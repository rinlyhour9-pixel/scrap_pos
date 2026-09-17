<?php

namespace App\Http\Controllers;

use App\Models\{Expense, ExpenseCategory, Material, Purchase, Sale, StockAdjustment, StockMovement};
use App\Services\InventoryService;
use App\Support\Currency;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OperationsController
{
    public function inventory(Request $r)
    {
        $items = Material::with(['category', 'unit'])
            ->when($r->search, fn ($q) => $q->where(fn ($material) => $material->where('name', 'like', '%' . $r->search . '%')->orWhere('khmer_name', 'like', '%' . $r->search . '%')))
            ->paginate(20);

        return view('inventory.index', compact('items'));
    }

    public function movements()
    {
        return view('inventory.movements', ['items' => StockMovement::with('material')->latest('moved_at')->paginate(30)]);
    }

    public function adjust(Request $r, InventoryService $inventory)
    {
        $d = $r->validate(['material_id' => 'required|exists:materials,id', 'type' => 'required|in:adjustment_add,adjustment_remove,damage,loss,correction', 'quantity' => 'required|numeric|min:0.001', 'reason' => 'required', 'adjusted_at' => 'required|date']);
        $m = Material::findOrFail($d['material_id']);
        $qty = in_array($d['type'], ['adjustment_remove', 'damage', 'loss']) ? -$d['quantity'] : $d['quantity'];

        try {
            \DB::transaction(function () use ($d, $m, $qty, $inventory) {
                $before = $m->current_stock;
                $inventory->move($m, $qty, $d['type'], (float) $m->average_cost, 'adjustment', null, $d['reason']);
                StockAdjustment::create($d + ['stock_before' => $before, 'stock_after' => $m->fresh()->current_stock]);
            });

            return back()->with('success', 'Stock adjustment recorded.');
        } catch (\Throwable $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    public function purchases()
    {
        return view('transactions.index', ['title' => 'Purchases', 'records' => Purchase::with('customer')->latest('purchased_at')->paginate(20), 'type' => 'purchase']);
    }

    public function sales()
    {
        return view('transactions.index', ['title' => 'Sales', 'records' => Sale::with('customer')->latest('sold_at')->paginate(20), 'type' => 'sale']);
    }

    public function purchaseShow(Purchase $purchase)
    {
        $purchase->load('customer', 'items.material');
        return view('transactions.receipt', ['record' => $purchase, 'type' => 'Purchase']);
    }

    public function saleShow(Sale $sale)
    {
        $sale->load('customer', 'items.material');
        return view('transactions.receipt', ['record' => $sale, 'type' => 'Sale']);
    }

    public function expenses()
    {
        return view('expenses.index', ['expenses' => Expense::with('category')->latest('expense_date')->paginate(20), 'categories' => ExpenseCategory::where('status', 1)->get()]);
    }

    public function expenseStore(Request $r)
    {
        $d = $r->validate(['expense_category_id' => 'required|exists:expense_categories,id', 'amount' => 'required|numeric|min:0.01', 'expense_date' => 'required|date', 'payment_method' => 'required', 'description' => 'nullable']);
        $d['amount'] = Currency::input($d['amount']);
        Expense::create($d + ['number' => 'EXP-' . now()->format('Ymd') . '-' . str_pad((string) (Expense::whereDate('created_at', today())->count() + 1), 4, '0', STR_PAD_LEFT)]);

        return back()->with('success', 'Expense saved.');
    }

    public function reports()
    {
        $purchaseTotal = Purchase::sum('grand_total');
        $salesTotal = Sale::sum('grand_total');
        $expensesTotal = Expense::sum('amount');
        $costOfGoods = Sale::sum('cost_of_goods');

        return view('reports.index', compact('purchaseTotal', 'salesTotal', 'expensesTotal', 'costOfGoods'));
    }

    public function purchaseReport(Request $r)
    {
        [$start, $end] = $this->reportRange($r);
        $records = Purchase::whereBetween('purchased_at', [$start, $end])->orderBy('purchased_at')->get();

        return view('reports.purchases', [
            'start' => $start,
            'end' => $end,
            'daily' => $records->groupBy(fn ($item) => $item->purchased_at->format('d M Y')),
            'monthly' => $records->groupBy(fn ($item) => $item->purchased_at->format('M Y')),
            'total' => $records->sum('grand_total'),
            'weight' => $records->sum('total_weight'),
        ]);
    }

    public function salesReport(Request $r)
    {
        [$start, $end] = $this->reportRange($r);
        $records = Sale::whereBetween('sold_at', [$start, $end])->orderBy('sold_at')->get();

        return view('reports.sales', [
            'start' => $start,
            'end' => $end,
            'daily' => $records->groupBy(fn ($item) => $item->sold_at->format('d M Y')),
            'monthly' => $records->groupBy(fn ($item) => $item->sold_at->format('M Y')),
            'total' => $records->sum('grand_total'),
            'weight' => $records->sum('total_weight'),
        ]);
    }

    public function profitReport(Request $r)
    {
        [$start, $end] = $this->reportRange($r);
        $sales = Sale::whereBetween('sold_at', [$start, $end])->get();
        $expenses = Expense::whereBetween('expense_date', [$start->toDateString(), $end->toDateString()])->sum('amount');
        $revenue = $sales->sum('grand_total');
        $costOfGoods = $sales->sum('cost_of_goods');

        return view('reports.profit', compact('start', 'end', 'revenue', 'costOfGoods', 'expenses'));
    }

    public function expenseReport(Request $r)
    {
        [$start, $end] = $this->reportRange($r);
        $records = Expense::with('category')->whereBetween('expense_date', [$start->toDateString(), $end->toDateString()])->orderBy('expense_date')->get();

        return view('reports.expenses', ['start' => $start, 'end' => $end, 'byCategory' => $records->groupBy(fn ($item) => $item->category->name ?? 'Uncategorized'), 'total' => $records->sum('amount')]);
    }

    public function inventoryValueReport()
    {
        $inventory = Material::with(['category', 'unit'])->orderBy('name')->get();

        return view('reports.inventory-value', ['inventory' => $inventory, 'stockValue' => $inventory->sum(fn ($item) => $item->current_stock * $item->average_cost)]);
    }

    public function movementReport(Request $r)
    {
        [$start, $end] = $this->reportRange($r);
        $movements = StockMovement::with('material.unit')->whereBetween('moved_at', [$start, $end])->latest('moved_at')->get();

        return view('reports.movements', compact('start', 'end', 'movements'));
    }

    private function reportRange(Request $r): array
    {
        $r->validate(['start_date' => 'nullable|date', 'end_date' => 'nullable|date']);
        $start = Carbon::parse($r->input('start_date', now()->startOfMonth()->toDateString()))->startOfDay();
        $end = Carbon::parse($r->input('end_date', now()->toDateString()))->endOfDay();

        return $start->greaterThan($end)
            ? [$end->copy()->startOfDay(), $start->copy()->endOfDay()]
            : [$start, $end];
    }
}

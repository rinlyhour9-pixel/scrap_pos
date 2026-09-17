<?php

namespace App\Http\Controllers;

use App\Models\{Customer, Material, MaterialCategory};
use App\Services\{PurchaseService, SaleService};
use App\Support\Currency;
use Illuminate\Http\Request;

class PosController
{
    public function buy()
    {
        return view('pos.buy', ['materials' => Material::with('unit')->where('status', 1)->get(), 'categories' => MaterialCategory::where('status', 1)->get(), 'customers' => Customer::orderBy('name')->get()]);
    }

    public function sell()
    {
        return view('pos.sell', ['materials' => Material::with('unit')->where('status', 1)->where('current_stock', '>', 0)->get(), 'categories' => MaterialCategory::where('status', 1)->get(), 'customers' => Customer::orderBy('name')->get()]);
    }

    public function purchase(Request $r, PurchaseService $service)
    {
        $d = $r->validate(['customer_id' => 'nullable|exists:customers,id', 'items' => 'required|array|min:1', 'items.*.material_id' => 'required|exists:materials,id', 'items.*.gross_weight' => 'required|numeric|min:0.001', 'items.*.tare_weight' => 'nullable|numeric|min:0', 'items.*.unit_price' => 'required|numeric|min:0', 'discount' => 'nullable|numeric|min:0', 'other_cost' => 'nullable|numeric|min:0', 'amount_paid' => 'nullable|numeric|min:0', 'payment_method' => 'required']);

        try {
            $p = $service->create($this->fromDisplayCurrency($d));
            return redirect()->route('purchases.show', $p)->with('success', 'Purchase saved and inventory updated.');
        } catch (\Throwable $e) {
            return back()->withInput()->withErrors($e->getMessage());
        }
    }

    public function sale(Request $r, SaleService $service)
    {
        $d = $r->validate(['customer_id' => 'nullable|exists:customers,id', 'items' => 'required|array|min:1', 'items.*.material_id' => 'required|exists:materials,id', 'items.*.weight' => 'required|numeric|min:0.001', 'items.*.unit_price' => 'required|numeric|min:0', 'discount' => 'nullable|numeric|min:0', 'other_cost' => 'nullable|numeric|min:0', 'amount_paid' => 'nullable|numeric|min:0', 'payment_method' => 'required']);

        try {
            $s = $service->create($this->fromDisplayCurrency($d));
            return redirect()->route('sales.show', $s)->with('success', 'Sale saved and inventory updated.');
        } catch (\Throwable $e) {
            return back()->withInput()->withErrors($e->getMessage());
        }
    }

    private function fromDisplayCurrency(array $data): array
    {
        foreach (['discount', 'other_cost', 'amount_paid'] as $field) {
            $data[$field] = Currency::input($data[$field] ?? 0);
        }

        foreach ($data['items'] as $key => $item) {
            $data['items'][$key]['unit_price'] = Currency::input($item['unit_price']);
        }

        return $data;
    }
}

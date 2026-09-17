<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $this->changePrecision(4);
    }

    public function down(): void
    {
        $this->changePrecision(2);
    }

    private function changePrecision(int $scale): void
    {
        foreach ([
            'materials' => ['purchase_price', 'selling_price', 'average_cost'],
            'customers' => ['outstanding_balance'],
            'suppliers' => ['outstanding_balance'],
            'purchases' => ['subtotal', 'discount', 'other_cost', 'grand_total', 'amount_paid', 'balance'],
            'purchase_items' => ['unit_price', 'subtotal', 'cost_per_unit'],
            'sales' => ['subtotal', 'discount', 'other_cost', 'grand_total', 'amount_paid', 'balance', 'cost_of_goods'],
            'sale_items' => ['unit_price', 'subtotal', 'cost_per_unit', 'cost_total'],
            'stock_movements' => ['unit_cost'],
            'expenses' => ['amount'],
            'payments' => ['amount'],
            'cash_registers' => ['opening_cash', 'actual_closing_cash'],
        ] as $table => $columns) {
            Schema::table($table, function (Blueprint $blueprint) use ($columns, $scale, $table) {
                foreach ($columns as $column) {
                    $definition = $blueprint->decimal($column, 14, $scale);
                    if ($table === 'cash_registers' && $column === 'actual_closing_cash') {
                        $definition->nullable();
                    } else {
                        $definition->default(0);
                    }
                    $definition->change();
                }
            });
        }
    }
};

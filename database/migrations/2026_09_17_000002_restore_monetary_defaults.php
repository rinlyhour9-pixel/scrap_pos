<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
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
            'cash_registers' => ['opening_cash'],
        ] as $tableName => $columns) {
            Schema::table($tableName, function (Blueprint $table) use ($columns) {
                foreach ($columns as $column) {
                    $table->decimal($column, 14, 4)->default(0)->change();
                }
            });
        }

        Schema::table('cash_registers', fn (Blueprint $table) => $table->decimal('actual_closing_cash', 14, 4)->nullable()->change());
    }

    public function down(): void
    {
        // Defaults are safe for both the two- and four-decimal representations.
    }
};

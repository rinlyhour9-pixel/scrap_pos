<?php

namespace Database\Seeders;

use App\Models\{Customer, ExpenseCategory, MaterialCategory, Unit, User};
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@example.com'], ['name' => 'Administrator', 'password' => 'password']);

        foreach ([['KG', 'Kilogram'], ['Ton', 'Ton'], ['Piece', 'Piece'], ['Bag', 'Bag'], ['Box', 'Box']] as [$symbol, $name]) {
            Unit::firstOrCreate(['symbol' => $symbol], ['name' => $name, 'status' => true]);
        }

        foreach ([
            ['Metal', 'លោហៈ'], ['Plastic', 'ប្លាស្ទិក'], ['Paper', 'ក្រដាស'], ['Glass', 'កញ្ចក់'],
            ['Battery', 'ថ្មពិល'], ['Electronic Waste', 'កាកសំណល់អេឡិចត្រូនិក'], ['Other', 'ផ្សេងៗ'],
        ] as [$name, $khmerName]) {
            MaterialCategory::firstOrCreate(['name' => $name], ['khmer_name' => $khmerName, 'status' => true]);
        }

        foreach (['Transportation', 'Fuel', 'Electricity', 'Water', 'Rent', 'Repair', 'Equipment', 'Food', 'Other'] as $name) {
            ExpenseCategory::firstOrCreate(['name' => $name], ['status' => true]);
        }

        foreach ([
            ['CUS-0001', 'Sok Dara', '012 345 678', 'Individual'],
            ['CUS-0002', 'Vannak Collector', '097 456 789', 'Scrap Collector'],
            ['CUS-0003', 'Srey Mom', '088 123 456', 'Regular Customer'],
        ] as [$code, $name, $phone, $type]) {
            Customer::firstOrCreate(['code' => $code], ['name' => $name, 'phone' => $phone, 'type' => $type]);
        }

        $this->call(ScrapPriceListSeeder::class);
    }
}

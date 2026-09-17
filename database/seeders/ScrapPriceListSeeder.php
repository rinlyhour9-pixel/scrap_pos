<?php

namespace Database\Seeders;

use App\Models\{Material, MaterialCategory, Unit};
use App\Support\Currency;
use Illuminate\Database\Seeder;

class ScrapPriceListSeeder extends Seeder
{
    public function run(): void
    {
        $kg = Unit::firstOrCreate(['symbol' => 'KG'], ['name' => 'Kilogram', 'status' => true]);
        $metal = MaterialCategory::firstOrCreate(['name' => 'Metal'], ['khmer_name' => 'លោហៈ', 'status' => true]);
        $paper = MaterialCategory::firstOrCreate(['name' => 'Paper'], ['khmer_name' => 'ក្រដាស', 'status' => true]);

        foreach ($this->materials() as [$code, $name, $khmerName, $category, $purchaseRiel, $status, $note]) {
            $sellingRiel = $status ? (int) round($purchaseRiel * 1.10) : 0;

            Material::firstOrCreate(['code' => $code], [
                'name' => $name,
                'khmer_name' => $khmerName,
                'category_id' => $category === 'Paper' ? $paper->id : $metal->id,
                'unit_id' => $kg->id,
                'purchase_price' => Currency::rielToBase($purchaseRiel),
                'selling_price' => Currency::rielToBase($sellingRiel),
                'minimum_stock' => 10,
                'description' => $note,
                'status' => $status,
            ]);
        }
    }

    private function materials(): array
    {
        return [
            ['KHR-L01', 'Iron Scrap No. 1', 'ដែកលេខ១ល្អ', 'Metal', 1060, true, 'Reference price list'],
            ['KHR-L02', 'Mixed Iron Scrap No. 2', 'ដែកលេខ២លាយ', 'Metal', 1060, true, 'Reference price list'],
            ['KHR-L03', 'Clean Iron Scrap No. 2', 'ដែកលេខ២ ស្អាត', 'Metal', 850, true, 'Reference price list'],
            ['KHR-L04', 'Iron Scrap Grade 4', 'ដែក', 'Metal', 1060, true, 'Reference price list'],
            ['KHR-L05', 'Iron Scrap Grade 5', 'ដែក', 'Metal', 900, true, 'Reference price list'],
            ['KHR-L06', 'Copper-Coated Iron Scrap', 'ដែកស្ពាន់សាយ', 'Metal', 650, true, 'Reference price list'],
            ['KHR-L07', 'Mixed Can Scrap', 'ដែកកំប៉ុងលាយ', 'Metal', 650, true, 'Reference price list'],
            ['KHR-L08', 'Clean Can Scrap Grade 1', 'ដែកកំប៉ុង ស្អាត', 'Metal', 700, true, 'Reference price list'],
            ['KHR-L09', 'Clean Can Scrap Grade 2', 'ដែកកំប៉ុង ស្អាត', 'Metal', 700, true, 'Reference price list'],
            ['KHR-L10', 'Construction Can Scrap', 'ដែកកំប៉ុង សំណង់', 'Metal', 700, true, 'Reference price list'],
            ['KHR-L11', 'Crimped Can Scrap', 'កំប៉ុងក្រីបកាត់', 'Metal', 9700, true, 'Price decreased'],
            ['KHR-L12', 'Small Mixed Crimped Can Scrap', 'កំប៉ុងក្រីបតូច / មូលលាយ', 'Metal', 9650, true, 'Price decreased'],
            ['KHR-L13', 'Small Crimped Steel Scrap', 'ដែកទង់ក្រីបតូច', 'Metal', 8300, true, 'Price decreased by 3%'],
            ['KHR-L14', 'Clean Cut Crimped Steel', 'ដែកទង់ក្រីបកាត់ស្អាត', 'Metal', 8200, true, 'Price decreased by 3%'],
            ['KHR-L15', 'Clean Mixed Steel', 'ដែកទង់លាយស្អាត', 'Metal', 8200, true, 'Price decreased'],
            ['KHR-L16', 'Clean Old Steel', 'ដែកសាច់ចាស់៖ដែកស្អាត', 'Metal', 9200, true, 'Price decreased by 1%'],
            ['KHR-L17', 'Old Steel Scrap', 'ដែកសាច់ចាស់', 'Metal', 9100, true, 'Price decreased by 3%'],
            ['KHR-L18', 'Clean Bone Iron No. 1', 'ដែកឆ្អឹងលេខ១ ស្អាត', 'Metal', 11800, true, 'Price decreased'],
            ['KHR-L19', 'Bone Iron No. 2', 'ដែកឆ្អឹងលេខ២', 'Metal', 10800, true, 'Price decreased'],
            ['KHR-L20', 'Clean Old Rolled Crimped Iron', 'ដែកក្រឡុកក្រីបដុំចាស់៖ស្អាត', 'Metal', 10500, true, 'Price decreased'],
            ['KHR-L21', 'Clean Mixed Rolled Iron', 'ដែកក្រឡុកលាយចាស់៖ស្អាត', 'Metal', 10400, true, 'Price decreased'],
            ['KHR-L22', 'Old Rolled Iron', 'ដែកក្រឡុក', 'Metal', 10400, true, 'Price decreased'],
            ['KHR-L23', 'Old Rolled Iron 90%', 'ដែកក្រឡុកចាស់ 90%', 'Metal', 9000, true, 'Reference price list'],
            ['KHR-L24', 'Old Iron Without Red', 'ដែកចាស់ អត់ក្រហម', 'Metal', 10800, true, 'Price decreased'],
            ['KHR-L25', 'Old Red Iron', 'ដែកចាស់ ក្រហម', 'Metal', 10400, true, 'Price decreased'],
            ['KHR-L26', 'Old Scrap', 'ចាស់', 'Metal', 10400, true, 'Price decreased'],
            ['KHR-L27', 'Clean Water Tank Sheet Iron', 'ដែកធុងទឹកបន្ទះៗដោះស្អាត', 'Metal', 7800, true, 'Price decreased'],
            ['KHR-R01', 'Clean Excavator Tank Iron', 'ដែកធុងទីកាយចាស់៖ស្អាត', 'Metal', 7800, true, 'Price decreased'],
            ['KHR-R02', 'Clean Mixed Steel', 'ដែកអង្កត់ ស្អាត', 'Metal', 9200, true, 'Price decreased by 1%'],
            ['KHR-R03', 'Brass Iron Scrap', 'ដែកស្ពាន់ប្រាំង', 'Metal', 6400, true, 'Price decreased'],
            ['KHR-R04', 'Steel Grade 1', 'ដែកទង់ ប្រភេទទី១ ស្អាត', 'Metal', 6000, true, 'Reference price list'],
            ['KHR-R05', 'Clean Red Steel', 'ទង់ក្រហម ដែកស្អាត', 'Metal', 7500, true, 'Reference price list'],
            ['KHR-R06', 'Grey Can Scrap', 'ដែកកំប៉ុងប្រផេះ', 'Metal', 8600, true, 'Reference price list'],
            ['KHR-R07', 'Alloy Scrap', 'អាំងស៊ីប', 'Metal', 6800, true, 'Reference price list'],
            ['KHR-R08', 'Clean Old Wheel Scrap', 'កង់ចាស់ស្អាត', 'Metal', 9200, true, 'Reference price list'],
            ['KHR-R09', 'Mixed Steel Scrap', 'ដែកសាច់', 'Metal', 5100, true, 'Reference price list'],
            ['KHR-R10', 'Steel Shell Scrap', 'ដែកសំបក', 'Metal', 3500, true, 'Reference price list'],
            ['KHR-R11', 'Stainless Steel No. 1', 'អ៊ីណុកលេខ១', 'Metal', 0, false, 'Purchasing paused'],
            ['KHR-R12', 'Stainless Steel No. 2', 'អ៊ីណុកលេខ២', 'Metal', 0, false, 'Purchasing paused'],
            ['KHR-R13', 'Red Copper No. 1', 'ស្ពាន់ក្រហមលេខ១', 'Metal', 54500, true, 'Price decreased'],
            ['KHR-R14', 'Red Copper No. 2', 'ស្ពាន់ក្រហមលេខ២', 'Metal', 53500, true, 'Price decreased'],
            ['KHR-R15', 'Copper Grade 3', 'ស្ពាន់ដុំ លេខ៣', 'Metal', 51500, true, 'Price decreased'],
            ['KHR-R16', 'Red Copper Grade 4', 'ស្ពាន់ក្រហម លេខ៤', 'Metal', 50500, true, 'Reference price list'],
            ['KHR-R17', 'Clean Copper Scrap', 'ស្ពាន់ ដែកស្អាត', 'Metal', 34000, true, 'Reference price list'],
            ['KHR-R18', 'Copper Scrap Grade 2', 'ស្ពាន់ ដែកស្អាត', 'Metal', 29500, true, 'Reference price list'],
            ['KHR-R19', 'Aluminum', 'អាលុយមីញ៉ូម', 'Metal', 4150, true, 'Price increased'],
            ['KHR-R20', 'Aluminum Scrap', 'អាលុយមីញ៉ូម', 'Metal', 4150, true, 'Price increased'],
            ['KHR-R21', 'Crimped Paper, 200 KG+', 'ក្រដាសកេះក្រីបដុំតូច ចាប់ពី 200kg ឡើង', 'Paper', 500, true, 'Price increased; minimum 200 KG'],
            ['KHR-R22', 'Clean Mixed Paper', 'ក្រដាសកេះលាយស្អាត', 'Paper', 490, true, 'Price increased'],
            ['KHR-R23', 'Book Paper', 'ក្រដាសសៀវភៅ', 'Paper', 350, true, 'Reference price list'],
            ['KHR-R24', 'Clean Ream Paper and Book Covers', 'ក្រដាសរាមស្អាត សៀវភៅសំបកក្រប', 'Paper', 480, true, 'Reference price list'],
            ['KHR-R25', 'Crimped Paper', 'ក្រដាសក្រីប', 'Paper', 200, true, 'Reference price list'],
        ];
    }
}

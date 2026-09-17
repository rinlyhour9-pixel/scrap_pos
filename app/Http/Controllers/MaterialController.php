<?php

namespace App\Http\Controllers;

use App\Models\{Material, MaterialCategory, Unit};
use App\Support\Currency;
use Illuminate\Http\Request;

class MaterialController
{
    public function index(Request $r)
    {
        $q = Material::with(['category', 'unit']);
        if ($r->search) $q->where(fn ($x) => $x->where('name', 'like', '%' . $r->search . '%')->orWhere('khmer_name', 'like', '%' . $r->search . '%')->orWhere('code', 'like', '%' . $r->search . '%'));

        return view('materials.index', ['materials' => $q->latest()->paginate(15), 'categories' => MaterialCategory::where('status', 1)->get(), 'units' => Unit::where('status', 1)->get()]);
    }

    public function store(Request $r)
    {
        $d = $r->validate(['code' => 'required|unique:materials', 'name' => 'required', 'category_id' => 'required|exists:material_categories,id', 'unit_id' => 'required|exists:units,id', 'purchase_price' => 'required|numeric|min:0', 'selling_price' => 'required|numeric|min:0', 'minimum_stock' => 'nullable|numeric|min:0', 'khmer_name' => 'nullable', 'description' => 'nullable']);
        Material::create($this->fromDisplayCurrency($d) + ['status' => 1]);

        return back()->with('success', 'Material added.');
    }

    public function update(Request $r, Material $material)
    {
        $d = $r->validate(['name' => 'required', 'category_id' => 'required|exists:material_categories,id', 'unit_id' => 'required|exists:units,id', 'purchase_price' => 'required|numeric|min:0', 'selling_price' => 'required|numeric|min:0', 'minimum_stock' => 'nullable|numeric|min:0', 'khmer_name' => 'nullable', 'description' => 'nullable', 'status' => 'nullable']);
        $material->update($this->fromDisplayCurrency($d) + ['status' => $r->boolean('status')]);

        return back()->with('success', 'Material updated.');
    }

    public function destroy(Material $material)
    {
        if ($material->current_stock > 0) return back()->withErrors('Cannot delete material with stock. Deactivate it instead.');
        $material->delete();

        return back()->with('success', 'Material deleted.');
    }

    private function fromDisplayCurrency(array $data): array
    {
        foreach (['purchase_price', 'selling_price'] as $field) {
            $data[$field] = Currency::input($data[$field]);
        }

        return $data;
    }
}

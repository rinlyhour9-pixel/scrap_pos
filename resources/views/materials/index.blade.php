@extends('layouts.app') @section('content')
    <div class="page-title">
        <div>
            <h1>{{ __('pos.scrap_materials') }}</h1>
            <p>{{ __('pos.prices_units') }}</p>
        </div><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add">+
            {{ __('pos.add_material') }}</button>
    </div>
    <form class="mb-3"><input class="form-control" name="search" value="{{ request('search') }}"
            placeholder="{{ __('pos.search_code_material') }}"></form>
    <div class="card p-3 table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>{{ __('pos.code') }}</th>
                    <th>{{ __('pos.material') }}</th>
                    <th>{{ __('pos.category') }}</th>
                    <th>{{ __('pos.buy') }}</th>
                    <th>{{ __('pos.sell') }}</th>
                    <th>{{ __('pos.stock') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($materials as $m)
                    <tr>
                        <td>{{ $m->code }}</td>
                        <td><b>{{ app()->getLocale() === 'km' && $m->khmer_name ? $m->khmer_name : $m->name }}</b><small
                                class="d-block">{{ app()->getLocale() === 'km' ? $m->name : $m->khmer_name }}</small></td>
                        <td>{{ $m->category->name }}</td>
                        <td>{{ \App\Support\Currency::format($m->purchase_price) }}</td>
                        <td>{{ \App\Support\Currency::format($m->selling_price) }}</td>
                        <td>{{ $m->current_stock }} {{ $m->unit->symbol }}</td>
                        <td>
                            @php($editData = ['code' => $m->code, 'name' => $m->name, 'khmer_name' => $m->khmer_name, 'category_id' => $m->category_id, 'unit_id' => $m->unit_id, 'purchase_price' => \App\Support\Currency::display($m->purchase_price), 'selling_price' => \App\Support\Currency::display($m->selling_price), 'minimum_stock' => $m->minimum_stock, 'description' => $m->description, 'status' => (bool) $m->status])
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary edit-material" data-bs-toggle="modal"
                                    data-bs-target="#edit" data-action="{{ route('materials.update', $m) }}"
                                    data-material='@json($editData)'>Edit</button>
                                <form method="post" action="{{ route('materials.destroy', $m) }}">@csrf
                                    @method('delete')<button class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Delete this material?')">{{ __('pos.delete') }}</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>{{ $materials->links() }}
    </div>
    <div class="modal fade" id="add">
        <div class="modal-dialog">
            <form class="modal-content" method="post" action="{{ route('materials.store') }}">@csrf<div
                    class="modal-header">
                    <h5>{{ __('pos.add_material') }}</h5>
                </div>
                <div class="modal-body row g-2">
                    <div class="col-5"><input class="form-control" name="code" placeholder="{{ __('pos.code') }}"
                            required></div>
                    <div class="col-7"><input class="form-control" name="name"
                            placeholder="{{ __('pos.material_name') }}" required></div>
                    <div class="col-12"><input class="form-control" name="khmer_name"
                            placeholder="{{ __('pos.khmer_name') }}"></div>
                    <div class="col-6"><select name="category_id" class="form-select">
                            @foreach ($categories as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6"><select name="unit_id" class="form-select">
                            @foreach ($units as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->symbol }})</option>
                            @endforeach
                        </select></div>
                    <div class="col-6"><input type="number" step="{{ \App\Support\Currency::usesRiel() ? '1' : '.01' }}" class="form-control" name="purchase_price"
                            placeholder="{{ __('pos.purchase_price') }}" required></div>
                    <div class="col-6"><input type="number" step="{{ \App\Support\Currency::usesRiel() ? '1' : '.01' }}" class="form-control" name="selling_price"
                            placeholder="{{ __('pos.selling_price') }}" required></div>
                    <div class="col-12"><input type="number" step=".001" class="form-control" name="minimum_stock"
                            placeholder="{{ __('pos.minimum_stock') }}" value="0"></div>
                    <div class="col-12">
                        <textarea class="form-control" name="description" placeholder="{{ __('pos.description') }}"></textarea>
                    </div>
                </div>
                <div class="modal-footer"><button class="btn btn-primary">{{ __('pos.save_material') }}</button></div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="edit" tabindex="-1" aria-labelledby="editMaterialTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" method="post" id="editMaterialForm">@csrf @method('put')
                <div class="modal-header"><h5 id="editMaterialTitle">Update material</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body row g-2">
                    <div class="col-4"><label class="form-label">{{ __('pos.code') }}</label><input class="form-control" id="editCode" disabled></div>
                    <div class="col-8"><label class="form-label">{{ __('pos.material_name') }}</label><input class="form-control" name="name" id="editName" required></div>
                    <div class="col-12"><label class="form-label">{{ __('pos.khmer_name') }}</label><input class="form-control" name="khmer_name" id="editKhmerName"></div>
                    <div class="col-6"><label class="form-label">{{ __('pos.category') }}</label><select name="category_id" class="form-select" id="editCategory" required>@foreach ($categories as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach</select></div>
                    <div class="col-6"><label class="form-label">Unit</label><select name="unit_id" class="form-select" id="editUnit" required>@foreach ($units as $u)<option value="{{ $u->id }}">{{ $u->name }} ({{ $u->symbol }})</option>@endforeach</select></div>
                    <div class="col-6"><label class="form-label">{{ __('pos.purchase_price') }}</label><input type="number" min="0" step="{{ \App\Support\Currency::usesRiel() ? '1' : '.01' }}" class="form-control" name="purchase_price" id="editPurchasePrice" required></div>
                    <div class="col-6"><label class="form-label">{{ __('pos.selling_price') }}</label><input type="number" min="0" step="{{ \App\Support\Currency::usesRiel() ? '1' : '.01' }}" class="form-control" name="selling_price" id="editSellingPrice" required></div>
                    <div class="col-6"><label class="form-label">{{ __('pos.minimum_stock') }}</label><input type="number" min="0" step=".001" class="form-control" name="minimum_stock" id="editMinimumStock"></div>
                    <div class="col-6 d-flex align-items-end"><div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="status" value="1" id="editStatus"><label class="form-check-label" for="editStatus">Active material</label></div></div>
                    <div class="col-12"><label class="form-label">{{ __('pos.description') }}</label><textarea class="form-control" name="description" id="editDescription" rows="3"></textarea></div>
                </div>
                <div class="modal-footer"><button class="btn btn-primary">Update material</button></div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.querySelectorAll('.edit-material').forEach(button => button.addEventListener('click', () => {
            const material = JSON.parse(button.dataset.material);
            document.querySelector('#editMaterialForm').action = button.dataset.action;
            document.querySelector('#editCode').value = material.code;
            document.querySelector('#editName').value = material.name;
            document.querySelector('#editKhmerName').value = material.khmer_name || '';
            document.querySelector('#editCategory').value = material.category_id;
            document.querySelector('#editUnit').value = material.unit_id;
            document.querySelector('#editPurchasePrice').value = material.purchase_price;
            document.querySelector('#editSellingPrice').value = material.selling_price;
            document.querySelector('#editMinimumStock').value = material.minimum_stock;
            document.querySelector('#editDescription').value = material.description || '';
            document.querySelector('#editStatus').checked = material.status;
        }));
    </script>
@endpush

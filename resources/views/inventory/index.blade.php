@extends('layouts.app') @section('content')
    <div class="page-title">
        <div>
            <h1>{{ __('pos.current_stock') }}</h1>
            <p>{{ __('pos.inventory_overview') }}</p>
        </div><button class="btn btn-outline-primary" data-bs-toggle="modal"
            data-bs-target="#adjust">{{ __('pos.stock_adjustment') }}</button>
    </div>
    <form class="mb-3"><input class="form-control" name="search" value="{{ request('search') }}"
            placeholder="{{ __('pos.search_material') }}"></form>
    <div class="card p-3 table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>{{ __('pos.material') }}</th>
                    <th>{{ __('pos.category') }}</th>
                    <th>{{ __('pos.stock') }}</th>
                    <th>{{ __('pos.average_cost') }}</th>
                    <th>{{ __('pos.sell') }}</th>
                    <th>{{ __('pos.stock_value') }}</th>
                    <th>{{ __('pos.status') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $m)
                    <tr>
                        <td><b>{{ app()->getLocale() === 'km' && $m->khmer_name ? $m->khmer_name : $m->name }}</b><small
                                class="d-block">{{ $m->code }}</small></td>
                        <td>{{ $m->category->name }}</td>
                        <td>{{ $m->current_stock }} {{ $m->unit->symbol }}</td>
                        <td>{{ \App\Support\Currency::format($m->average_cost) }}</td>
                        <td>{{ \App\Support\Currency::format($m->selling_price) }}</td>
                        <td>{{ \App\Support\Currency::format($m->current_stock * $m->average_cost) }}</td>
                        <td>
                            @if ($m->current_stock <= 0)
                                <span class="badge text-bg-danger">{{ __('pos.out_of_stock') }}</span>
                            @elseif($m->current_stock <= $m->minimum_stock)
                            <span class="badge text-bg-warning">{{ __('pos.low_stock') }}</span>@else<span
                                    class="badge text-bg-success">{{ __('pos.in_stock') }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>{{ $items->links() }}
    </div>
    <div class="modal fade" id="adjust">
        <div class="modal-dialog">
            <form method="post" action="{{ route('inventory.adjust') }}" class="modal-content">@csrf<div
                    class="modal-header">
                    <h5>{{ __('pos.stock_adjustment') }}</h5>
                </div>
                <div class="modal-body"><select name="material_id" class="form-select mb-2">
                        @foreach ($items as $m)
                            <option value="{{ $m->id }}">{{ app()->getLocale() === 'km' && $m->khmer_name ? $m->khmer_name : $m->name }} ({{ $m->current_stock }})</option>
                        @endforeach
                    </select><select name="type" class="form-select mb-2">
                        <option value="adjustment_add">{{ __('pos.add_stock') }}</option>
                        <option value="adjustment_remove">{{ __('pos.remove_stock') }}</option>
                        <option value="damage">{{ __('pos.damage') }}</option>
                        <option value="loss">{{ __('pos.loss') }}</option>
                        <option value="correction">{{ __('pos.correction') }}</option>
                    </select><input class="form-control mb-2" name="quantity" type="number" step=".001"
                        placeholder="{{ __('pos.quantity') }}" required><input class="form-control mb-2" name="adjusted_at"
                        type="date" value="{{ today()->format('Y-m-d') }}">
                    <textarea class="form-control" name="reason" placeholder="{{ __('pos.reason') }}" required></textarea>
                </div>
                <div class="modal-footer"><button class="btn btn-primary">{{ __('pos.save_adjustment') }}</button></div>
            </form>
        </div>
    </div>
@endsection

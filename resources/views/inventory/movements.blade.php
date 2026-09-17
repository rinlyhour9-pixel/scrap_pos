@extends('layouts.app') @section('content')
    <div class="page-title">
        <div>
            <h1>{{ __('pos.stock_movement') }}</h1>
            <p>{{ __('pos.traceable') }}</p>
        </div>
    </div>
    <div class="card p-3 table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('pos.date') }}</th>
                    <th>{{ __('pos.material') }}</th>
                    <th>{{ __('pos.type') }}</th>
                    <th>{{ __('pos.before') }}</th>
                    <th>{{ __('pos.change') }}</th>
                    <th>{{ __('pos.after') }}</th>
                    <th>{{ __('pos.reason') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $m)
                    <tr>
                        <td>{{ $m->moved_at->format('d M Y H:i') }}</td>
                        <td>{{ app()->getLocale() === 'km' && $m->material->khmer_name ? $m->material->khmer_name : $m->material->name }}</td>
                        <td><span class="badge text-bg-light">{{ $m->type }}</span></td>
                        <td>{{ $m->stock_before }}</td>
                        <td class="{{ $m->quantity > 0 ? 'text-success' : 'text-danger' }}">
                            {{ $m->quantity > 0 ? '+' : '' }}{{ $m->quantity }}</td>
                        <td>{{ $m->stock_after }}</td>
                        <td>{{ $m->reason }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>{{ $items->links() }}
    </div>
@endsection

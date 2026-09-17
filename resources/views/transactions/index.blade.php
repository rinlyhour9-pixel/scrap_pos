@extends('layouts.app') @section('content')
    <div class="page-title">
        <div>
            <h1>{{ $title }}</h1>
            <p>Completed {{ $title }} history</p>
        </div><a class="btn btn-primary" href="{{ route($type === 'purchase' ? 'pos.buy' : 'pos.sell') }}">+ New
            {{ rtrim($title, 's') }}</a>
    </div>
    <div class="card p-3 table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Number</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Weight</th>
                    <th>Total</th>
                    <th>Paid</th>
                    <th>Balance</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($records as $r)
                    <tr>
                        <td><b>{{ $r->number }}</b></td>
                        <td>{{ ($type === 'purchase' ? $r->purchased_at : $r->sold_at)->format('d M Y H:i') }}</td>
                        <td>{{ $r->customer->name ?? 'Walk-in' }}</td>
                        <td>{{ $r->total_weight }}</td>
                        <td>{{ \App\Support\Currency::format($r->grand_total) }}</td>
                        <td>{{ \App\Support\Currency::format($r->amount_paid) }}</td>
                        <td>{{ \App\Support\Currency::format($r->balance) }}</td>
                        <td><a class="btn btn-sm btn-outline-primary"
                                href="{{ route($type === 'purchase' ? 'purchases.show' : 'sales.show', $r) }}">View</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>{{ $records->links() }}
    </div>
@endsection

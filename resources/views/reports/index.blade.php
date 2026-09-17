@extends('layouts.app')

@section('content')
    <div class="page-title"><div><h1>{{ __('reports.title') }}</h1><p>{{ __('reports.choose') }}</p></div></div>
    <div class="metrics mb-4">
        <div class="metric blue"><span>{{ __('pos.total_sales') }}</span><b>{{ \App\Support\Currency::format($salesTotal) }}</b></div>
        <div class="metric green"><span>{{ __('pos.total_purchases') }}</span><b>{{ \App\Support\Currency::format($purchaseTotal) }}</b></div>
        <div class="metric orange"><span>{{ __('pos.total_expenses') }}</span><b>{{ \App\Support\Currency::format($expensesTotal) }}</b></div>
        <div class="metric purple"><span>{{ __('pos.net_profit') }}</span><b>{{ \App\Support\Currency::format($salesTotal - $costOfGoods - $expensesTotal) }}</b></div>
    </div>
    <div class="row g-3">
        @foreach ([
            ['reports.purchases', __('reports.purchase'), __('reports.purchase_description')],
            ['reports.sales', __('reports.sales'), __('reports.sales_description')],
            ['reports.profit', __('reports.profit'), __('reports.profit_description')],
            ['reports.expenses', __('reports.expense'), __('reports.expense_description')],
            ['reports.inventory-value', __('reports.inventory'), __('reports.inventory_description')],
            ['reports.movements', __('reports.movement'), __('reports.movement_description')],
        ] as [$route, $title, $description])
            <div class="col-md-6 col-xl-4"><a class="card h-100 p-4 text-decoration-none report-link" href="{{ route($route) }}"><h5 class="mb-2">{{ $title }}</h5><p class="mb-0 text-muted">{{ $description }}</p><span class="mt-3 fw-semibold">{{ __('reports.open') }} →</span></a></div>
        @endforeach
    </div>
@endsection

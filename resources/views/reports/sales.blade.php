@extends('layouts.app')
@section('content')
    <div class="page-title"><div><h1>{{ __('reports.sales') }}</h1><p>{{ $start->format('d M Y') }} – {{ $end->format('d M Y') }}</p></div><a class="btn btn-outline-secondary no-print" href="{{ route('reports.index') }}">{{ __('reports.all') }}</a></div>
    @include('reports.partials.date-filter')
    <div class="metrics mb-4"><div class="metric blue"><span>{{ __('reports.sales_total') }}</span><b>{{ \App\Support\Currency::format($total) }}</b></div><div class="metric green"><span>{{ __('reports.total_weight') }}</span><b>{{ number_format($weight, 3) }} KG</b></div></div>
    @include('reports.partials.period-summary', ['dailyTitle' => __('reports.daily_sales'), 'monthlyTitle' => __('reports.monthly_sales'), 'empty' => __('reports.no_sales')])
@endsection

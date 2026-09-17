@extends('layouts.app')
@section('content')
    <div class="page-title"><div><h1>{{ __('reports.expense') }}</h1><p>{{ $start->format('d M Y') }} – {{ $end->format('d M Y') }}</p></div><a class="btn btn-outline-secondary no-print" href="{{ route('reports.index') }}">{{ __('reports.all') }}</a></div>
    @include('reports.partials.date-filter')
    <div class="metrics mb-4"><div class="metric orange"><span>{{ __('pos.total_expenses') }}</span><b>{{ \App\Support\Currency::format($total) }}</b></div></div>
    <div class="card p-4 table-responsive"><h5>{{ __('reports.by_category') }}</h5><table class="table align-middle mb-0"><thead><tr><th>{{ __('reports.category') }}</th><th>{{ __('reports.entries') }}</th><th class="text-end">{{ __('reports.amount') }}</th></tr></thead><tbody>@forelse($byCategory as $category => $records)<tr><td>{{ $category }}</td><td>{{ $records->count() }}</td><td class="text-end">{{ \App\Support\Currency::format($records->sum('amount')) }}</td></tr>@empty<tr><td colspan="3" class="text-center text-muted">{{ __('reports.no_expenses') }}</td></tr>@endforelse</tbody><tfoot><tr><th colspan="2">{{ __('reports.total') }}</th><th class="text-end">{{ \App\Support\Currency::format($total) }}</th></tr></tfoot></table></div>
@endsection

@extends('layouts.app') @section('content')
    <div class="page-title">
        <div>
            <h1>{{ __('pos.dashboard') }}</h1>
            <p>{{ __('pos.today_overview') }}</p>
        </div><a class="btn btn-primary" href="{{ route('pos.buy') }}">+ {{ __('pos.new_purchase') }}</a>
    </div>
    <div class="metrics">
        <div class="metric green"><span>{{ __('pos.today_purchase') }}</span><b>{{ \App\Support\Currency::format($purchases) }}</b></div>
        <div class="metric blue"><span>{{ __('pos.today_sales') }}</span><b>{{ \App\Support\Currency::format($sales) }}</b></div>
        <div class="metric orange"><span>{{ __('pos.today_expenses') }}</span><b>{{ \App\Support\Currency::format($expenses) }}</b></div>
        <div class="metric purple">
            <span>{{ __('pos.today_profit') }}</span><b>{{ \App\Support\Currency::format($sales - $cogs - $expenses) }}</b></div>
    </div>
    <div class="row g-4 mt-1">
        <div class="col-lg-8">
            <div class="card p-4 h-100">
                <h5>{{ __('pos.business_performance') }}</h5>
                <div class="chart-bars"><i style="height:42%"></i><i style="height:70%"></i><i style="height:50%"></i><i
                        style="height:90%"></i><i style="height:62%"></i><i style="height:80%"></i><i
                        style="height:55%"></i></div><small class="text-muted">{{ __('pos.purchase_sales_trend') }}</small>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card p-4 h-100">
                <h5>{{ __('pos.business_snapshot') }}</h5>
                <dl class="snapshot">
                    <dt>{{ __('pos.stock_value') }}</dt>
                    <dd>{{ \App\Support\Currency::format($stockValue) }}</dd>
                    <dt>{{ __('pos.total_customers') }}</dt>
                    <dd>{{ $customers }}</dd>
                    <dt>{{ __('pos.total_suppliers') }}</dt>
                    <dd>{{ $suppliers }}</dd>
                    <dt>{{ __('pos.total_transactions') }}</dt>
                    <dd>{{ $transactions }}</dd>
                </dl>
            </div>
        </div>
        <div class="col-12">
            <div class="card p-4">
                <h5>{{ __('pos.recent_stock_movements') }}</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('pos.time') }}</th>
                                <th>{{ __('pos.material') }}</th>
                                <th>{{ __('pos.type') }}</th>
                                <th class="text-end">{{ __('pos.quantity') }}</th>
                                <th class="text-end">{{ __('pos.stock_after') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentMovements as $m)
                                <tr>
                                    <td>{{ $m->moved_at->format('d M H:i') }}</td>
                                    <td>{{ app()->getLocale() === 'km' && $m->material->khmer_name ? $m->material->khmer_name : $m->material->name }}</td>
                                    <td><span class="badge text-bg-light">{{ $m->type }}</span></td>
                                    <td class="text-end {{ $m->quantity > 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $m->quantity > 0 ? '+' : '' }}{{ $m->quantity }}</td>
                                    <td class="text-end">{{ $m->stock_after }}</td>
                            </tr>@empty<tr>
                                    <td colspan="5" class="text-center text-muted py-4">{{ __('pos.no_movements') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

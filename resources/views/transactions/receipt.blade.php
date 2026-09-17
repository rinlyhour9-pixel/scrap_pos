@extends('layouts.app') @section('content')
    <div class="receipt card p-4">
        <div class="text-center">
            <h3>RIN LY HOUR SCRAP</h3>
            <p>{{ __('pos.receipt_business') }}</p>
            <h5>{{ $type === 'Purchase' ? __('pos.purchase_receipt') : __('pos.sale_receipt') }}</h5>
        </div>
        <hr>
        <div class="d-flex justify-content-between"><span>{{ __('pos.number') }}៖
                <b>{{ $record->number }}</b></span><span>{{ ($type === 'Purchase' ? $record->purchased_at : $record->sold_at)->format('d/m/Y H:i') }}</span>
        </div>
        <p>{{ __('pos.customer') }}៖ {{ $record->customer->name ?? __('pos.walk_in') }}</p>
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('pos.material') }}</th>
                    <th>{{ __('pos.weight') }}</th>
                    <th>{{ __('pos.price') }}</th>
                    <th class="text-end">{{ __('pos.amount') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($record->items as $i)
                    <tr>
                        <td>{{ app()->getLocale() === 'km' && $i->material->khmer_name ? $i->material->khmer_name : $i->material->name }}</td>
                        <td>{{ $type === 'Purchase' ? $i->net_weight : $i->weight }}</td>
                        <td>{{ \App\Support\Currency::format($i->unit_price) }}</td>
                        <td class="text-end">{{ \App\Support\Currency::format($i->subtotal) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="receipt-total">
            <div>{{ __('pos.subtotal') }} <b>{{ \App\Support\Currency::format($record->subtotal) }}</b></div>
            <div>{{ __('pos.discount') }} <b>{{ \App\Support\Currency::format($record->discount) }}</b></div>
            <div class="grand">{{ __('pos.grand_total') }} <b>{{ \App\Support\Currency::format($record->grand_total) }}</b></div>
            <div>{{ __('pos.paid') }} <b>{{ \App\Support\Currency::format($record->amount_paid) }}</b></div>
            <div>{{ __('pos.balance') }} <b>{{ \App\Support\Currency::format($record->balance) }}</b></div>
        </div><button class="btn btn-dark mt-4 no-print" onclick="window.print()">{{ __('pos.print_receipt') }}</button>
    </div>
@endsection

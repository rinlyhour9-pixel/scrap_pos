@extends('layouts.app') @section('content')
    @include('pos.screen', [
        'mode' => 'sell',
        'title' => 'Sell Scrap',
        'action' => route('pos.sale'),
        'materials' => $materials,
        'customers' => $customers,
    ])
@endsection

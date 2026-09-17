@extends('layouts.app') @section('content')
    @include('pos.screen', [
        'mode' => 'buy',
        'title' => 'Buy Scrap',
        'action' => route('pos.purchase'),
        'materials' => $materials,
        'customers' => $customers,
    ])
@endsection

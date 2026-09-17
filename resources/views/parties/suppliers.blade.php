@extends('layouts.app') @section('content') @include('parties.list',['title'=>__('pos.suppliers'),'route'=>'suppliers.store','types'=>null]) @endsection

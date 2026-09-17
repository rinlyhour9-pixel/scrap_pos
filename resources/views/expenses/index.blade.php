@extends('layouts.app') @section('content')
    <div class="page-title">
        <div>
            <h1>Expenses</h1>
            <p>Daily business costs</p>
        </div><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add">+ Add expense</button>
    </div>
    <div class="card p-3 table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Number</th>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Payment</th>
                    <th class="text-end">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($expenses as $e)
                    <tr>
                        <td>{{ $e->number }}</td>
                        <td>{{ $e->expense_date }}</td>
                        <td>{{ $e->category->name }}</td>
                        <td>{{ $e->description }}</td>
                        <td>{{ $e->payment_method }}</td>
                        <td class="text-end">{{ \App\Support\Currency::format($e->amount) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>{{ $expenses->links() }}
    </div>
    <div class="modal fade" id="add">
        <div class="modal-dialog">
            <form method="post" action="{{ route('expenses.store') }}" class="modal-content">@csrf<div
                    class="modal-header">
                    <h5>Record expense</h5>
                </div>
                <div class="modal-body"><select class="form-select mb-2" name="expense_category_id">
                        @foreach ($categories as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select><input class="form-control mb-2" type="number" step="{{ \App\Support\Currency::usesRiel() ? '1' : '.01' }}" name="amount"
                        placeholder="Amount" required><input class="form-control mb-2" type="date" name="expense_date"
                        value="{{ today()->format('Y-m-d') }}" required><select class="form-select mb-2"
                        name="payment_method">
                        <option>Cash</option>
                        <option>ABA</option>
                        <option>ACLEDA</option>
                        <option>Wing</option>
                        <option>Bank Transfer</option>
                    </select>
                    <textarea class="form-control" name="description" placeholder="Description"></textarea>
                </div>
                <div class="modal-footer"><button class="btn btn-primary">Save expense</button></div>
            </form>
        </div>
    </div>
@endsection

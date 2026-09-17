<form class="card p-3 mb-4 no-print" method="get">
    <div class="row g-2 align-items-end">
        <div class="col-sm-4"><label class="form-label">{{ __('reports.start_date') }}</label><input class="form-control" type="date" name="start_date" value="{{ $start->toDateString() }}"></div>
        <div class="col-sm-4"><label class="form-label">{{ __('reports.end_date') }}</label><input class="form-control" type="date" name="end_date" value="{{ $end->toDateString() }}"></div>
        <div class="col-sm-4 d-flex gap-2"><button class="btn btn-primary">{{ __('reports.apply') }}</button><button class="btn btn-outline-primary" type="button" onclick="window.print()">{{ __('reports.print') }}</button></div>
    </div>
</form>

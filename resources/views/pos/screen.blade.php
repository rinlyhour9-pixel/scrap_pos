@php($currency = ['symbol' => \App\Support\Currency::symbol(), 'decimals' => \App\Support\Currency::decimalPlaces(), 'step' => \App\Support\Currency::usesRiel() ? '1' : '.01'])
<div class="page-title">
    <div>
        <h1>{{ $mode === 'buy' ? __('pos.buy_scrap') : __('pos.sell_scrap') }}</h1>
        <p>{{ $mode === 'buy' ? __('pos.fast_purchase_entry') : __('pos.stock_aware_selling') }}</p>
    </div><span class="badge text-bg-light">{{ now()->format('d M Y, H:i') }}</span>
</div>
<form method="post" action="{{ $action }}" id="posForm">@csrf<div class="pos-layout">
        <div class="catalog"><input class="form-control mb-3" id="materialSearch"
                placeholder="{{ __('pos.search_material') }}">
            <div class="material-grid">
                @foreach ($materials as $m)
                    @php($materialData = ['id' => $m->id, 'name' => app()->getLocale() === 'km' && $m->khmer_name ? $m->khmer_name : $m->name, 'khmer' => $m->khmer_name, 'price' => \App\Support\Currency::display($mode === 'buy' ? $m->purchase_price : $m->selling_price), 'stock' => $m->current_stock, 'unit' => $m->unit->symbol])
                    <button type="button" class="material-card" data-material='@json($materialData)'><span
                            class="material-icon">♻</span><strong>{{ app()->getLocale() === 'km' && $m->khmer_name ? $m->khmer_name : $m->name }}</strong><small>{{ app()->getLocale() === 'km' ? $m->name : $m->khmer_name }}</small><b>{{ \App\Support\Currency::format($mode === 'buy' ? $m->purchase_price : $m->selling_price) }}
                            / {{ $m->unit->symbol }}</b>
                        @if ($mode === 'sell')
                            <em>{{ __('pos.stock') }}: {{ $m->current_stock }} {{ $m->unit->symbol }}</em>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>
        <aside class="cart">
            <h5>{{ __('pos.transaction_cart') }}</h5>
            <div id="cartItems">
                <p class="empty">{{ __('pos.select_material') }}</p>
            </div>
            <div class="totals">
                <div><span>{{ __('pos.subtotal') }}</span><b id="subtotal">{{ $currency['symbol'] }}0</b></div>
                <div><span>{{ __('pos.discount') }}</span><input name="discount" type="number" min="0"
                        value="0" step="{{ $currency['step'] }}"></div>
                <div><span>{{ __('pos.other_cost') }}</span><input name="other_cost" type="number" min="0"
                        value="0" step="{{ $currency['step'] }}"></div>
                <div class="grand"><span>{{ __('pos.grand_total') }}</span><b id="total">{{ $currency['symbol'] }}0</b></div>
            </div><label>{{ __('pos.customer_buyer') }}</label><select name="customer_id" class="form-select mb-2">
                <option value="">{{ __('pos.walk_in') }}</option>
                @foreach ($customers as $c)
                    <option value="{{ $c->id }}">{{ $c->name }} · {{ $c->phone }}</option>
                @endforeach
            </select>
            <div class="row g-2">
                <div class="col-6"><label>{{ __('pos.paid_amount') }}</label><input name="amount_paid" value="0"
                        min="0" step="{{ $currency['step'] }}" type="number" class="form-control"></div>
                <div class="col-6"><label>{{ __('pos.payment_method') }}</label><select name="payment_method"
                        class="form-select">
                        <option>{{ __('pos.cash') }}</option>
                        <option>ABA</option>
                        <option>ACLEDA</option>
                        <option>Wing</option>
                        <option>Bank Transfer</option>
                        <option>{{ __('pos.credit') }}</option>
                    </select></div>
            </div><button
                class="btn btn-primary w-100 mt-3">{{ $mode === 'buy' ? __('pos.save_purchase') : __('pos.save_sale') }}</button>
        </aside>
    </div>
</form>
@push('scripts')
    <script>
        const mode = '{{ $mode }}',
            currency = @json($currency),
            cart = {},
            box = document.querySelector('#cartItems'),
            labels = {
                gross: '{{ __('pos.gross') }}',
                tare: '{{ __('pos.tare') }}',
                weight: '{{ __('pos.weight') }}',
                available: '{{ __('pos.available') }}',
                empty: '{{ __('pos.select_material') }}'
            };

        const formatMoney = amount => currency.symbol + Number(amount || 0).toLocaleString('en-US', {
            minimumFractionDigits: currency.decimals,
            maximumFractionDigits: currency.decimals
        });

        function render() {
            let sub = 0,
                html = '';
            Object.values(cart).forEach((x, i) => {
                let qty = mode === 'buy' ? Math.max(0, (+x.gross || 0) - (+x.tare || 0)) : (+x.weight || 0),
                    amount = qty * x.price;
                sub += amount;
                html +=
                    `<div class="cart-line"><button type="button" onclick="removeItem(${x.id})">×</button><strong>${x.name}</strong><small>${x.unit}${mode==='sell'?` · ${labels.available} ${x.stock}`:''}</small>${mode==='buy'?`<div><input name="items[${i}][gross_weight]" class="gross" data-id="${x.id}" type="number" step=".001" value="${x.gross}" placeholder="${labels.gross}"><input name="items[${i}][tare_weight]" class="tare" data-id="${x.id}" type="number" step=".001" value="${x.tare}" placeholder="${labels.tare}"></div>`:`<input name="items[${i}][weight]" class="weight" data-id="${x.id}" type="number" max="${x.stock}" step=".001" value="${x.weight}" placeholder="${labels.weight}">`}<input type="hidden" name="items[${i}][material_id]" value="${x.id}"><input name="items[${i}][unit_price]" class="price" data-id="${x.id}" type="number" step="${currency.step}" value="${x.price}"><b>${formatMoney(amount)}</b></div>`
            });
            box.innerHTML = html || `<p class="empty">${labels.empty}</p>`;
            document.querySelector('#subtotal').textContent = formatMoney(sub);
            let d = +document.querySelector('[name=discount]').value || 0,
                o = +document.querySelector('[name=other_cost]').value || 0;
            document.querySelector('#total').textContent = formatMoney(sub - d + o)
        }
        function updateLiveTotals() {
            let subtotal = 0;
            Object.values(cart).forEach(item => {
                const quantity = mode === 'buy'
                    ? Math.max(0, (+item.gross || 0) - (+item.tare || 0))
                    : (+item.weight || 0);
                subtotal += quantity * (+item.price || 0);
            });
            document.querySelector('#subtotal').textContent = formatMoney(subtotal);
            const discount = +document.querySelector('[name=discount]').value || 0;
            const otherCost = +document.querySelector('[name=other_cost]').value || 0;
            document.querySelector('#total').textContent = formatMoney(subtotal - discount + otherCost);
        }

        document.querySelectorAll('.material-card').forEach(b => b.onclick = () => {
            let x = JSON.parse(b.dataset.material);
            if (cart[x.id]) return;
            cart[x.id] = {
                ...x,
                gross: '',
                tare: 0,
                weight: ''
            };
            render()
        });

        function removeItem(id) {
            delete cart[id];
            render()
        }
        document.addEventListener('input', e => {
            let id = e.target.dataset.id;
            if (id && cart[id]) {
                if (e.target.classList.contains('gross')) cart[id].gross = e.target.value;
                if (e.target.classList.contains('tare')) cart[id].tare = e.target.value;
                if (e.target.classList.contains('weight')) cart[id].weight = e.target.value;
                if (e.target.classList.contains('price')) cart[id].price = e.target.value;
                const item = cart[id];
                const quantity = mode === 'buy'
                    ? Math.max(0, (+item.gross || 0) - (+item.tare || 0))
                    : (+item.weight || 0);
                e.target.closest('.cart-line').querySelector('b').textContent = formatMoney(quantity * (+item.price || 0));
                updateLiveTotals()
            }
            if (e.target.name === 'discount' || e.target.name === 'other_cost') updateLiveTotals()
        });
        document.querySelector('#materialSearch').oninput = e => document.querySelectorAll('.material-card').forEach(x => x
            .hidden = !x.innerText.toLowerCase().includes(e.target.value.toLowerCase()));
    </script>
@endpush

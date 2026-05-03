<fieldset class="flex flex-col gap-y-4 bg-white border border-slate-400 shadow-lg p-3 rounded-lg mb-8">
    <legend class="font-medium text-md">Select additional meals</legend>

    @foreach ($additional_meals as $meal)
        <div class="meal-row grid grid-cols-4 items-center gap-2 text-sm bg-gray-100 text-slate-700 border border-dotted border-gray-400 rounded-lg py-2 px-3"
            data-meal-id="{{ $meal->id }}"
            data-unit-price="{{ number_format((float) $meal->unit_price, 2, '.', '') }}"
            data-max-qty="{{ (int) $meal->max_quantity }}">
            <div class="col-span-2">
                <div class="font-medium">{{ $meal->name }}</div>
                <div class="text-xs text-slate-500">BHD {{ number_format($meal->unit_price, 2) }}</div>
            </div>

            <div class="col-span-1 text-sm text-right">
                <span class="text-slate-600">Max: {{ $meal->max_quantity }}</span>
            </div>

            <div class="col-span-1">
                <input type="number" min="0" max="{{ $meal->max_quantity }}" step="1" value="0"
                    class="qty-input w-full focus:outline-0 bg-white border border-slate-300 rounded-md py-1 px-2 font-bold"
                    aria-label="Quantity for {{ $meal->name }}" />
            </div>

            <div class="col-span-4 text-right text-sm mt-1 pr-2">
                <span class="text-slate-600">Line total: </span>
                <span class="line-total font-bold">BHD 0.00</span>
            </div>
        </div>
    @endforeach

    <div class="grid grid-cols-3 text-sm bg-gray-100 text-slate-700 border border-dotted border-gray-300 rounded-lg py-2.5 px-3 mt-3">
        <label class="col-span-1 font-medium">Additional Price</label>
        <span id="additional-total" class="col-span-2 font-bold text-right">BHD 0.00</span>
    </div>
</fieldset>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const mealRows = document.querySelectorAll('.meal-row');
    const totalEl = document.getElementById('additional-total');

    function parsePrice(value) {
        return Number(value) || 0;
    }

    function formatCurrency(value) {
        return value.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function updateTotals() {
        let grandTotal = 0;
        mealRows.forEach(row => {
            const unitPrice = parsePrice(row.dataset.unitPrice);
            const maxQty = parseInt(row.dataset.maxQty, 10) || 0;
            const qtyInput = row.querySelector('.qty-input');
            if (!qtyInput) return;

            let qty = parseInt(qtyInput.value, 10) || 0;

            // clamp quantity
            if (qty < 0) qty = 0;
            if (maxQty > 0 && qty > maxQty) qty = maxQty;
            qtyInput.value = qty;

            const lineTotal = unitPrice * qty;
            grandTotal += lineTotal;

            const lineTotalEl = row.querySelector('.line-total');
            if (lineTotalEl) {
                lineTotalEl.textContent = 'BHD ' + formatCurrency(lineTotal);
            }
        });

        if (totalEl) {
            totalEl.textContent = 'BHDr ' + formatCurrency(grandTotal);
        }
    }

    // attach listeners safely
    mealRows.forEach(row => {
        const input = row.querySelector('.qty-input');
        if (!input) return;

        let debounceTimer = null;
        input.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(updateTotals, 150);
        });
        input.addEventListener('change', updateTotals);
        input.addEventListener('blur', updateTotals);
    });

    // initial calculation
    updateTotals();
});
</script>

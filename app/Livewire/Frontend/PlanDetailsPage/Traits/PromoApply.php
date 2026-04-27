<?php
namespace App\Livewire\Frontend\PlanDetailsPage\Traits;

use App\Models\PromoCode;
use Illuminate\Support\Facades\RateLimiter;

trait PromoApply
{
    protected $rules = [
        'promo_code' => 'required|string',
    ];

    public function apply()
    {
        // normalize input and check empty first
        $code = trim((string) ($this->promo_code ?? ''));

        if ($code === '') {
            $this->addError('promo_code', 'Please enter a promo code.');
            return;
        }

        // normalize and prepare key
        $userPart = auth()->id() ? 'user:' . auth()->id() : 'ip:' . request()->ip();
        $codePart = strtolower(trim((string) ($this->promo_code ?? '')));
        $key = "promo-apply:{$userPart}:{$codePart}";

        $maxAttempts = 6;
        $decaySeconds = 120;

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            \Log::warning('Promo apply rate limited', [
                'key' => $key,
                'ip' => request()->ip(),
                'user_id' => auth()->id(),
                'promo_code' => $codePart,
            ]);
            $this->dispatch('toast', message: "Too many attempts. Try again in {$seconds} seconds.", type: 'error');
            $this->addError('promo_code', 'Too many attempts. Please wait and try again.');
            return;
        }

        // register attempt
        RateLimiter::hit($key, $decaySeconds);

        // validate input (only promo_code)
        $this->resetValidation('promo_code');
        try {
            $this->validateOnly('promo_code');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // validation errors will be shown by Livewire; do not clear rate limiter here
            return;
        }

        $promo = PromoCode::whereRaw('LOWER(promo_code) = ?', [$code])->first();

        if (!$promo) {
            $this->addError('promo_code', 'Invalid promo code.');
            $this->promoItem = null;
            $this->discountAmount = 0;
            $this->finalPrice = $this->originalPrice;
            return;
        }

        // expiry / status checks
        if (method_exists($promo, 'isExpired') && $promo->isExpired()) {
            $this->addError('promo_code', 'This promo code has expired.');
            $this->promoItem = null;
            $this->discountAmount = 0;
            $this->finalPrice = $this->originalPrice;
            return;
        }

        if (isset($promo->status) && $promo->status !== 'active') {
            $this->addError('promo_code', 'This promo code is not active.');
            $this->promoItem = null;
            $this->discountAmount = 0;
            $this->finalPrice = $this->originalPrice;
            return;
        }

        // calculate discount using server-side original price (ensure originalPrice is server-derived)
        $this->discountAmount = $promo->calculateDiscount((float) $this->originalPrice);
        $this->finalPrice = max(0, (float) $this->originalPrice - $this->discountAmount);

        // store only safe promo summary (avoid storing full model)
        $this->promoItem = [
            'id' => $promo->id,
            'code' => $promo->promo_code,
            'type' => $promo->type,
            'value' => (string) $promo->value,
        ];

        \Log::info('Promo applied (preview)', [
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'promo_id' => $promo->id,
            'promo_code' => $promo->promo_code,
        ]);

        $this->dispatch('toast', message: 'Promo applied successfully.', type: 'success');

        // clear attempts for this key on success
        RateLimiter::clear($key);
    }

    public function remove()
    {
        $this->promo_code = '';
        $this->promoItem = null;
        $this->discountAmount = 0;
        $this->finalPrice = $this->originalPrice;
        $this->dispatch('toast', message: 'Promo removed.', type: 'warning');
        $this->resetValidation();
    }
}

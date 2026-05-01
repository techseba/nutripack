<main class="max-w-md mx-auto min-h-screen bg-lemon text-slate-900 pt-2 pb-16 px-4 overflow-hidden">

    {{-- Plan details header --}}
    <div class="flex justify-between items-center">
        <div class="flex items-center gap-x-2">
            <a href="{{ route('home') }}" wire:navigate>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hover:text-app-yellow" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="font-bold text-slate-700 my-4 text-xl">Plan Details</h1>
        </div>

        {{-- clock --}}
        <x-widget.clock />

    </div>

    <form wire:submit.prevent="submit">

        {{-- selected plan --}}
        @include('frontend.plan-details-page.sections.selected-plan')

        {{-- additional meals --}}
        @include('frontend.plan-details-page.sections.additional-meals')

        {{-- allergen  ingredients --}}
        @include('frontend.plan-details-page.sections.allergen-ingredients')

        {{-- delivery information --}}
        @include('frontend.plan-details-page.sections.delivery-information')

        {{-- delivery address --}}
        @include('frontend.plan-details-page.sections.delivery-address')

        {{-- summery --}}
        @include('livewire.frontend.plan-details-page.sections.summery')

        {{-- <pre>{{ json_encode($selectedPlan, JSON_PRETTY_PRINT) }}</pre> --}}
    </form>
</main>

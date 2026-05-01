<fieldset
    class="flex flex-col gap-y-4 bg-white border border-slate-400 ring-10 ring-white shadow-lg shadow-gray-400 p-2 rounded-lg mb-8">
    <legend class="font-medium text-md">Delivery Address</legend>

    <div class="space-y-3">
        <input type="tel" placeholder="Enter your phone" wire:model.prevent="phone"
            class="bg-gray-100 py-1.5 px-2 rounded-md w-full text-sm font-medium focus:outline-0 border border-dotted border-gray-400">
        @error('phone')
            <div class="text-red-600 text-sm">{{ $message }}</div>
        @enderror

        <input type="number" placeholder="House number" wire:model.prevent="house"
            class="bg-gray-100 py-1.5 px-2 rounded-md w-full text-sm font-medium focus:outline-0 border border-dotted border-gray-400">
        @error('house')
            <div class="text-red-600 text-sm">{{ $message }}</div>
        @enderror

        <input type="number" placeholder="Road number" wire:model.prevent="road"
            class="bg-gray-100 py-1.5 px-2 rounded-md w-full text-sm font-medium focus:outline-0 border border-dotted border-gray-400">
        @error('road')
            <div class="text-red-600 text-sm">{{ $message }}</div>
        @enderror

        <input type="text" placeholder="Block" wire:model.prevent="block"
            class="bg-gray-100 py-1.5 px-2 rounded-md w-full text-sm font-medium focus:outline-0 border border-dotted border-gray-400">
        @error('block')
            <div class="text-red-600 text-sm">{{ $message }}</div>
        @enderror

        <input type="text" placeholder="Area" wire:model.prevent="area"
            class="bg-gray-100 py-1.5 px-2 rounded-md w-full text-sm font-medium focus:outline-0 border border-dotted border-gray-400">
        @error('area')
            <div class="text-red-600 text-sm">{{ $message }}</div>
        @enderror

        <textarea cols="30" rows="5" placeholder="Additional direction" wire:model.prevent="additional_direction"
            class="bg-gray-100 py-1.5 px-2 rounded-md w-full text-sm font-medium focus:outline-0 border border-dotted border-gray-400"></textarea>
        @error('additional_direction')
            <div class="text-red-600 text-sm">{{ $message }}</div>
        @enderror

    </div>
</fieldset>

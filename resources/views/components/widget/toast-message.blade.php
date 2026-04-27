<div x-data="{
    show: false,
    message: '',
    type: 'success',
    icon(type) {
        switch (type) {
            case 'success':
                return '✅';
            case 'error':
                return '❌';
            case 'warning':
                return '⚠️';
            default:
                return '💬';
        }
    }
}"
    x-on:toast.window="
        message = $event.detail.message;
        type = $event.detail.type ?? 'info';
        show = true;
        setTimeout(() => show = false, 3000);
    "
    x-show="show" x-transition:enter="transform ease-out duration-300 transition"
    x-transition:enter-start="translate-y-[-30px] opacity-0 scale-95"
    x-transition:enter-end="translate-y-0 opacity-100 scale-100"
    x-transition:leave="transform ease-in duration-300 transition"
    x-transition:leave-start="translate-y-0 opacity-100 scale-100"
    x-transition:leave-end="translate-y-[-20px] opacity-0 scale-95"
    class="fixed top-10 left-1/2 -translate-x-1/2 z-90 w-full max-w-sm" x-cloak>
    <div class="flex items-center gap-3 px-4 py-3 rounded-xl shadow-2xl border border-zinc-700/50 backdrop-blur-md"
        :class="{
            'bg-emerald-500/10 text-emerald-400 border-emerald-600/30': type === 'success',
            'bg-red-500/10 text-red-400 border-red-600/30': type === 'error',
            'bg-amber-500/10 text-amber-400 border-amber-600/30': type === 'warning',
            'bg-blue-500/10 text-blue-400 border-blue-600/30': type === 'info'
        }">
        <!-- Icon -->
        <div class="text-lg" x-text="icon(type)"></div>

        <!-- Message -->
        <p class="font-medium text-sm" x-text="message"></p>
    </div>
</div>

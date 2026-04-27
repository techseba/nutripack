<div x-data="{
    toasts: [],
    baseTimeout: 2000, // প্রথম টোস্ট কত মি.সে. দেখাবে
    stagger: 600, // প্রতিটি পরের টোস্ট কত মি.সে. বেশি দেখাবে
    addToast(detail) {
        const id = Date.now().toString(36) + Math.random().toString(36).slice(2, 8);
        // index = বর্তমান টোস্ট সংখ্যা (নতুন টোস্ট যোগ করার আগে)
        const index = this.toasts.length;
        const timeout = (detail.timeout ?? this.baseTimeout) + (index * this.stagger);

        const toast = {
            id,
            message: detail.message ?? '',
            type: detail.type ?? 'info',
            timeout
        };

        this.toasts.push(toast);

        // নির্দিষ্ট timeout পরে সেই টোস্ট সরানো হবে
        setTimeout(() => {
            this.toasts = this.toasts.filter(t => t.id !== id);
        }, timeout);
    },
    removeToast(id) {
        this.toasts = this.toasts.filter(t => t.id !== id);
    },
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
}" x-on:toast.window="addToast($event.detail)"
    class="fixed top-10 left-1/2 -translate-x-1/2 z-50 w-full max-w-sm pointer-events-none" x-cloak>
    <template x-for="toast in toasts" :key="toast.id">
        <div class="mb-3 pointer-events-auto" x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-[-10px] opacity-0 scale-95"
            x-transition:enter-end="translate-y-0 opacity-100 scale-100"
            x-transition:leave="transform ease-in duration-300 transition"
            x-transition:leave-start="translate-y-0 opacity-100 scale-100"
            x-transition:leave-end="translate-y-[-10px] opacity-0 scale-95">
            <div class="flex items-center gap-3 px-4 py-3 rounded-xl shadow-2xl border backdrop-blur-md"
                :class="{
                    'bg-emerald-500/10 text-emerald-500 border-emerald-500/50': toast.type === 'success',
                    'bg-red-500/10 text-red-500 border-red-500/50': toast.type === 'error',
                    'bg-amber-500/10 text-amber-400 border-amber-600/30': toast.type === 'warning',
                    'bg-blue-500/10 text-blue-400 border-blue-600/30': toast.type === 'info'
                }">
                <div class="text-lg" x-text="icon(toast.type)"></div>
                <p class="font-medium text-sm" x-text="toast.message"></p>
                <button type="button" class="ml-auto text-sm opacity-70 hover:opacity-100"
                    x-on:click="removeToast(toast.id)" aria-label="Close toast">✕</button>
            </div>
        </div>
    </template>
</div>

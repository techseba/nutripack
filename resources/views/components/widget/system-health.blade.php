<div class="hidden 2xl:block fixed bottom-7 right-7">
    <style>
        [x-cloak] {
            display: none !important;
        }

        .glass {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid white;
        }
    </style>

    <div class="w-80 p-4 glass rounded-2xl p-4">
        <div class="flex items-center justify-between mb-3">
            <span class="text-[10px] font-bold text-slate-800 uppercase tracking-widest">System Health</span>
            <span class="w-2 h-2 bg-success rounded-full"></span>
        </div>

        <div class="space-y-2" x-data="{
            latency: '—',
            width: 0,
            maxLatency: 500,
            async measure() {
                const start = performance.now();
                await fetch('/ping', { cache: 'no-store' });
                const end = performance.now();
                const rtt = Math.round(end - start); // number in ms

                this.latency = rtt + 'ms';

                // Map latency -> width (lower latency => higher width)
                let pct = (1 - (rtt / this.maxLatency)) * 100;
                pct = Math.max(5, Math.min(100, Math.round(pct))); // clamp 5..100
                this.width = pct;
            }
        }" x-init="measure();
        setInterval(() => measure(), 5000)">

            <div class="flex justify-between text-[10px]">
                <span class="text-slate-600">API Latency</span>
                <div>
                    <span class="font-bold text-slate-800" x-text="latency"></span>
                </div>
            </div>

            <div class="w-full bg-slate-200 rounded-full h-1 overflow-hidden">
                <div :style="`width: ${width}%`"
                    :class="(width > 60) ? 'bg-green-400' : (width > 30 ? 'bg-yellow-400' : 'bg-red-500')"
                    class="h-1 rounded-full transition-all duration-300 ease-out"></div>
            </div>
        </div>

    </div>
</div>

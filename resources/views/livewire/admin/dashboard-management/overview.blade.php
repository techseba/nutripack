<div class="bg-background text-slate-900 antialiased min-h-screen flex" x-data="planManager()">

    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
        }

        .sidebar-item-active {
            background: #F1F5F9;
            color: #0F172A;
            border-right: 3px solid #3B82F6;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #E2E8F0;
            border-radius: 10px;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #3B82F6;
            ring: 2px;
            ring-color: rgba(59, 130, 246, 0.2);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }
    </style>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        <!-- Scrollable Content -->
        <div class="flex-1 overflow-y-auto custom-scrollbar">

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">

                {{-- Subscribers --}}
                <div
                    class="bg-white p-5 rounded-2xl border border-slate-200 shadow-md card-hover hover:bg-slate-200 hover:border-slate-300">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2 rounded-lg">
                            <i class="w-5 h-5 text-blue-500">
                                <x-icons.users />
                            </i>
                        </div>
                        <a href="{{ route('admin.subscribers') }}" wire:navigate
                            class="text-xs font-medium px-2 py-0.5 rounded-full bg-slate-50 text-emerald-500"><x-icons.link /></a>
                    </div>

                    <div wire:poll.5000ms="refreshCount">
                        <p class="text-2xl font-bold text-slate-900">{{ $subscriberCount ?? '—' }}</p>
                    </div>

                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wider mt-1">subscribers</p>
                </div>

                {{-- Active Plans --}}
                <div
                    class="bg-white p-5 rounded-2xl border border-slate-200 shadow-md card-hover hover:bg-slate-200 hover:border-slate-300">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2 rounded-lg">
                            <i class="w-5 h-5 text-blue-500">
                                <x-icons.plan />
                            </i>
                        </div>
                        <a href="{{ route('admin.subscribers') }}" wire:navigate
                            class="text-xs font-medium px-2 py-0.5 rounded-full bg-slate-50 text-emerald-500"><x-icons.link /></a>
                    </div>
                    <p class="text-2xl font-bold text-slate-900">{{ $subscriberCount }}</p>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wider mt-1">Active Plans</p>
                </div>

                {{-- Total Meals --}}
                <div
                    class="bg-white p-5 rounded-2xl border border-slate-200 shadow-md card-hover hover:bg-slate-200 hover:border-slate-300">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2 rounded-lg">
                            <i class="w-5 h-5 text-blue-500">
                                <x-icons.meal />
                            </i>
                        </div>
                        <a href="{{ route('admin.meals') }}" wire:navigate
                            class="text-xs font-medium px-2 py-0.5 rounded-full bg-slate-50 text-emerald-500"><x-icons.link /></a>
                    </div>
                    <div wire:poll.5000ms="refreshCount">
                        <p class="text-2xl font-bold text-slate-900">{{ $mealCount ?? '—' }}</p>
                    </div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wider mt-1">Total Meals</p>
                </div>

            </div>

            <!-- Main Management Layout -->
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">

                <!-- Left Side: Plan List -->
                {{-- <div class="xl:col-span-7 space-y-6">
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <div
                            class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <h3 class="font-bold text-lg">Active Subscription Plans</h3>
                            <div class="flex items-center gap-2">
                                <select class="bg-slate-50 border-none text-sm rounded-lg px-3 py-2 focus:ring-0">
                                    <option>All Status</option>
                                    <option>Active</option>
                                    <option>Draft</option>
                                </select>
                                <button class="p-2 bg-slate-50 rounded-lg hover:bg-slate-100">
                                    <i data-lucide="filter" class="w-4 h-4 text-slate-500"></i>
                                </button>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-slate-50/50 border-b border-slate-100">
                                    <tr>
                                        <th
                                            class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                            Plan Details</th>
                                        <th
                                            class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                            Composition</th>
                                        <th
                                            class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <template x-for="p in existingPlans" :key="p.id">
                                        <tr class="hover:bg-slate-50/50 transition-colors group">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400">
                                                        <i data-lucide="box" class="w-5 h-5"></i>
                                                    </div>
                                                    <div>
                                                        <div class="flex items-center gap-2">
                                                            <span class="font-bold text-slate-900"
                                                                x-text="p.name"></span>
                                                            <template x-if="p.recommended">
                                                                <span
                                                                    class="bg-amber-50 text-amber-600 text-[10px] font-bold px-1.5 py-0.5 rounded uppercase tracking-tighter border border-amber-100">REC</span>
                                                            </template>
                                                        </div>
                                                        <p class="text-xs text-slate-500" x-text="p.slug"></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex flex-wrap gap-1">
                                                    <template x-for="m in p.meals" :key="m">
                                                        <span
                                                            class="px-2 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-bold rounded uppercase"
                                                            x-text="m"></span>
                                                    </template>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span
                                                    :class="`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${p.status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600'}`">
                                                    <span
                                                        :class="`w-1.5 h-1.5 rounded-full mr-1.5 ${p.status === 'active' ? 'bg-emerald-500' : 'bg-slate-400'}`"></span>
                                                    <span
                                                        x-text="p.status.charAt(0).toUpperCase() + p.status.slice(1)"></span>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div
                                                    class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <button
                                                        class="p-2 text-slate-400 hover:text-primary hover:bg-blue-50 rounded-lg transition-all">
                                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                                    </button>
                                                    <button
                                                        class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-all">
                                                        <i data-lucide="copy" class="w-4 h-4"></i>
                                                    </button>
                                                    <button
                                                        class="p-2 text-slate-400 hover:text-danger hover:bg-red-50 rounded-lg transition-all">
                                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <div class="p-4 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
                            <p class="text-xs text-slate-500">Showing 5 of 24 plans</p>
                            <div class="flex items-center gap-1">
                                <button
                                    class="p-1.5 rounded-lg border border-slate-200 bg-white text-slate-400 hover:text-slate-600 disabled:opacity-50"
                                    disabled>
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                </button>
                                <button class="w-8 h-8 rounded-lg bg-primary text-white text-xs font-bold">1</button>
                                <button
                                    class="w-8 h-8 rounded-lg text-slate-600 text-xs font-bold hover:bg-slate-100">2</button>
                                <button
                                    class="w-8 h-8 rounded-lg text-slate-600 text-xs font-bold hover:bg-slate-100">3</button>
                                <button
                                    class="p-1.5 rounded-lg border border-slate-200 bg-white text-slate-400 hover:text-slate-600">
                                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Optional Matrix Preview Section -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="font-bold text-lg">Combination Matrix</h3>
                                <p class="text-sm text-slate-500">Visual breakdown of enabled meal combinations.</p>
                            </div>
                            <i data-lucide="grid" class="w-5 h-5 text-slate-400"></i>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            <template x-for="matrix in combinationMatrix" :key="matrix.label">
                                <div
                                    class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 flex flex-col items-center text-center">
                                    <div
                                        :class="`w-8 h-8 rounded-lg flex items-center justify-center mb-2 ${matrix.active ? 'bg-primary/10 text-primary' : 'bg-slate-200 text-slate-400'}`">
                                        <i :data-lucide="matrix.icon" class="w-4 h-4"></i>
                                    </div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest"
                                        x-text="matrix.label"></p>
                                    <p class="text-xs font-semibold mt-1"
                                        x-text="matrix.active ? 'Enabled' : 'Disabled'"></p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div> --}}

                <!-- Right Side: Create/Edit Form & Preview -->
                {{-- <div class="xl:col-span-5 space-y-8" x-show="showForm"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-x-4"
                    x-transition:enter-end="opacity-100 translate-x-0">

                    <!-- Form Card -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden sticky top-24">
                        <div class="border-b border-slate-100">
                            <div class="flex">
                                <button @click="activeTab = 'basic'"
                                    :class="`flex-1 py-4 text-sm font-bold transition-all border-b-2 ${activeTab === 'basic' ? 'border-primary text-primary bg-blue-50/30' : 'border-transparent text-slate-500 hover:text-slate-700'}`">Basic
                                    Info</button>
                                <button @click="activeTab = 'config'"
                                    :class="`flex-1 py-4 text-sm font-bold transition-all border-b-2 ${activeTab === 'config' ? 'border-primary text-primary bg-blue-50/30' : 'border-transparent text-slate-500 hover:text-slate-700'}`">Configuration</button>
                                <button @click="activeTab = 'pricing'"
                                    :class="`flex-1 py-4 text-sm font-bold transition-all border-b-2 ${activeTab === 'pricing' ? 'border-primary text-primary bg-blue-50/30' : 'border-transparent text-slate-500 hover:text-slate-700'}`">Pricing</button>
                            </div>
                        </div>

                        <div class="p-6 max-h-[calc(100vh-350px)] overflow-y-auto custom-scrollbar">

                            <!-- Basic Information -->
                            <div x-show="activeTab === 'basic'" class="space-y-5">
                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Plan
                                        Name</label>
                                    <input type="text" x-model="plan.name"
                                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white transition-all"
                                        placeholder="e.g. Weight Loss Pro">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Slug</label>
                                        <input type="text" x-model="plan.slug"
                                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white transition-all"
                                            placeholder="weight-loss-pro">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Status</label>
                                        <select x-model="plan.status"
                                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white transition-all">
                                            <option value="active">Active</option>
                                            <option value="draft">Draft</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Short
                                        Description</label>
                                    <textarea x-model="plan.description" rows="3"
                                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white transition-all"
                                        placeholder="Briefly describe the plan..."></textarea>
                                </div>
                                <div
                                    class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100">
                                    <div class="flex items-center gap-3">
                                        <div class="p-2 bg-amber-100 text-amber-600 rounded-lg">
                                            <i data-lucide="star" class="w-4 h-4"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold">Recommended Plan</p>
                                            <p class="text-xs text-slate-500">Show 'Recommended' badge on storefront
                                            </p>
                                        </div>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" x-model="plan.recommended" class="sr-only peer">
                                        <div
                                            class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Configuration -->
                            <div x-show="activeTab === 'config'" class="space-y-6">
                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Meal
                                        Composition</label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <button @click="plan.meals.breakfast = !plan.meals.breakfast"
                                            :class="`flex items-center justify-between p-3 rounded-xl border transition-all ${plan.meals.breakfast ? 'bg-blue-50 border-primary/30 text-primary' : 'bg-white border-slate-200 text-slate-500'}`">
                                            <span class="text-sm font-bold">Breakfast</span>
                                            <i :data-lucide="plan.meals.breakfast ? 'check-circle' : 'circle'"
                                                class="w-4 h-4"></i>
                                        </button>
                                        <button @click="plan.meals.lunch = !plan.meals.lunch"
                                            :class="`flex items-center justify-between p-3 rounded-xl border transition-all ${plan.meals.lunch ? 'bg-blue-50 border-primary/30 text-primary' : 'bg-white border-slate-200 text-slate-500'}`">
                                            <span class="text-sm font-bold">Lunch</span>
                                            <i :data-lucide="plan.meals.lunch ? 'check-circle' : 'circle'"
                                                class="w-4 h-4"></i>
                                        </button>
                                        <button @click="plan.meals.dinner = !plan.meals.dinner"
                                            :class="`flex items-center justify-between p-3 rounded-xl border transition-all ${plan.meals.dinner ? 'bg-blue-50 border-primary/30 text-primary' : 'bg-white border-slate-200 text-slate-500'}`">
                                            <span class="text-sm font-bold">Dinner</span>
                                            <i :data-lucide="plan.meals.dinner ? 'check-circle' : 'circle'"
                                                class="w-4 h-4"></i>
                                        </button>
                                        <div
                                            class="flex items-center justify-between p-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-400 cursor-not-allowed">
                                            <span class="text-sm font-bold">Snacks</span>
                                            <i data-lucide="lock" class="w-4 h-4"></i>
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-2 flex items-center gap-1">
                                        <i data-lucide="info" class="w-3 h-3"></i>
                                        Snacks are mandatory and included in all plans by default.
                                    </p>
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Available
                                        Durations</label>
                                    <div class="space-y-3">
                                        <template x-for="(dur, index) in plan.durations" :key="index">
                                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 space-y-3">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-sm font-bold text-slate-900"
                                                        x-text="dur.label"></span>
                                                    <button class="text-danger hover:text-red-600">
                                                        <i data-lucide="x" class="w-4 h-4"></i>
                                                    </button>
                                                </div>
                                                <div class="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <label
                                                            class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Discount
                                                            Type</label>
                                                        <select x-model="dur.discountType"
                                                            class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs">
                                                            <option value="percentage">Percentage (%)</option>
                                                            <option value="fixed">Fixed Amount ($)</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label
                                                            class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Value</label>
                                                        <input type="number" x-model="dur.discountValue"
                                                            class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs">
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                        <button
                                            class="w-full py-2 border-2 border-dashed border-slate-200 rounded-xl text-xs font-bold text-slate-400 hover:border-primary hover:text-primary transition-all">
                                            + Add Duration Option
                                        </button>
                                    </div>
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Delivery
                                        Rules</label>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label
                                                class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Min
                                                Days/Week</label>
                                            <input type="number" x-model="plan.delivery.minDays" min="5"
                                                max="7"
                                                class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Max
                                                Days/Week</label>
                                            <input type="number" x-model="plan.delivery.maxDays" min="5"
                                                max="7"
                                                class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-2 flex items-center gap-1">
                                        <i data-lucide="info" class="w-3 h-3"></i>
                                        Weekly delivery days must be between 5 and 7 days.
                                    </p>
                                </div>
                            </div>

                            <!-- Pricing -->
                            <div x-show="activeTab === 'pricing'" class="space-y-6">
                                <div class="p-5 bg-blue-600 rounded-2xl text-white shadow-lg shadow-blue-200">
                                    <div class="flex items-center justify-between mb-4">
                                        <p class="text-sm font-medium opacity-80">Base Daily Price</p>
                                        <i data-lucide="calculator" class="w-5 h-5 opacity-80"></i>
                                    </div>
                                    <div class="flex items-end gap-2">
                                        <span class="text-4xl font-bold">$</span>
                                        <input type="number" x-model="plan.pricing.basePrice"
                                            class="bg-transparent border-none text-4xl font-bold p-0 w-32 focus:ring-0">
                                    </div>
                                    <p class="text-[10px] mt-4 opacity-70 uppercase tracking-widest font-bold">Computed
                                        before meal adjustments</p>
                                </div>

                                <div class="space-y-4">
                                    <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Meal Price
                                        Adjustments</h4>
                                    <div class="space-y-3">
                                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm">
                                                    <i data-lucide="coffee" class="w-4 h-4 text-slate-400"></i>
                                                </div>
                                                <span class="text-sm font-semibold">Breakfast</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs font-bold text-slate-400">$</span>
                                                <input type="number" x-model="plan.pricing.adjustments.breakfast"
                                                    class="w-16 px-2 py-1 bg-white border border-slate-200 rounded-lg text-xs text-right">
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm">
                                                    <i data-lucide="sun" class="w-4 h-4 text-slate-400"></i>
                                                </div>
                                                <span class="text-sm font-semibold">Lunch</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs font-bold text-slate-400">$</span>
                                                <input type="number" x-model="plan.pricing.adjustments.lunch"
                                                    class="w-16 px-2 py-1 bg-white border border-slate-200 rounded-lg text-xs text-right">
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm">
                                                    <i data-lucide="moon" class="w-4 h-4 text-slate-400"></i>
                                                </div>
                                                <span class="text-sm font-semibold">Dinner</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs font-bold text-slate-400">$</span>
                                                <input type="number" x-model="plan.pricing.adjustments.dinner"
                                                    class="w-16 px-2 py-1 bg-white border border-slate-200 rounded-lg text-xs text-right">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Footer -->
                        <div class="p-6 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                            <button class="text-sm font-bold text-slate-500 hover:text-slate-700">Reset
                                Changes</button>
                            <div class="flex items-center gap-3">
                                <button
                                    class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-700 hover:bg-slate-50">Save
                                    Draft</button>
                                <button
                                    class="px-6 py-2 bg-primary text-white rounded-xl text-sm font-bold hover:bg-blue-600 shadow-lg shadow-primary/20">Publish
                                    Plan</button>
                            </div>
                        </div>
                    </div>

                    <!-- Live Preview Panel -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between px-2">
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Live Storefront
                                Preview</h4>
                            <span class="flex items-center gap-1 text-[10px] font-bold text-success uppercase">
                                <span class="w-1.5 h-1.5 bg-success rounded-full animate-pulse"></span>
                                Live Sync
                            </span>
                        </div>

                        <div
                            class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden p-8 relative">
                            <template x-if="plan.recommended">
                                <div
                                    class="absolute top-0 right-0 bg-amber-400 text-white text-[10px] font-black px-4 py-1 rounded-bl-2xl uppercase tracking-widest">
                                    Recommended</div>
                            </template>

                            <div class="mb-6">
                                <h2 class="text-2xl font-black text-slate-900 leading-tight"
                                    x-text="plan.name || 'Plan Name'"></h2>
                                <p class="text-sm text-slate-500 mt-2 line-clamp-2"
                                    x-text="plan.description || 'Plan description goes here...'"></p>
                            </div>

                            <div class="flex flex-wrap gap-2 mb-8">
                                <template x-if="plan.meals.breakfast">
                                    <span
                                        class="px-3 py-1 bg-slate-100 rounded-full text-[10px] font-bold text-slate-600 uppercase">Breakfast</span>
                                </template>
                                <template x-if="plan.meals.lunch">
                                    <span
                                        class="px-3 py-1 bg-slate-100 rounded-full text-[10px] font-bold text-slate-600 uppercase">Lunch</span>
                                </template>
                                <template x-if="plan.meals.dinner">
                                    <span
                                        class="px-3 py-1 bg-slate-100 rounded-full text-[10px] font-bold text-slate-600 uppercase">Dinner</span>
                                </template>
                                <span
                                    class="px-3 py-1 bg-blue-50 rounded-full text-[10px] font-bold text-primary uppercase">Snacks
                                    Included</span>
                            </div>

                            <div class="space-y-3 mb-8">
                                <template x-for="dur in plan.durations" :key="dur.label">
                                    <div
                                        :class="`flex items-center justify-between p-4 rounded-2xl border transition-all ${dur.recommended ? 'border-primary bg-blue-50/20 ring-1 ring-primary' : 'border-slate-100 bg-slate-50/50'}`">
                                        <div>
                                            <p class="text-sm font-bold text-slate-900" x-text="dur.label"></p>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider"
                                                x-text="dur.days + ' Days Subscription'"></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-lg font-black text-slate-900"
                                                x-text="'$' + calculatePrice(dur)"></p>
                                            <template x-if="dur.discountValue > 0">
                                                <p class="text-[10px] font-bold text-success uppercase"
                                                    x-text="'Save ' + (dur.discountType === 'percentage' ? dur.discountValue + '%' : '$' + dur.discountValue)">
                                                </p>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <button
                                class="w-full py-4 bg-slate-900 text-white rounded-2xl font-bold text-sm hover:bg-slate-800 transition-all shadow-lg shadow-slate-200">
                                Select This Plan
                            </button>
                        </div>
                    </div>

                    <!-- Validation Rules -->
                    <div class="bg-amber-50 border border-amber-100 rounded-2xl p-5 space-y-3">
                        <h5 class="text-xs font-bold text-amber-800 uppercase tracking-wider flex items-center gap-2">
                            <i data-lucide="shield-alert" class="w-4 h-4"></i>
                            Admin Validation Rules
                        </h5>
                        <ul class="space-y-2">
                            <li class="flex items-start gap-2 text-xs text-amber-700">
                                <i data-lucide="check" class="w-3 h-3 mt-0.5 text-amber-500"></i>
                                <span>Snacks are mandatory for every plan and cannot be disabled.</span>
                            </li>
                            <li
                                :class="`flex items-start gap-2 text-xs ${hasMainMeal ? 'text-amber-700' : 'text-danger font-bold'}`">
                                <i :data-lucide="hasMainMeal ? 'check' : 'alert-circle'" class="w-3 h-3 mt-0.5"></i>
                                <span>Minimum 1 main meal (Breakfast, Lunch, or Dinner) required.</span>
                            </li>
                            <li class="flex items-start gap-2 text-xs text-amber-700">
                                <i data-lucide="check" class="w-3 h-3 mt-0.5 text-amber-500"></i>
                                <span>Weekly delivery days must be between 5 and 7 days.</span>
                            </li>
                        </ul>
                    </div>
                </div> --}}

            </div>
        </div>
    </div>

    <!-- Activity Sidebar (Right) -->
    <aside
        class="w-80 bg-white/90 backdrop-blur-xs border border-slate-200 rounded-2xl hidden 2xl:flex flex-col sticky top-0 min-h-160 max-h-180">
        <div class="p-6 glass rounded-t-2xl border-b border-slate-200 shadow-md">
            <h3 class="font-bold text-slate-900">Activity Log</h3>
            <p class="text-xs text-slate-500 mt-1">Recent changes to plans & pricing.</p>
        </div>

        <div x-data="{
            loading: false,
            scrollTimeout: null,
            checkScroll() {
                if (this.loading) return;
                const threshold = 200;
                // check container scroll
                const containerBottom = (this.$el.scrollTop + this.$el.clientHeight) >= (this.$el.scrollHeight - threshold);
                // check window scroll as fallback
                const windowBottom = (window.innerHeight + window.scrollY) >= (document.body.offsetHeight - threshold);
                if (containerBottom || windowBottom) {
                    this.loading = true;
                    $wire.loadMore().then(() => {
                        setTimeout(() => { this.loading = false }, 300);
                    }).catch(() => { this.loading = false });
                }
            }
        }" x-init="const onScroll = () => {
            if (scrollTimeout) clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => checkScroll(), 120);
        };
        // listen both container and window
        $el.addEventListener('scroll', onScroll);
        window.addEventListener('scroll', onScroll);
        $el.__cleanup = () => {
            $el.removeEventListener('scroll', onScroll);
            window.removeEventListener('scroll', onScroll);
        };" wire:poll.visible.5000ms="refreshLatest"
            class="flex-1 overflow-y-auto custom-scrollbar p-4 pb-25 space-y-4">
            {{-- আপনার আগের markup এখানে অপরিবর্তিত --}}
            @forelse ($activities as $activity)
                <div
                    class="relative pt-2 pl-2 rounded-xl shadow-sm pl-6 pb-6 bg-white border-l border-slate-100 last:border-0 last:pb-0">
                    <div class="absolute left-[-5px] top-0 w-2.5 h-2.5 rounded-full bg-green-400 border-2 border-white">
                    </div>
                    <p class="text-xs font-bold text-slate-900">{{ $activity->description }}</p>
                    <p class="text-[10px] text-slate-500 mt-1">{{ $activity->properties['describe'] ?? '-' }}</p>
                    <div class="flex items-center gap-2 mt-2">
                        <img src="{{ asset('assets/admin/user.png') }}" class="w-4 h-4 rounded-full" alt="User">
                        <span
                            class="text-[10px] font-medium text-slate-400">{{ optional($activity->causer)->name ?? 'System' }}
                            • {{ $activity->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            @empty
                <div class="relative pl-6 pb-6 border-l border-slate-100 last:border-0 last:pb-0">
                    <div class="absolute left-[-5px] top-0 w-2.5 h-2.5 rounded-full bg-slate-200 border-2 border-white">
                    </div>
                    <p class="text-xs font-bold text-slate-900">Activity log not found.</p>
                </div>
            @endforelse

            <div class="p-4 text-center">
                <div wire:loading wire:target="loadMore,refreshLatest" class="text-sm text-slate-500">Loading...</div>
                <div x-show="loading" class="text-sm text-slate-500">Loading more...</div>
            </div>
        </div>

        <div class="w-80 p-4">
            <div class="bg-white/90 backdrop-blur-xs border border-slate-300 rounded-2xl p-4">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">System Health</span>
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
                        <span class="text-slate-500">API Latency</span>
                        <div>
                            <span class="font-bold" x-text="latency"></span>
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

    </aside>
</div>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-950">
    <flux:sidebar sticky collapsible="mobile"
        class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.header>
            @can('dashboard.view')
                <x-app-logo :sidebar="true" href="{{ route('admin.dashboard') }}" wire:navigate />
            @else
                <x-app-logo :sidebar="true" href="{{ route('home') }}" wire:navigate />
            @endcan
            <flux:sidebar.collapse class="lg:hidden" />
        </flux:sidebar.header>

        <flux:sidebar.nav>

            <div class="md:hidden">
                {{-- dashboard overview --}}
                <flux:sidebar.item icon="home" :href="route('home')"
                    :current="request()->routeIs('home')" wire:navigate>
                    {{ __('Home') }}
                </flux:sidebar.item>
            </div>

            <flux:sidebar.group :heading="__('Platform')" class="grid">

                @can('dashboard.view')
                    {{-- dashboard overview --}}
                    <flux:sidebar.item icon="overview" :href="route('admin.dashboard')"
                        :current="request()->routeIs('admin.dashboard')" wire:navigate>
                        {{ __('Overview') }}
                    </flux:sidebar.item>
                @endcan

                @can('diet-plan.view')
                    {{-- diet plans --}}
                    <flux:sidebar.item icon="diet-plans" :href="route('admin.diet-plans')"
                        :current="request()->routeIs('admin.diet-plans')" wire:navigate>
                        {{ __('Diet Plans') }}
                    </flux:sidebar.item>
                @endcan

                @can('meal-type.view')
                    {{-- meal type --}}
                    <flux:sidebar.item icon="meal-types" :href="route('admin.meal-types')"
                        :current="request()->routeIs('admin.meal-types')" wire:navigate>
                        {{ __('Meal Types') }}
                    </flux:sidebar.item>
                @endcan

                @can('ingredient.view')
                    {{-- meal type --}}
                    <flux:sidebar.item icon="ingredients" :href="route('admin.ingredients')"
                        :current="request()->routeIs('admin.ingredients')" wire:navigate>
                        {{ __('Ingredients') }}
                    </flux:sidebar.item>
                @endcan

                @can('meal.view')
                    {{-- meal --}}
                    <flux:sidebar.item icon="meals" :href="route('admin.meals')"
                        :current="request()->routeIs('admin.meals')" wire:navigate>
                        {{ __('Meals') }}
                    </flux:sidebar.item>
                @endcan

                @can('meal.view')
                    {{-- day wise meals --}}
                    <flux:sidebar.item icon="meals" :href="route('admin.additional-meals')"
                        :current="request()->routeIs('admin.additional-meals')" wire:navigate>
                        {{ __('Additional Meals') }}
                    </flux:sidebar.item>
                @endcan

                @can('guest-meal.view')
                    {{-- day wise meals --}}
                    <flux:sidebar.item icon="guest-meals" :href="route('admin.guest-meals')"
                        :current="request()->routeIs('admin.guest-meals')" wire:navigate>
                        {{ __('Guest Meals') }}
                    </flux:sidebar.item>
                @endcan

                @can('day-wise-meal.view')
                    {{-- day wise meals --}}
                    <flux:sidebar.item icon="day-wise-meals" :href="route('admin.day-wise-meals')"
                        :current="request()->routeIs('admin.day-wise-meals')" wire:navigate>
                        {{ __('Daywise Meals') }}
                    </flux:sidebar.item>
                @endcan

                @can('meal.view')
                    {{-- day wise meals --}}
                    <flux:sidebar.item icon="plan-categories" :href="route('admin.plan-categories')"
                        :current="request()->routeIs('admin.plan-categories')" wire:navigate>
                        {{ __('Plan Categories') }}
                    </flux:sidebar.item>
                @endcan

                @can('meal.view')
                    {{-- day wise meals --}}
                    <flux:sidebar.item icon="plan" :href="route('admin.plans')"
                        :current="request()->routeIs('admin.plans')" wire:navigate>
                        {{ __('Plans') }}
                    </flux:sidebar.item>
                @endcan

                @can('meal.view')
                    {{-- day wise meals --}}
                    <flux:sidebar.item icon="users" :href="route('admin.subscribers')"
                        :current="request()->routeIs('admin.subscribers')" wire:navigate>
                        {{ __('Subscribers') }}
                    </flux:sidebar.item>
                @endcan

                @can('meal.view')
                    {{-- day wise meals --}}
                    <flux:sidebar.item icon="users" :href="route('admin.subscriber-meals')"
                        :current="request()->routeIs('admin.subscriber-meals')" wire:navigate>
                        {{ __('Subscriber Meals') }}
                    </flux:sidebar.item>
                @endcan

                @can('meal.view')
                    {{-- day wise meals --}}
                    <flux:sidebar.item icon="users" :href="route('admin.subscriber-additional-meals')"
                        :current="request()->routeIs('admin.subscriber-additional-meals')" wire:navigate>
                        {{ __('Subscriber AD Meals') }}
                    </flux:sidebar.item>
                @endcan

                @can('meal.view')
                    {{-- day wise meals --}}
                    <flux:sidebar.item icon="promo-codes" :href="route('admin.promo-codes')"
                        :current="request()->routeIs('admin.promo-codes')" wire:navigate>
                        {{ __('Promo Codes') }}
                    </flux:sidebar.item>
                @endcan

                @can('meal.view')
                    {{-- day wise meals --}}
                    <flux:sidebar.item icon="report" :href="route('admin.kitchen-report')"
                        :current="request()->routeIs('admin.kitchen-report')" wire:navigate>
                        {{ __('Kitchen Report') }}
                    </flux:sidebar.item>
                @endcan

                @can('meal.view')
                    {{-- day wise meals --}}
                    <flux:sidebar.item icon="report" :href="route('admin.packing-report')"
                        :current="request()->routeIs('admin.packing-report')" wire:navigate>
                        {{ __('Packing Report') }}
                    </flux:sidebar.item>
                @endcan

                @can('meal.view')
                    {{-- day wise meals --}}
                    <flux:sidebar.item icon="report" :href="route('admin.delivery-report')"
                        :current="request()->routeIs('admin.delivery-report')" wire:navigate>
                        {{ __('Delivery Report') }}
                    </flux:sidebar.item>
                @endcan

            </flux:sidebar.group>

        </flux:sidebar.nav>

        <flux:spacer />


        <flux:sidebar.nav>
            <flux:sidebar.group :heading="__('User Management')" class="grid">

                @can('user.view')
                    {{-- users --}}
                    <flux:sidebar.item icon="users" :href="route('admin.users')"
                        :current="request()->routeIs('admin.users')" wire:navigate>
                        {{ __('Manage Users') }}
                    </flux:sidebar.item>
                @endcan

                @can('role.view')
                    {{-- roles --}}
                    <flux:sidebar.item icon="roles" :href="route('admin.roles')"
                        :current="request()->routeIs('admin.roles')" wire:navigate>
                        {{ __('Manage Roles') }}
                    </flux:sidebar.item>
                @endcan

                @can('permission.view')
                    {{-- roles --}}
                    <flux:sidebar.item icon="permissions" :href="route('admin.permissions')"
                        :current="request()->routeIs('admin.permissions')" wire:navigate>
                        {{ __('Manage Permissions') }}
                    </flux:sidebar.item>
                @endcan

            </flux:sidebar.group>
        </flux:sidebar.nav>

        <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                        {{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer" data-test="logout-button">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    <x-widget.toast-message />

    @fluxScripts
</body>

</html>

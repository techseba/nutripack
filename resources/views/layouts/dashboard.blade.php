<x-layouts::admin.sidebar :title="$title ?? null">


    <flux:header class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">

        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />


        <flux:navbar class="-mb-px max-lg:hidden">
            <flux:navbar.item icon="home" href="{{ route('home') }}" wire:navigate>Home</flux:navbar.item>
            <flux:navbar.item icon="inbox" badge="12" href="#" wire:navigate>Inbox</flux:navbar.item>
            <flux:navbar.item icon="document-text" href="#" wire:navigate>Documents</flux:navbar.item>
            <flux:navbar.item icon="calendar" href="#" wire:navigate>Calendar</flux:navbar.item>

            <flux:separator vertical variant="subtle" class="my-2" />

            <flux:dropdown class="max-lg:hidden">
                <flux:navbar.item icon:trailing="chevron-down">Favorites</flux:navbar.item>

                <flux:navmenu>
                    <flux:navmenu.item href="#">Marketing site</flux:navmenu.item>
                    <flux:navmenu.item href="#">Android app</flux:navmenu.item>
                    <flux:navmenu.item href="#">Brand guidelines</flux:navmenu.item>
                </flux:navmenu>
            </flux:dropdown>
        </flux:navbar>

        <flux:spacer />

        <flux:navbar class="me-4">
            <flux:navbar.item icon="magnifying-glass" href="#" label="Search" />
            <flux:navbar.item class="max-lg:hidden" icon="cog-6-tooth" href="#" label="Settings" />
            <flux:navbar.item class="max-lg:hidden" icon="information-circle" href="#" label="Help" />
        </flux:navbar>

        @auth
            <flux:dropdown position="bottom" align="start">
                @if (auth()->user()->image)
                    <flux:profile avatar="https://fluxui.dev/img/demo/user.png" />
                @else
                    <flux:profile :initials="auth()->user()->initials()" />
                @endif

                <flux:menu>
                    <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                        <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />
                        <div class="grid flex-1 text-start text-sm leading-tight">
                            <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                            <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                        </div>
                    </div>
                    <flux:menu.separator />
                    <flux:menu.radio.group>

                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Settings') }}
                        </flux:menu.item>

                        <flux:menu.separator />

                        @if (!request()->routeIs('admin.dashboard'))
                            @can('dashboard.view')
                                <flux:menu.item :href="route('admin.dashboard')" icon="cog" wire:navigate>
                                    {{ __('Dashboard') }}
                                </flux:menu.item>
                            @endcan
                        @endif

                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                                class="w-full cursor-pointer" data-test="logout-button">
                                {{ __('Log Out') }}
                            </flux:menu.item>
                        </form>
                    </flux:menu.radio.group>
                </flux:menu>
            </flux:dropdown>
        @endauth
    </flux:header>

    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts::admin.sidebar>

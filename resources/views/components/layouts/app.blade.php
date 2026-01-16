<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen font-sans antialiased bg-neutral-50">
    {{-- The navbar with `sticky` and `full-width` --}}
    <x-nav sticky full-width>
        <x-slot:brand>
            <label for="main-drawer" class="lg:hidden mr-3">
                <x-icon name="o-bars-3" class="cursor-pointer" />
            </label>
 
            <div>App</div>
        </x-slot:brand>
 
        {{-- Right side actions --}}
        <x-slot:actions>
            
             @if($user = auth()->user())
            <x-button label="Notifications" icon="o-bell" link="###" class="btn-ghost btn-sm" responsive />
            {{-- User Profile Submenu --}}
           <x-dropdown>    
                <x-slot:trigger>
                    <x-button icon="o-user-circle" class="btn-ghost btn-sm" />
                </x-slot:trigger>
                <x-menu-item icon="o-key" link="{{ route('change-password') }}">Change Password</x-menu-item>
                <x-menu-item icon="o-power" link="{{ route('logout') }}">Logout</x-menu-item>
            </x-dropdown>
            @endif
        </x-slot:actions>
    </x-nav>
    {{-- MAIN --}}
    <x-main full-width>
       
        <x-layouts.sidebar />
        {{-- The `$slot` goes here --}}
        <x-slot:content class="bg-white">
            {{ $slot }}
        </x-slot:content>
    </x-main>
 
    {{-- Toast --}}
    <x-toast />
</body>
</html>

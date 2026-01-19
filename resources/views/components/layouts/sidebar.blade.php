{{-- SIDEBAR --}}
<x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-100 lg:bg-inherit">

    {{-- BRAND --}}
    <div class="ml-5 pt-5">Restaurant POS</div>

    {{-- MENU --}}
    <x-menu activate-by-route>

        

        <x-menu-item title="Dashboard" icon="o-sparkles" link="/" />
        @role('admin')
        <x-menu-sub title="Admin" icon="o-cog-6-tooth">
            <x-menu-item title="Menu Category" link="{{route('admin.menu-category-management')}}" />
            <x-menu-item title="Menu Items" link="{{route('admin.menu-item-management')}}" />
            <x-menu-item title="Tables" link="{{route('admin.table-management')}}" />
            <x-menu-item title="Users" link="{{route('admin.user')}}" />
        </x-menu-sub>
        @endrole       
        @role('waiter')
        <x-menu-item title="Select Table" link="{{route('select-table')}}" />
        @endrole       
    </x-menu>
    
</x-slot:sidebar>
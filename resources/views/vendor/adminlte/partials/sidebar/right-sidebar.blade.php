<aside class="control-sidebar control-sidebar-{{ config('adminlte.right_sidebar_theme') }}">
    @yield('right-sidebar')
    <!-- aqui ira el componente livewire-->
    @livewire('administracion.dashboard.barra-derecha.barra-derecha-main')
</aside>

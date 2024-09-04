<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('template.header')
    
    <body>

        <div class="wrapper">

            <!-- Muestra el menú solo si el usuario está autenticado -->
            {{--  @if($page['props']['isLoggedIn'])
                @include('template.menu')
                
                <!-- Left sidebar -->
                @include('template.left_sidebar')
                <!-- End Left sidebar -->
                
                <div class="content-page">
                    <div class="content">
                        <div class="container-fluid">
                            @inertia
                        </div>
                    </div>
                
                    @include('template.footer')
                </div>
            @else
                @inertia
            @endif  --}}
            @inertia
        </div>
        
        @include('template.scripts')
    </body>
</html>

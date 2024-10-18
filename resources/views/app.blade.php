<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('template.header')
    
    <body>

        <div class="wrapper">
            @inertia
        </div>
        
        <script type="text/javascript">
            window.Laravel = {
                    csrfToken: "{{ csrf_token() }}",
                    baseUrl: "{{config('app.url')}}",
                    urlBase: "{{ url('/')}}",
                }
        </script>
        @include('template.scripts')
    </body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('template.header')
    
    <body>
        <!-- Loader -->
        <div id="preloader">
            <div id="status">
                <div class="spinner">
                    <div class="spinner-wrapper">
                        <div class="rotator">
                            <div class="inner-spin"></div>
                            <div class="inner-spin"></div>
                        </div>
                        {{--  <img src="{{ asset('images/abarrotes/logo.png') }}" alt="Loader Image" class="loader-image">  --}}
                    </div>
                </div>
            </div>
        </div>

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

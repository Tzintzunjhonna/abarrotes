<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <meta charset="utf-8">

    <title inertia>{{ config('app.name', 'ABARROTES') }}</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully responsive admin theme which can be used to build CRM, CMS,ERP etc." name="description" />
    <meta content="CONTRERAS CORP" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('images/abarrotes/favicon/favicon.ico')}}" type="image/x-icon">

    <!-- Daterangepicker css -->
    <link href="{{ URL('css/vendor/daterangepicker.css') }}" rel="stylesheet">

    <!-- Vector Map css -->
    <link href="{{ URL('css/vendor/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet">

    <!-- Responsive Table css -->
    <link href="{{ URL('css/vendor/rwd-table.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Theme Config Js -->
    <script src="{{ URL('js/config.js') }}"></script>

    <!-- Icons css -->
    <link href="{{ URL('css/icons.css') }}" rel="stylesheet" type="text/css" />

    <!-- App css -->
    <link href="{{ URL('css/app.css') }}" rel="stylesheet" type="text/css" id="app-style" />

    <!-- sb-admin-2 CSS -->
    <link href="{{ URL('css/sb-admin-2.css') }}" rel="stylesheet" type="text/css" id="app-style" />

    <!-- style_2 CSS -->
    <link href="{{ URL('css/style_2.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />


    <!-- Scripts -->

</head>

<body class="bg-success d-flex justify-content-center align-items-center min-vh-100 p-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-4 col-md-5">
                <div class="card bg-principal">
                    <div class="card-body p-4">

                        <div class="text-center w-50 mx-auto my-4">
                            <img src="{{ URL('images/404-error.svg') }}" title="invite.svg">
                        </div>

                        <h3 class="text-center mb-4 mt-3">Página no encontrada</h3>

                        <p class="text-muted text-center mt-3">
                            Pareciera que has tomado un giro equivocado. No te preocupes... le pasa a los mejores de nosotros. Quizás quieras
                            verificar tu conexión a internet.
                        </p>
                        <div class="mt-4 text-center">
                            <a href="/" class="btn btn-success w-100">Ir a inicio...</a>
                        </div>

                    </div> <!-- end card-body -->
                </div>
                <!-- end card -->
            </div> <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->

    @include('template.scripts')

</body>

</html>
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


    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead

</head>

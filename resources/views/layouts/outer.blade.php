<!DOCTYPE html>
<html lang="en" class="min-h-screen">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-site-verification" content="aqTUEUmaWe4XRgzxXoerh3b6ARaB8IralaY25MR4Y_U" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title') | Samarkand Design</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.1/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Muli:300,400' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="{{ mix('css/main.css') }}">



    <link rel="shortcut icon" href="/img/favicon.ico">

    @yield('head')

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    @include('partials._gtm')
</head>

<body>
    <div id="app" class="font-light font-body tracking-wide">
        @include('partials._navbar')
        @yield('body')

        @include('partials._footer')
    </div>
    @include('partials._scripts')
    @yield('scripts')
</body>

</html>
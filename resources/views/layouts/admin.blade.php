@include('comps.navs_admin')

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>MicroAcquire Admin @yield('title')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="/js/jquery-ui/jquery-ui.min.css">

  <link href="https://fonts.googleapis.com/css?family=Montserrat:100,100i,400,400i,500,500i" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <link rel="stylesheet" href="/js/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="/js/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
  <link rel="stylesheet" href="/js/bootstrap-datepicker/css/bootstrap-datepicker.standalone.min.css">
  <link rel="stylesheet" href="/js/bootstrap-datepicker/css/bootstrap-datepicker3.standalone.min.css">

  <link rel="stylesheet" type="text/css" href="{{ url('/') }}/js/fancybox/jquery.fancybox.css?v=2.1.5"/>
  <link rel="stylesheet" type="text/css" href="{{ url('/') }}/js/select2/css/select2.min.css"/>

  @section('css_import')  
    <link rel="stylesheet" href="/css/app.css">
  @show

</head>
<body class="body-admin @yield('body_class') {{ $page_class ?? '' }}">

  @yield('nav')

  @yield('content')

  @yield('footer')


<!-- jQuery library -->
@section('js_import')
<script src="/js/jquery-1.12.4.min.js"></script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->

<script src="/js/app.js"></script>
<script src="/js/functions.js?v0.11"></script>

<script src="/js/jquery-ui/jquery-ui.min.js"></script>

<script src="/js/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="{{ url('/') }}/js/bootstrap-notify/bootstrap-notify.min.js"></script>

<script type="text/javascript" src="{{ url('/') }}/js/jquery.actual/jquery.actual.min.js"></script>
<script type="text/javascript" src="{{ url('/') }}/js/fancybox/jquery.fancybox.pack.js?v=2.1.5"></script>
<script type="text/javascript" src="{{ url('/') }}/js/select2/js/select2.full.min.js"></script>



@show


</body>
</html>
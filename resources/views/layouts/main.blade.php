@include('comps.navs')

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <title>MicroAcquire @yield('title')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  @section('css_import')
    <link rel="stylesheet" href="/css/app.css">
  @show

  <link href="https://fonts.googleapis.com/css?family=Montserrat:100,100i,400,400i,500,500i" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">  

</head>
<body class="{{ $page_class ?? '' }}">

  @yield('nav')

  @yield('content')

  @yield('footer')


<!-- jQuery library -->
@section('js_import')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="/js/app.js"></script>
@show


</body>
</html>
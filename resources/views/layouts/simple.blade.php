

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
<body class="type-simple @yield('body_class') {{ $page_class ?? '' }}">

  <nav class="site-header">
    <div class="container text-center">
      <a class="" href="{{ url('/') }}">
        <img src="{{ url('/') }}/images/home/microacquire.png" alt="" class="logo" />
      </a>
    </div>
  </nav>

  @component('comps.block')
    @slot('block_name')
        @yield('block_name')
    @endslot
    @slot('block_class')
        @yield('block_class')
    @endslot

    @yield('content')

  @endcomponent

  <div class="bottom-deco"></div>

  


<!-- jQuery library -->
@section('js_import')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="/js/app.js"></script>
@show


</body>
</html>
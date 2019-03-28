@section('nav')  
  <nav class="site-header sticky-top colored">
    <div class="container">

      <div class="row mobile-menu-row clearfix">
        <div id="mobile-menu-curtain"></div>
        <div class="logo float-left">
          <a class="" href="/">
            <img src="/images/home/microacquire.png" alt="" class="img-responsive" />
          </a>
        </div>

        <div class="float-right">
          <!-- Collapse button -->
          <button id="mobile_nave_button" class="navbar-toggler collapsed" type="button"></button>

          <!-- Collapsible content -->
          <div class="navbar-collapse" id="mobile_nav_menu">

            <!-- Links -->
            <ul class="navbar-nav mr-auto">
              <li class="nav-item"><a class="link" href="/apply">Apply</a></li>
              <li class="nav-item active"><a class="link" href="/about">About</a></li>
              <li class="nav-item"><a class="link" href="/contact">Contact</a></li>
              <li class="nav-item"><a class="link" href="/login">Login</a></li>
            </ul>
            <!-- Links -->

          </div>
        </div>
      </div>

      <div class="row desktop-menu-row">
        <div class="logo col-md-3">
          <a class="" href="/">
            <img src="/images/home/microacquire.png" alt="" class="img-responsive" />
          </a>
        </div>

        <div class="text-right col-md-9" style="padding-right: 0;">
          <a class="nav-item" href="/apply">Apply</a>
          <a class="nav-item active" href="/about">About</a>
          <a class="nav-item" href="/contact">Contact</a>
          <a class="nav-item" href="/login">Login</a>
        </div>

      </div>
    </div>
  </nav> 
@endsection


@section('footer')
  <div class="footer">
    <div class="container">

      <div class="row footer-top">
        <div class="col-md-6 col-sm-6">
          <img src="/images/home/microacquire.png" alt="" class="img-responsive">
          <p class="cust-address">785 Shotgun Road, Sunrise,<br>Florida 33326. United States</p>
        </div>
        <div class="col-md-6 col-sm-6">
          <ul class="navigation-menu">
            <li><a href="#">Apply</a></li>
            <li class="active"><a href="#">About</a></li>
            <li><a href="#">Contact</a></li>
            <li><a href="#">Login</a></li>
          </ul>
          <ul class="footer-social-icons">
            <li><a href="#"><img src="/images/home/twitter.png"></a></li>
            <li><a href="#"><img src="/images/home/linkedin.png"></a></li>
            <li><a href="#"><img src="/images/home/youtube.png"></a></li>
            <li><a href="#"><img src="/images/home/loc.png"></a></li>
            <li><a href="#"><img src="/images/home/b.png"></a></li>
          </ul>
        </div>
      </div>
      <div class="row footer-bottom">
        <div class="col-md-6 col-sm-6">
          <a class="cust-copyright" href="#">@ 2019 Acquisition. All rights reserved</a>
        </div>
        <div class="col-md-6 col-sm-6">
          <ul class="footer-bottom-menu">
            <li><a href="#"> Terms of Service </a></li>
            <li><a href="#"> Privacy Policy </a></li>
          </ul>
        </div>
      </div>
      
    </div>
  </div>
@endsection
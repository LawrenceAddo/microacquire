
function assignEvents() {
  $('#mobile_nave_button').on('click', function(e) {
    e.preventDefault();
    $(this).toggleClass('collapsed', !$(this).hasClass('collapsed'));
    $('#mobile_nav_menu').toggleClass('show', !$(this).hasClass('collapsed'));
    $('body').toggleClass('mobile-menu-open', !$(this).hasClass('collapsed'));
  });

  $('#mobile_nav_menu').on('click', function(e) {
    $('#mobile_nave_button').trigger('click');
  });

  $('#mobile-menu-curtain').on('click', function(e){
    $('#mobile_nave_button').trigger('click');
  });


  $('nav a').click(function(e) {
    var sectionTo = $(this).attr('href');
    if ((sectionTo.indexOf('#') === 0) && (sectionTo.length > 1)) {
      e.preventDefault();
      $('html, body').animate({
        scrollTop: $(sectionTo).offset().top - 100
      }, 200);  
    }
  });

}

function byScrolling() {
  var scrollPos = $(document).scrollTop();
  $('body').toggleClass('scrolled', scrollPos > 0);
  $('body').toggleClass('scrolled-far', scrollPos > 300);
}


function init_plugins() {
  if(jQuery().slick) {
    $('.fancy-slider').each(function(i, o) {
      if ($(o).hasClass('not-common')) return true;
      $(o).slick({
        dots: true
      });
    });
  }
  
  if(jQuery().fancybox) {
    $(".fancybox").fancybox();
  }

  $('textarea').each(function(i, o){
    var $obj = $(o);
    if ($obj.length < 1) return;

    helper.initLimitedTyper($obj);

  });

  $('datepicker').each(function(i, o){
    $(o).datepicker({
      zIndexOffset: 500,
      orientation: "right bottom",
      format: 'yyyy-mm-dd'
    /*
      format: 'mm/dd/yyyy',
      startDate: '-3d'
    */
    });
  });
  
}


$(window).scroll(function(){
    byScrolling();
});


$(document).ready(function(){
    assignEvents();
    byScrolling();
    init_plugins();
})

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
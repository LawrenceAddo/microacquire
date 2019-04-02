String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};

jQuery.fn.typerLimit = function(opts) {
  var $me = jQuery(this);

  $me.on('keyup', function(e){calcTextCount(e, $me, opts);});
  $me.on('keypress', function(e){calcTextCount(e, $me, opts);});
  $me.on('paste', function(e){
      var $me = jQuery(thi);
      setTimeout(function(){
          $me.trigger('keyup');
      }, 100); // interval decreased
  });

  $me.trigger('keyup');
}


jQuery.fn.overlay = function(opts, anim) {
  var opacity = 0;
  if (typeof opts == "number") {
    opts = {
      opacity: opts
    };
  } else {
    opts = opts || {};
    opts.opacity = opts.opacity != undefined ? opts.opacity : 0.8;
  }

  
  var overlayClass = "loading-overlay";
  // var $overlay = jQuery('<div><div class="circle-loader-wrap"><svg class="circle-loader" width="120" height="120" version="1.1" xmlns="http://www.w3.org/2000/svg"><circle cx="60" cy="60" r="50"></circle></svg></div></div>')
  //     .addClass(overlayClass);

  var $overlay = jQuery('<div><div class="circle-loader-wrap"><img class="circle-loader" src="' + SITE_URL + '/images/icons/loading 04.gif"></div></div>').addClass(overlayClass);

  var $target = jQuery(this).hasClass("modal") ? jQuery(this).find(".modal-dialog") : jQuery(this);
  if ($target.hasClass('loading-medium')) {
    $overlay = jQuery('<div><div class="circle-loader-wrap"><svg class="circle-loader" width="60" height="60" version="1.1" xmlns="http://www.w3.org/2000/svg"><circle cx="30" cy="30" r="20"></circle></svg></div></div>')
      .addClass(overlayClass);
  }

  $overlay.prependTo($target);

  if (anim != undefined) {
    if(anim.duration == undefined) anim.duration = 400;
    $overlay.css(opts);
    $overlay.css(anim.from).stop().animate(anim.to);
  } else {
    $overlay.css(opts);
  }

  $target.addClass('loading');
  return this;
};


jQuery.fn.overlayDone = function(anim) {
  var $target = jQuery(this).hasClass("modal") ? jQuery(this).find(".modal-dialog") : jQuery(this);

  if ((anim != undefined) && anim) {

    if(anim.duration == undefined) anim.duration = 400;
    $target.children(".loading-overlay").css(anim.from).animate(anim.to, anim.duration, function(){
      jQuery(this).remove();
    });

  } else {
    $target.children(".loading-overlay").remove();
  }

  $target.removeClass('loading');
  return this;
};
/**
 * modal.js
 *
 * @author Wilson
 */



  $.fn.redraw = function() {

    var $modal = $(this);
    if (!$modal.hasClass("modal")) {
      return this;
    }
    if ($modal.parent().hasClass("note-dialog")) {
      // Skip resizing
      return this;
    }

    var windowHeight = window.innerHeight;
    var $md = $modal.find(".modal-dialog");

    if ($('body').hasClass('embed')) {
      $md.css({
        "margin-top": 200,
      });
      return;
    }

    if ($modal.hasClass("vCenter")) {

      var mdHeight;
      //start_timer();
      mdHeight = $md.actual('outerHeight');
      //end_timer("modal-dialog.actual(outerHeight)");
      var marginTop = (windowHeight - mdHeight) / 2;

      marginTop = marginTop < 10 ? 10 : marginTop;
      marginBtm = marginTop == 10 ? 10 : 0;
      $md.css({
        "margin-top": marginTop,
        "margin-bottom": marginBtm
      });
    } else if ($modal.hasClass("hRight")) {
      var offset = 0;
      offset += $md.find('.modal-header').actual("outerHeight");
      offset += $md.find('.modal-footer').actual("outerHeight");

      var $mb = $md.find('.modal-body');
      offset += parseInt($mb.css("padding-top"));
      offset += parseInt($mb.css("padding-bottom"));

      $mb.height(windowHeight - offset);
    }

    return this;
  };

  $.bindModal = function(opts) {

    var defaults = {
      // 1. Configuration

      // CSS selector
      selector:  false,

      // jQuery Selector or Object
      parent: false,

      // Modal (css selector or DOM / jQuery object)
      modal:     false,

      // boolean or the string 'static': Includes a modal-backdrop element. Alternatively, specify static for a backdrop which doesn't close the modal on click.
      backdrop: 'static',

      // boolean: Closes the modal when escape key is pressed
      keyboard:  false,

      // boolean: Shows the modal when initialized.
      show:      false,

      // 2. Events

      // This event fires immediately when the show instance method is called. If caused by a click, the clicked element is available as the relatedTarget property of the event.
      onShow:    false,

      // This event is fired when click Cancel
      onCancel: false,

      // This event is fired when click Save
      onSave: false,
      
      // This event is fired when the modal has been made visible to the user (will wait for CSS transitions to complete). If caused by a click, the clicked element is available as the relatedTarget property of the event.
      onShown:   false,

      //This event is fired immediately when the hide instance method has been called.
      onHide:    false,

      // This event is fired when the modal has finished being hidden from the user (will wait for CSS transitions to complete).
      onHidden:  false,

      // This event is fired when the modal has loaded content using the remote option.
      onLoaded:   false,

      // When you click Submit button in dialog
      onSubmit: false,

      // If this returns FALSE, nothing is fired
      before: function(e) { return true; }
    };

    opts = $.extend({}, defaults, opts);

    if (typeof opts.modalId != "undefined" && opts.modalId != '') {
      opts.modal = "#" + opts.modalId;
    }

    var $modal = $(opts.modal);
    opts.modal = $modal;

    if ($modal.data('is_modal_initialized')) {
      // already initialized
    } else {
      $modal.modal(opts);
      $modal.data('is_modal_initialized', true);
    }

    if (opts.parent == false) {
      // console.log("Warning: $.bind_modal should have parent.");
      opts.parent = document;
    }

    if (opts.selector) {
      $(opts.parent).off("click", opts.selector);
      $(opts.parent).on("click", opts.selector, function(e) {
        opts.delegator = e.currentTarget;

        if (!opts.before(e)) {
          return;
        }

        $modal.data("delegator", e.currentTarget).modal('show');
      });  
    }
    

    $modal.on("show.bs.modal", function(e) {
      if (opts.onShow !== false) {
        opts.onShow(e);
      }

      setTimeout(function () { $modal.redraw(); }, 150);

      /*if ( !$(this).hasClass("no-blur") ) {
        setTimeout(function() {
          $("body").addClass("blured");
        }, 300);
      }*/
    });

    $modal.on("shown.bs.modal", function(e) {
      if (opts.onShown !== false) {
        opts.onShown(e);
      }
    });

    $modal.on("hide.bs.modal", function(e) {
      /*if ( !$(this).hasClass("no-blur") ) {
        $("body").removeClass("blured");
      }*/

      if (opts.onHide !== false) {
        opts.onHide(e);
      }
    });

    $modal.on("hidden.bs.modal", function(e) {
      if (opts.onHidden !== false) {
        opts.onHidden(e);
      }
    });

    $modal.on("loaded.bs.modal", function(e) {
      if (opts.onLoaded !== false) {
        opts.onLoaded(e);
      }
    });

    if (opts.onSubmit !== false) {
      $modal.on("click", ".btn-submit", function(e) {
        opts.delegator = $modal.data("delegator");
        opts.onSubmit(e);
        return false;
      });
    }

    $modal.on("click", ".btn-save", function(e) {
      $(this).attr('disabled', true);
      if (opts.onSave !== false) {
        opts.onSave(e);
      }
      return true;
    });

    $modal.on("click", ".btn-no", function(e) {
      if (opts.onCancel !== false) {
        opts.onCancel(e);
      }
      return true;
    }).on("click", ".btn-cancel", function(e) {
      if (opts.onCancel !== false) {
        opts.onCancel(e);
      }
      return true;
    });


    // Modal Progress dots mapping

    $modal.on('click', '.modal-title .back', function(e){
      e.preventDefault();
      var $me = $(this);
      
      var actAs = $me.attr('act-as');
      if (actAs && $(actAs).length > 0) {
          $(actAs).trigger('click');
          return;
      }
    });
    
    $modal.on('click', '.button-panel .pages a', function(e) {
      e.preventDefault();

      var $me = $(this),
          $modal = $me.closest('.modal');
      if ($me.parent().hasClass('active')) return;
      
      var targetSelector = $me.data('target');
      if (targetSelector) {
        $modal.find('.curtain, .main').removeClass('on').end()
              .find(targetSelector).addClass('on');
      }


      var actAs = $me.attr('act-as');
      if (actAs && $(actAs).length > 0) {
          $(actAs).trigger('click');
          return;
      }     

      var callBack = $me.attr('onclick');
      if (callBack) {
        try {
          eval(callBack);
        } catch(e) { console.log(e); }  
      }
      return;
      
    });

    $modal.on('click', '.modal-header .back', function(e){
      e.preventDefault();
      var $me = $(this);
      var callBack = $me.attr('onclick');
      try {
        eval(callBack);
      } catch(e) { console.log(e); }
    });
    
  };

  $.instantModal = function(type, text, buttonHtml, title, data) {
    if ( !text ) {
      return false;
    }

    // Class of modal
    var modalClass = 'modal modal-sccrol vCenter fade modal-' + type;

    // Find modal
    var $modal = $(".modal-alert." + type);
    /*
    var is_html = (text.indexOf('<p') === 0 || text.indexOf('<div') === 0),
        html = is_html ? text : '<p>' + text + '</p>';
    */

    if ($modal.length > 0) {
      $modal.remove();
    } 
    
    // If not found, create this modal
    var str_type = type.charAt(0).toUpperCase() + type.substr(1);
    var modal_id = 'modal' + str_type;

    var html = '<div class="' + modalClass + '" tabindex="-1" role="dialog" aria-labelledby="' + str_type + '" aria-hidden="true" id="' + modal_id + '">'
      +   '<div class="modal-dialog">'
      +     '<div class="modal-content">'
      +       '<div class="modal-header">'
      +         '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>'
      +       '</div>'
      +       '<div class="modal-body">'
      +       ((title != false) ? ('<h4>' + title + '</h4>') : '')
      +          '<div class="msg-content">' + text + '</div>'
      +       '</div>'
      +       '<div class="modal-footer">'
      +           buttonHtml
      +       '</div>'
      +     '</div>'
      +   '</div>'
      + '</div>';

    $modal = $(html).appendTo('body');

    // Show modal
    $modal.modal({
      backdrop: 'static',
      keyboard: false,
    }).data('data', data).redraw();
  };


  $(window).on("resize", function(e) {
    $(".modal.in").redraw();
  });

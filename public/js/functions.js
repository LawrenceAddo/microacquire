
/*
 *
 * Text Length Calc Helper functions
 *
 *
 */

function utf8Len (codePoint) {
    if(codePoint >= 0xD800 && codePoint <= 0xDFFF)
        return 1; // This is bug case
    if(codePoint < 0) return 1; // This is bug case
    if(codePoint <= 0x7F) return 1;
    if(codePoint <= 0x7FF) return 2;
    if(codePoint <= 0xFFFF) return 3;
    if(codePoint <= 0x1FFFFF) return 4;
    if(codePoint <= 0x3FFFFFF) return 5;
    if(codePoint <= 0x7FFFFFFF) return 6;

    return 1; // This is bug case
}

function isHighSurrogate (codeUnit) {
    return codeUnit >= 0xD800 && codeUnit <= 0xDBFF;
}

function isLowSurrogate (codeUnit) {
    return codeUnit >= 0xDC00 && codeUnit <= 0xDFFF;
}

/**
* Transforms UTF-16 surrogate pairs to a code point.
* See RFC2781
*/
function toCodepoint (highCodeUnit, lowCodeUnit) {
    if(!isHighSurrogate(highCodeUnit)) return ""; // Error case
    if(!isLowSurrogate(lowCodeUnit)) return ""; // Error case
    highCodeUnit = (0x3FF & highCodeUnit) << 10;
    var u = highCodeUnit | (0x3FF & lowCodeUnit);
    return u + 0x10000;
}

/**
* Counts the length in bytes of a string when encoded as UTF-8.
* str - a string
* return - the length as an integer
*/
function utf8ByteCount (str) {
    var count = 0;
    for(var i=0; i<str.length; i++) {
        var ch = str.charCodeAt(i);
        if(isHighSurrogate(ch)) {
            var high = ch;
            var low = str.charCodeAt(++i);
            count += utf8Len(toCodepoint(high, low));
        } else {
            count += utf8Len(ch);
        }
    }
    return count;
}


function calcTextCount (event, $msg, opts) {

  if (event.type == "keypress") {
      if ( (event.keyCode >= 33 && event.keyCode <= 40) || event.keyCode == 8 || event.keyCode == 46 ) {
          return false;
      }
  }

  if (!opts.max_char_count) opts.max_char_count = 100;
  
  var message = $msg.val();
  var count = utf8ByteCount(message);
  if (event.type == "keypress") {
      if (count > opts.max_char_count) {
          return false;
      }
      return true;
  }

  var cut_len = 0;
  if (count > opts.max_char_count) {
      while(utf8ByteCount(message) > opts.max_char_count) {
          message = message.substring(0, message.length-1);
          cut_len++;
      }
      count = opts.max_char_count;
      $msg.val(message);
  }

  if (opts.label_char_left) {
    // opts.label_char_left.html(count + ' / ' + opts.max_char_count);
    opts.label_char_left.html(opts.max_char_count - count);
  }

}

/*
 *
 */

function nl2br(text) {
  return text.replace(/(?:\r\n|\r|\n)/g, '<br />');
}

function markup(k, src, tpl) {
  if (typeof src != 'string') return src;
  if (!tpl) tpl = '<b>\$&</b>';
  var re = new RegExp(k,"gi");
  src = src.replace(re, tpl);
  return src;
}

function getHashTarget() {
  var hash = document.location.href.split('#');
  if (hash.length > 1) {
    hash = hash.pop();
    if (hash) {
      return hash.split('?')[0];
    } 
  }
  return '';
}

function makeRandomId() {
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for (var i = 0; i < 5; i++)
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}



function ajax(url, method, data, doneHandler, errorHandler) {
  $.ajax({
        type: method,
        url: url,
        data: data,
        dataType: "json",
        cache: false,
    }).done(function (data) {
        if (!data.status && data.redirect) {
          document.location.href = data.redirect;
          return;
        }

        if (data.msg) {
          if (data.status == 1)   {
            notify.success(data.msg);
          } else if (data.status == 0) {
            notify.error(data.msg);  
          } else {
            notify.info(data.msg);  
          }
        }

        if (doneHandler) {
          doneHandler(data);
        } else {
          $('body').overlayDone();
        }

    }).fail(function() {
        
        $('body').overlayDone();
        notify.error('Something went wrong. Please try again.');

        if (errorHandler) errorHandler(data);
    });
}


function ajaxGet(url, doneHandler, errorHandler) {
  ajax(url, 'GET', false, doneHandler, errorHandler);
}

function ajaxDelete(url, doneHandler, errorHandler) {
  ajax(url, 'DELETE', false, doneHandler, errorHandler);
}


function ajaxPost(url, data, doneHandler, errorHandler) {
  ajax(url, 'POST', data, doneHandler, errorHandler);
}

function ajaxPatch(url, data, doneHandler, errorHandler) {
  ajax(url, 'PATCH', data, doneHandler, errorHandler);
}



var helper = {
  isMobile: function() {
    var isMobile = false;
    // device detection
    if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
        || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) { 
        isMobile = true;
    }

    return isMobile;
  },

  initSelectbox: function (opts) {
    var defaults = {
      selector: '.select2',
      width: '100%',
      // placeholder: 'Select ...',
      minimumResultsForSearch: -1,
      dropdownCssClass: 'fancy-widget',
      searchPlaceHolder: 'Search ...'
    };

    opts = $.extend({}, defaults, opts);

    // If dropdownCssClass is not defined, just use containerCssClass
    opts.dropdownCssClass = opts.dropdownCssClass || opts.containerCssClass;

    if (helper.isMobile()) {
      // Never focus select2 on mobile
      opts.shouldFocusInput = function (instance) {
        return false;
      };
    }

    $(opts.selector).each(function () {
      var $this = $(this);
      //
    });

    $(opts.selector).select2(opts);
    $(opts.selector).on("select2:opening", function(e) {
        if (opts.onOpening)  return opts.onOpening(e);
        return true;
    });

    $(opts.selector).on("select2:open", function(e) {
        $(".select2-search__field").attr("placeholder", opts.searchPlaceHolder);

        var $currentTarget = $(e.currentTarget);

        if ($currentTarget.closest('.modal, .flyup').length > 0) {
          $("body").children(".select2-container--open").css("z-index", 2000);
        }

        if (opts.onOpen)  opts.onOpen(e);
    });

    $(opts.selector).on("select2:closing", function(e) {
        if (opts.onClosing)  return opts.onClosing(e);
        return true;
    });
    $(opts.selector).on("select2:close", function(e) {
        $(".select2-search__field").attr("placeholder", null);
        if (opts.onClose)  opts.onClose(e);
    });
  },

  renderPagination: function ($wrap, total, current, opts) {
    if ( !$wrap ) {
      console.log('[function:renderPagination] $wrap is not defined.');
      return false;
    }

    var label_page_stats = "{CURRENT} / {TOTAL} Page(s)";

    total = parseInt(total);
    current = parseInt(current);
    
    if (total < 1) {
      total = 1;
    }
    if (current < 1) {
      current = 1;
    }
    if (current > total) {
      current = total;
    }

    var ulHtml = '<ul class="pagination">';
    ulHtml += '<li class="first"><a class="goto_first ' + (current == 1 ? 'disabled' : '') + '" data-page="1"><i class="fa fa-angle-double-left" aria-hidden="true"></i></a></li>';
    ulHtml += '<li class="prev"><a class="goto_prev ' + (current == 1 ? 'disabled' : '') + '" data-page="' + Math.max(1, current - 1) + '"><i class="fa fa-angle-left" aria-hidden="true"></i></a></li>';

    var firstPack = 4;
    var middlePack = Math.floor(current / 2) * 2;
    var lastPack = total;

    if (total < 6) {
      firstPack = 5;
    } else {
      if (current > 3) {
        firstPack = 2;
      }

      if (current > total - 3) {
        middlePack = total - 2;
      }
    }

    firstPack = Math.min(firstPack, total);
    for (var i = 1; i <= firstPack; i++) {
      ulHtml += '<li class=""><a class="goto_page ' + /*(i > 999 ? 'xs' : '') +*/ (i == current ? 'active' : '') + '" data-page="' + i + '">' + i + '</a></li>';
    }

    if ( total >= 6) {
      if (current > 3) {
        ulHtml += '<li class=""><a class="goto_more">...</a></li>';

        for (var i = 0; i < 2; i++) {
          var p = middlePack + i;
          if (p > total - 1) break;
          ulHtml += '<li class=""><a class="goto_page ' + (p == current ? 'active' : '') + '" data-page="' + p + '">' + p + '</a></li>';
        }
      }

      if ( current <= total - 3) {
        ulHtml += '<li class=""><a class="goto_more">...</a></li>';
      }

      ulHtml += '<li class=""><a class="goto_page ' + (total == current ? 'active' : '') + '" data-page="' + total + '">' + total + '</a></li>';
    }

    ulHtml += '<li class="next"><a class="goto_next ' + (current == total ? 'disabled' : '') + '" data-page="' + Math.min(current + 1, total) + '"><i class="fa fa-angle-right" aria-hidden="true"></i></a></li>';
    ulHtml += '<li class="last"><a class="goto_last ' + (current == total ? 'disabled' : '') + '" data-page="' + total + '"><i class="fa fa-angle-double-right" aria-hidden="true"></i></a></li>';

    // Page selector
    if (typeof opts == 'object') {
      if (typeof opts.meta != 'undefined') {
        opts.meta.perpageValue = opts.meta.perpageValue || 20;
        opts.meta.perpageOptions = opts.meta.perpageOptions || [10, 20, 40, 80, 100];
        opts.meta.totalItems = opts.meta.totalItems || 0;

        if (opts.meta.totalItems == 0) {
          $wrap.find('.paginator').hide();
          return;
        }

        var maxPerpageOption = Math.max(opts.meta.totalItems, 20);

        ulHtml += '<li class="input-group pagi-total">';
        ulHtml +=   '<div class="page-total clearfix">';
        ulHtml +=     '<div class="form-control-static pull-left totalv"><span>' + $phrases.pagination_label_total + ': </span><span class="total-items">' + opts.meta.totalItems + '</span></div>';

        // Dropdown {{{
        ulHtml += '<div class="pagi-perpage input-group pull-right">';
        ulHtml += '<select name="perpage" class="select2 perpage form-control">';
        opts.meta.perpageOptions.every(function(v) {
          ulHtml += '<option value="' + v + '"' + (v == opts.meta.perpageValue ? ' selected' : '') + '>' + v + '</option>';

          if (v >= maxPerpageOption) {
            return false;
          }

          return true;
        });
        ulHtml += '</select>';
        ulHtml += '</div>';
        // }}} Dropdown

        ulHtml +=   '</div>';
        ulHtml += '</li>';
      }
    }

    var pageStats = label_page_stats.replace('{CURRENT}', current).replace('{TOTAL}', total);

    var wrapperHtml = '<div class="paginator paginator-northpark clearfix">'
    +   '<div class="pull-left part-stats">'
    +      '<span class="page-stats">' + pageStats + '</span>'
    +   '</div>';

    wrapperHtml += '<div class="pull-right part-paginator">' + ulHtml + '</div>'
        + '</div>';
    
    $wrap.html(wrapperHtml);

    if (typeof opts == 'object' && typeof opts.meta != 'undefined') {
      helper.initSelectbox({
        selector: $wrap.find('.select2')
      });
    }
  },

  initSlider: function($rangerObj, $value0Obj, $value1Obj, opts) {
    var revMin = parseInt($value0Obj.val());
    var revMax = parseInt($value1Obj.val());

    var defaults = {
      value_template: '$ {%V}',
      range_template: '{%1} ~ {%2}',
      range_template_no_min: '&#8804;&nbsp;&nbsp;&nbsp;{%V}',
      range_template_no_max: '&#8805;&nbsp;&nbsp;&nbsp;{%V}',
      full_range_template: 'All',
      label_template: 'Value: {%V}',
      label: false
    };
    
    opts = $.extend({}, defaults, opts);

    if (!opts.label) opts.label = $rangerObj.parent().find('label');


    $rangerObj.slider({
        range: true,
        min: revMin,
        max: revMax,
        values: [revMin, revMax],
        slide: function( event, ui ) {
            $value0Obj.val(ui.values[0]).attr('data-value', ui.values[0]);
            $value1Obj.val(ui.values[1]).attr('data-value', ui.values[1]);

            var v0str = opts.value_template.replace('{%V}', ui.values[0]);
            var v1str = opts.value_template.replace('{%V}', ui.values[1]);

            if (ui.values[0] == $(this).slider( "option", "min")) {
              $value0Obj.val('');
              v0str = '';
            }
            if (ui.values[1] == $(this).slider( "option", "max")) {
              $value1Obj.val('');
              v1str = '';
            }

            var finalStr = '';
            if ((v0str == '') && (v1str == '')) {
              finalStr = opts.full_range_template;
            } else if (v0str == '') {
              finalStr = opts.range_template_no_min.replace('{%V}', v1str);
            } else if (v1str == '') {
              finalStr = opts.range_template_no_max.replace('{%V}', v0str);
            } else {
              finalStr = opts.range_template.replace('{%1}', v0str).replace('{%2}', v1str);
            }

            var lbl = opts.label_template.replace('{%V}', finalStr);

            opts.label.html(lbl);
        }
    })
    .slider('option', 'slide').call($rangerObj, null, {values: [revMin, revMax]});

    $value0Obj.val('');
    $value1Obj.val('');
  },

  initLimitedTyper: function($obj, opts) {
    var limitv = parseInt($obj.attr('limit'));
    limitv = isNaN(limitv) ? 1000 : limitv;

    $obj.typerLimit({
        max_char_count: limitv,
        label_char_left: $obj.parent().find('.char-counter .metre')
    });
  }
}
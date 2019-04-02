
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


function ajaxPost(url, data, doneHandler, errorHandler) {
  ajax(url, 'POST', data, doneHandler, errorHandler);
}

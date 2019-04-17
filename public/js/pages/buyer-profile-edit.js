/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 3);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/pages/buyer-profile-edit.js":
/*!**************************************************!*\
  !*** ./resources/js/pages/buyer-profile-edit.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var fn = {
  $socialList: null,
  firstData: null,
  buyer: {
    init: function init() {
      $('#submit_save').click(function (e) {
        $('#submit_type').val(0);
      });
      $('#submit_review').click(function (e) {
        $('#submit_type').val(1);
      });
      $('#frm_buyer_save').submit(function () {
        //
        var submit_type = $('#submit_type').val(); // console.log(submit_type);

        if (!$('#iagree').is(':checked')) {
          var html_content = "<div>You should agree to our Terms of Service and have read and understood the Privacy Policy before submit.</div>",
              html_footer = '<div class="row">' + '<div class="col-sm-12 form-group">' + '<a data-dismiss="modal" class="btn btn-default">Okay</a>' + '</div>' + '</div>';
          $.instantModal('alert', html_content, html_footer, 'Information');
          return false;
        }

        $('body').overlay();
        ajaxPost($(this).attr('action'), $(this).serialize(), function (data) {
          // complete handler
          $('body').overlayDone();
          notify.success('Successfully submitted.');
          document.location.href = data.redirect ? data.redirect : document.location.href;
        });
        return false;
      });
      fn.firstData = $('#frm_buyer_save').serialize();
      $('input,textarea').on('keyup change', function () {
        fn.buyer.monitor();
      });
      fn.buyer.monitor();
    },
    monitor: function monitor() {
      var changed = fn.firstData != $('#frm_buyer_save').serialize();

      if (changed) {
        $('#submit_save,#submit_review').removeAttr('disabled');
      } else {
        $('#submit_save,#submit_review').attr('disabled', 'disabled');
      }
    }
  },
  socials: {
    init: function init() {
      fn.$socialList = $('#social_list');
      fn.$socialList.sortable({
        handle: ".item-handler",
        update: function update(event, ui) {
          //
          fn.buyer.monitor();
        }
      });
      helper.initSelectbox({
        selector: fn.$socialList.find('.social-types')
      });
      $('#add_new_social').on('click', function (e) {
        e.preventDefault();
        fn.socials.insertItem();
      });
      fn.$socialList.on('click', '.item-del', function (e) {
        e.preventDefault();
        fn.socials.deleteItem($(this).closest('li'));
      });
      $('input,textarea').on('keyup change', function () {
        fn.buyer.monitor();
      });
      fn.socials.initList();
      fn.buyer.monitor();
    },
    initList: function initList() {
      if (socials.length > 0) {
        fn.$socialList.html('');

        for (var i = 0; i < socials.length; i++) {
          var item = socials[i];
          fn.socials.insertItem({
            type: item.social_type,
            url: item.social_url
          });
        }
      }
    },
    insertItem: function insertItem(fileData) {
      var itemHtml = "<li class=\"row\">\n                                <div class=\"col-md-3 col-sm-3\" style=\"padding-top: 24px;\">\n                                    <a href=\"#\" class=\"command item-handler\" title=\"Move\"><i class=\"fa fa-arrows-alt\"></i></a>\n                                    <select name=\"social_names[]\" class=\"social-types\">\n                                        <option></option>\n                                        <option class=\"linkedin\" value=\"linkedin\">LinkedIn</option>\n                                        <option class=\"facebook\" value=\"facebook\">Facebook</option>\n                                        <option class=\"twitter\" value=\"twitter\">Twitter</option>\n                                        <option class=\"youtube\" value=\"youtube\">Youtube</option>\n                                        <option class=\"pinterest\" value=\"pinterest\">Pinterest</option>\n                                        <option class=\"instagram\" value=\"instagram\">Instagram</option>\n                                    </select>\n                                </div>\n                                <div class=\"col-md-9 col-sm-9\">\n                                    <input type=\"text\" class=\"form-control fancy input-social-url\" name=\"social_urls[]\" placeholder=\"Social Media\">\n                                    <a href=\"#\" class=\"command item-del\" title=\"Delete\"><i class=\"fa fa-trash\" aria-hidden=\"true\"></i></a>\n                                </div>\n                            </li>";
      var $obj = $(itemHtml);

      if (fileData) {
        $obj.find('.social-types').val(fileData.type);
        $obj.find('.input-social-url').val(fileData.url);
      }

      $obj.appendTo(fn.$socialList);
      helper.initSelectbox({
        selector: $obj.find('.social-types')
      });
      fn.buyer.monitor();
    },
    deleteItem: function deleteItem($obj) {
      $obj.remove();
      fn.buyer.monitor();
    }
  },
  init: function init() {
    fn.socials.init();
    fn.buyer.init();
  }
};
fn.init();

/***/ }),

/***/ 3:
/*!********************************************************!*\
  !*** multi ./resources/js/pages/buyer-profile-edit.js ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! F:\projects\microacquire\build\microacq\resources\js\pages\buyer-profile-edit.js */"./resources/js/pages/buyer-profile-edit.js");


/***/ })

/******/ });
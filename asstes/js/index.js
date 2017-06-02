webpackJsonp([0],{

/***/ 0:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _vue = __webpack_require__(3);

var _vue2 = _interopRequireDefault(_vue);

var _router = __webpack_require__(11);

var _router2 = _interopRequireDefault(_router);

var _app = __webpack_require__(14);

var _app2 = _interopRequireDefault(_app);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

window.$ = window.jQuery = __webpack_require__(13);
__webpack_require__(12);

var app = new _vue2.default({
  router: _router2.default,
  el: '#app',
  render: function render(h) {
    return h(_app2.default);
  }
});

/***/ }),

/***/ 11:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _vue = __webpack_require__(3);

var _vue2 = _interopRequireDefault(_vue);

var _vueRouter = __webpack_require__(45);

var _vueRouter2 = _interopRequireDefault(_vueRouter);

var _List = __webpack_require__(42);

var _List2 = _interopRequireDefault(_List);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

_vue2.default.use(_vueRouter2.default);

// components.


var router = new _vueRouter2.default({
  mode: 'hash',
  routes: [
  // root.
  {
    path: '/',
    redirect: 'list'
  },
  // Setting router.
  {
    path: '/list',
    component: _List2.default
  }]
});

exports.default = router;

/***/ }),

/***/ 14:
/***/ (function(module, exports, __webpack_require__) {


/* styles */
__webpack_require__(37)

var Component = __webpack_require__(9)(
  /* script */
  null,
  /* template */
  __webpack_require__(43),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "G:\\MyWork\\project\\thinksns-plus\\resources\\repositorie\\sources\\plus-component-pc\\public\\app.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] app.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-0c6093a2", Component.options)
  } else {
    hotAPI.reload("data-v-0c6093a2", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),

/***/ 33:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _request = __webpack_require__(34);

var _request2 = _interopRequireDefault(_request);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

//  const SmsMainComponent = {
//    data: () => ({
//      search: {
//        state: -1,
//        keyword: '',
//      },
//      loading: false,
//      logs: [],
//      error: null,
//      currentPage: 1,
//    }),
//    methods: {
//      nextPage() {
//        this.requestLogs(parseInt(this.currentPage) + 1);
//      },
//      prevPage() {
//        this.requestLogs(parseInt(this.currentPage) - 1);
//      },
//      searchHandle() {
//        this.requestLogs(1);
//      },
//      dismisError() {
//        this.error = null;
//      },
//      changeState(state) {
//        this.search.after = [];
//        this.search.state = state;
//      },
//      requestLogs(page) {
//        let params = {
//          page
//        };
//
//        if (this.search.state >= 0) {
//          params['state'] = this.search.state;
//        }
//
//        if (this.search.keyword.length) {
//          params['phone'] = this.search.keyword;
//        }
//
//        this.loading = true;
//
//        request.get(
//          createRequestURI('sms'), {
//            params,
//            validateStatus: status => status === 200
//          }
//        ).then(({
//          data = {}
//        }) => {
//          this.loading = false;
//
//          const {
//            current_page: currentPage = 1,
//            data: logs = []
//          } = data;
//
//          if (!logs.length) {
//            this.error = '没有数据可加载';
//            return;
//          }
//
//          this.currentPage = currentPage;
//          this.logs = logs;
//
//        }).catch();
//      }
//    },
//    created() {
//      this.searchHandle();
//    }
//  };

exports.default = {
  data: function data() {
    return {
      loading: false,
      error: null,
      search: {
        state: -1,
        key: ''
      },
      currentPage: 1,
      news: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15]
    };
  },
  methods: {
    dismisError: function dismisError() {
      this.error = null;
    }
  }
}; //
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/***/ }),

/***/ 34:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.createAPI = exports.createRequestURI = undefined;

var _axios = __webpack_require__(15);

var _axios2 = _interopRequireDefault(_axios);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var _window$PC = window.PC,
    baseURL = _window$PC.baseURL,
    api = _window$PC.api; // This "NEWS" variable is set from the global variables in the template.

// Export a method to create the requested address.

var createRequestURI = exports.createRequestURI = function createRequestURI(PATH) {
    return baseURL + '/' + PATH;
};

// Created the request address of API.
var createAPI = exports.createAPI = function createAPI(PATH) {
    return api + '/' + PATH;
};

_axios2.default.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

var token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    _axios2.default.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

exports.default = _axios2.default;

// ...
// Read the https://github.com/mzabriskie/axios

/***/ }),

/***/ 37:
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 38:
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 42:
/***/ (function(module, exports, __webpack_require__) {


/* styles */
__webpack_require__(38)

var Component = __webpack_require__(9)(
  /* script */
  __webpack_require__(33),
  /* template */
  __webpack_require__(44),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "G:\\MyWork\\project\\thinksns-plus\\resources\\repositorie\\sources\\plus-component-pc\\public\\component\\pc\\List.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] List.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-edd792ee", Component.options)
  } else {
    hotAPI.reload("data-v-edd792ee", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),

/***/ 43:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', [_c('ul', {
    staticClass: "nav nav-tabs component-controller-nav"
  }, [_c('router-link', {
    attrs: {
      "to": "/",
      "tag": "li",
      "active-class": "active"
    }
  }, [_c('a', {
    attrs: {
      "href": "#"
    }
  }, [_vm._v("认证管理")])])], 1), _vm._v(" "), _c('router-view')], 1)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-0c6093a2", module.exports)
  }
}

/***/ }),

/***/ 44:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "component-container container-fluid"
  }, [_c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.error),
      expression: "error"
    }],
    staticClass: "alert alert-danger alert-dismissible",
    attrs: {
      "role": "alert"
    }
  }, [_c('button', {
    staticClass: "close",
    attrs: {
      "type": "button"
    },
    on: {
      "click": function($event) {
        $event.preventDefault();
        _vm.dismisError($event)
      }
    }
  }, [_c('span', {
    attrs: {
      "aria-hidden": "true"
    }
  }, [_vm._v("×")])]), _vm._v("\n    " + _vm._s(_vm.error) + "\n  ")]), _vm._v(" "), _c('div', {
    staticClass: "panel panel-default"
  }, [_c('div', {
    staticClass: "panel-heading"
  }, [_c('div', {
    staticClass: "row"
  }, [_vm._m(0), _vm._v(" "), _vm._m(1), _vm._v(" "), _c('div', {
    staticClass: "col-xs-2 col-xs-offset-4 text-right"
  }, [_c('ul', {
    staticClass: "pagination",
    staticStyle: {
      "margin": "0"
    }
  }, [_c('li', {
    class: parseInt(this.currentPage) <= 1 ? 'disabled' : null
  }, [_c('a', {
    attrs: {
      "href": "#",
      "aria-label": "Previous"
    },
    on: {
      "click": function($event) {
        $event.stopPropagation();
        $event.preventDefault();
        _vm.prevPage($event)
      }
    }
  }, [_c('span', {
    attrs: {
      "aria-hidden": "true"
    }
  }, [_vm._v("<<")])])]), _vm._v(" "), _c('li', [_c('a', {
    attrs: {
      "href": "#",
      "aria-label": "Next"
    },
    on: {
      "click": function($event) {
        $event.stopPropagation();
        $event.preventDefault();
        _vm.nextPage($event)
      }
    }
  }, [_c('span', {
    attrs: {
      "aria-hidden": "true"
    }
  }, [_vm._v(">>")])])])])])])]), _vm._v(" "), _c('table', {
    staticClass: "table table-hove text-center table-striped"
  }, [_vm._m(2), _vm._v(" "), _c('tbody', _vm._l((_vm.news), function(news) {
    return _c('tr', {
      key: news.id
    }, [_c('td', [_vm._v(_vm._s("用户ID"))]), _vm._v(" "), _c('td', [_vm._v(_vm._s("用户名"))]), _vm._v(" "), _c('td', [_vm._v(_vm._s("真实姓名"))]), _vm._v(" "), _c('td', [_vm._v(_vm._s("身份证号"))]), _vm._v(" "), _c('td', [_vm._v(_vm._s("联系方式"))]), _vm._v(" "), _c('td', [_vm._v(_vm._s("认证补充"))]), _vm._v(" "), _c('td', [_vm._v(_vm._s("认证资料"))]), _vm._v(" "), _c('td', [_vm._v(_vm._s("认证状态"))]), _vm._v(" "), _c('td', [_c('router-link', {
      staticClass: "btn btn-primary btn-sm",
      attrs: {
        "type": "button",
        "to": "/news/add_recommend"
      }
    }, [_vm._v("审核")])], 1)])
  }))])])])
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "col-xs-3"
  }, [_c('div', {
    staticClass: "input-group"
  }, [_c('input', {
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "placeholder": "用户ID"
    }
  }), _vm._v(" "), _c('span', {
    staticClass: "input-group-btn"
  }, [_c('button', {
    staticClass: "btn btn-default",
    attrs: {
      "type": "button"
    }
  }, [_vm._v("搜索")])])])])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "col-xs-3"
  }, [_c('div', {
    staticClass: "form-group rs"
  }, [_c('label', {
    staticClass: "radio-inline"
  }, [_c('input', {
    attrs: {
      "type": "radio",
      "name": "optionsRadios",
      "value": "all"
    }
  }), _vm._v("全部\n            ")]), _vm._v(" "), _c('label', {
    staticClass: "radio-inline"
  }, [_c('input', {
    attrs: {
      "type": "radio",
      "name": "optionsRadios",
      "value": "done"
    }
  }), _vm._v("已审核\n            ")]), _vm._v(" "), _c('label', {
    staticClass: "radio-inline"
  }, [_c('input', {
    attrs: {
      "type": "radio",
      "name": "optionsRadios",
      "value": "will"
    }
  }), _vm._v("未审核\n            ")]), _vm._v(" "), _c('label', {
    staticClass: "radio-inline"
  }, [_c('input', {
    attrs: {
      "type": "radio",
      "name": "optionsRadios",
      "value": "drafe"
    }
  }), _vm._v("草稿\n            ")])])])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('thead', [_c('tr', [_c('th', [_vm._v("用户ID")]), _vm._v(" "), _c('th', [_vm._v("用户名")]), _vm._v(" "), _c('th', [_vm._v("真实姓名")]), _vm._v(" "), _c('th', [_vm._v("身份证号")]), _vm._v(" "), _c('th', [_vm._v("联系方式")]), _vm._v(" "), _c('th', [_vm._v("认证补充")]), _vm._v(" "), _c('th', [_vm._v("认证资料")]), _vm._v(" "), _c('th', [_vm._v("认证状态")]), _vm._v(" "), _c('th', [_vm._v("操作")])])])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-edd792ee", module.exports)
  }
}

/***/ })

},[0]);
//# sourceMappingURL=index.js.map
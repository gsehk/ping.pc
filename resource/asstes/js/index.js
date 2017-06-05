webpackJsonp([1],[
/* 0 */,
/* 1 */,
/* 2 */,
/* 3 */,
/* 4 */,
/* 5 */,
/* 6 */,
/* 7 */,
/* 8 */,
/* 9 */
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
/* 10 */,
/* 11 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _vue = __webpack_require__(3);

var _vue2 = _interopRequireDefault(_vue);

var _vueRouter = __webpack_require__(49);

var _vueRouter2 = _interopRequireDefault(_vueRouter);

var _AuthList = __webpack_require__(44);

var _AuthList2 = _interopRequireDefault(_AuthList);

var _Report = __webpack_require__(45);

var _Report2 = _interopRequireDefault(_Report);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

// components.
_vue2.default.use(_vueRouter2.default);

var router = new _vueRouter2.default({
  mode: 'hash',
  routes: [
  // root.
  {
    path: '/',
    redirect: 'authlist'
  },
  // Setting router.
  {
    path: '/authlist',
    component: _AuthList2.default
  }, {
    path: '/report',
    component: _Report2.default
  }]
});

exports.default = router;

/***/ }),
/* 12 */,
/* 13 */,
/* 14 */
/***/ (function(module, exports, __webpack_require__) {


/* styles */
__webpack_require__(40)

var Component = __webpack_require__(2)(
  /* script */
  null,
  /* template */
  __webpack_require__(48),
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
/* 15 */,
/* 16 */,
/* 17 */,
/* 18 */,
/* 19 */,
/* 20 */,
/* 21 */,
/* 22 */,
/* 23 */,
/* 24 */,
/* 25 */,
/* 26 */,
/* 27 */,
/* 28 */,
/* 29 */,
/* 30 */,
/* 31 */,
/* 32 */,
/* 33 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; //
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

var _request = __webpack_require__(9);

var _request2 = _interopRequireDefault(_request);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
  data: function data() {
    return {
      loading: false,
      error: null,
      auditID: '',
      state: [{
        value: '',
        label: '全部'
      }, {
        value: -1,
        label: '未认证'
      }, {
        value: 1,
        label: '成功'
      }, {
        value: 2,
        label: '失败'
      }],
      search: {
        state: '',
        key: ''
      },
      currentPage: 1,
      auth: []
    };
  },
  created: function created() {
    this.getAuthList();
  },

  methods: {
    dismisError: function dismisError() {
      this.error = null;
    },
    getAuthList: function getAuthList() {
      var _this = this;

      this.loadding = true;
      _request2.default.get('/pc/admin/auth').then(function (response) {
        _this.auth = response.data.data;
        _this.loadding = false;
      }).catch(function (_ref) {
        var _ref$response = _ref.response;
        _ref$response = _ref$response === undefined ? {} : _ref$response;
        var _ref$response$data = _ref$response.data;
        _ref$response$data = _ref$response$data === undefined ? {} : _ref$response$data;
        var _ref$response$data$er = _ref$response$data.errors,
            errors = _ref$response$data$er === undefined ? ['加载数据失败'] : _ref$response$data$er;

        _this.loadding = false;
      });
    },
    manageAuth: function manageAuth(auth) {
      var _this2 = this;

      var state = '',
          _id = auth.id;
      switch (auth.verified) {
        case 0:
          state = 1;
          break;
        case 1:
          state = 2;
          break;
        case 2:
          state = 1;
          break;
        default:
          state = 2;
          break;
      }
      _request2.default.post('/pc/admin/auth/audit/' + _id, {
        state: state
      }).then(function (response) {
        if (response.data.status) {
          _this2.auditID = null;
          _this2.getAuthList();
        }
      }).catch(function (_ref2) {
        var _ref2$response = _ref2.response;
        _ref2$response = _ref2$response === undefined ? {} : _ref2$response;
        var _ref2$response$data = _ref2$response.data;
        _ref2$response$data = _ref2$response$data === undefined ? {} : _ref2$response$data;
        var _ref2$response$data$e = _ref2$response$data.errors,
            errors = _ref2$response$data$e === undefined ? ['删除失败'] : _ref2$response$data$e;

        _this2.deleteID = null;
      });
    },
    verified2label: function verified2label(v) {
      // 认证状态，0：未认证；1：成功 2 :  失败
      switch (v) {
        case 0:
          return '未认证';
        case 1:
          return '成功';
        case 2:
          return '失败';
        default:
          return '未认证';
      }
    },
    verified2stye: function verified2stye(v) {
      // 认证状态，0：未认证；1：成功 2 :  失败
      switch (v) {
        case 0:
          return 'info';
        case 1:
          return '';
        case 2:
          return 'danger';
        default:
          return 'info';
      }
    },
    _search: function _search() {
      var _this3 = this;

      this.loadding = true;
      this.auth = null;
      _request2.default.get('/pc/admin/auth', {
        params: _extends({}, this.search)
      }).then(function (response) {
        _this3.auth = response.data.data;
        _this3.loadding = false;
      }).catch(function (_ref3) {
        var _ref3$response = _ref3.response;
        _ref3$response = _ref3$response === undefined ? {} : _ref3$response;
        var _ref3$response$data = _ref3$response.data;
        _ref3$response$data = _ref3$response$data === undefined ? {} : _ref3$response$data;
        var _ref3$response$data$e = _ref3$response$data.errors,
            errors = _ref3$response$data$e === undefined ? ['加载数据失败'] : _ref3$response$data$e;

        _this3.loadding = false;
      });
    }
  }
};

/***/ }),
/* 34 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; //
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

var _request = __webpack_require__(9);

var _request2 = _interopRequireDefault(_request);

var _Page = __webpack_require__(62);

var _Page2 = _interopRequireDefault(_Page);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
  name: 'report',
  components: {
    Page: _Page2.default
  },
  data: function data() {
    return {
      loading: false,
      tips: {
        error: null,
        success: null
      },
      auditID: '',
      state: [{
        value: -1,
        label: '未处理'
      }, {
        value: 1,
        label: '已处理'
      }],
      search: {
        state: '',
        key: ''
      },
      current_page: 1,
      last_page: 0,
      report: []
    };
  },
  methods: {
    dismisError: function dismisError() {
      this.tips.error = null;
      this.tips.success = null;
    },
    getReportList: function getReportList(page) {
      var _this = this;

      this.dismisError();
      this.loadding = true;
      _request2.default.get('/pc/admin/denounce', {
        params: _extends({
          page: page || this.current_page
        }, this.search)
      }).then(function (response) {
        _this.loadding = false;
        if (!response.data.status) return _this.tips.error = '获取数据失败！';
        var _response$data$data = response.data.data,
            data = _response$data$data === undefined ? [] : _response$data$data;

        _this.report = data;
        if (!data.length) return _this.tips.error = '暂无数据！';
      }).catch(function (_ref) {
        var _ref$response = _ref.response;
        _ref$response = _ref$response === undefined ? {} : _ref$response;
        var _ref$response$data = _ref$response.data;
        _ref$response$data = _ref$response$data === undefined ? {} : _ref$response$data;
        var _ref$response$data$er = _ref$response$data.errors,
            errors = _ref$response$data$er === undefined ? ['加载数据失败'] : _ref$response$data$er;

        _this.loadding = false;
      });
    },
    managereport: function managereport(report) {
      var _this2 = this;

      var state = '',
          _id = report.id;
      switch (report.verified) {
        case 0:
          state = 1;
          break;
        case 1:
          state = 2;
          break;
        case 2:
          state = 1;
          break;
        default:
          state = 2;
          break;
      }
      _request2.default.post('/pc/admin/report/audit/' + _id, {
        state: state
      }).then(function (response) {
        if (response.data.status) {
          _this2.auditID = null;
          _this2.getReportList();
        }
      }).catch(function (_ref2) {
        var _ref2$response = _ref2.response;
        _ref2$response = _ref2$response === undefined ? {} : _ref2$response;
        var _ref2$response$data = _ref2$response.data;
        _ref2$response$data = _ref2$response$data === undefined ? {} : _ref2$response$data;
        var _ref2$response$data$e = _ref2$response$data.errors,
            errors = _ref2$response$data$e === undefined ? ['删除失败'] : _ref2$response$data$e;

        _this2.deleteID = null;
      });
    },
    state2label: function state2label(v) {
      // 处理状态，0：未处理；1：已处理 
      switch (v) {
        case 0:
          return '未处理';
        case 1:
          return '已处理';
        default:
          return '未处理';
      }
    },
    state2style: function state2style(v) {
      // 处理状态，0：未处理；1：已处理 
      switch (v) {
        case 0:
          return 'info';
        case 1:
          return '';
        default:
          return 'info';
      }
    },
    pageGo: function pageGo(page) {
      this.current_page = page;
      this.getReportList();
    }
  },
  created: function created() {
    this.getReportList();
  }
};

/***/ }),
/* 35 */
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
/* 36 */,
/* 37 */,
/* 38 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 39 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 40 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 41 */,
/* 42 */,
/* 43 */,
/* 44 */
/***/ (function(module, exports, __webpack_require__) {


/* styles */
__webpack_require__(38)

var Component = __webpack_require__(2)(
  /* script */
  __webpack_require__(33),
  /* template */
  __webpack_require__(46),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "G:\\MyWork\\project\\thinksns-plus\\resources\\repositorie\\sources\\plus-component-pc\\public\\component\\pc\\AuthList.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] AuthList.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-00f0eed1", Component.options)
  } else {
    hotAPI.reload("data-v-00f0eed1", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 45 */
/***/ (function(module, exports, __webpack_require__) {


/* styles */
__webpack_require__(39)

var Component = __webpack_require__(2)(
  /* script */
  __webpack_require__(34),
  /* template */
  __webpack_require__(47),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "G:\\MyWork\\project\\thinksns-plus\\resources\\repositorie\\sources\\plus-component-pc\\public\\component\\report\\Report.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Report.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-05562c04", Component.options)
  } else {
    hotAPI.reload("data-v-05562c04", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 46 */
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
  }, [_c('div', {
    staticClass: "col-xs-3"
  }, [_c('div', {
    staticClass: "input-group"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.search.key),
      expression: "search.key"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "placeholder": "用户名"
    },
    domProps: {
      "value": (_vm.search.key)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.search.key = $event.target.value
      }
    }
  }), _vm._v(" "), _c('span', {
    staticClass: "input-group-btn"
  }, [_c('button', {
    staticClass: "btn btn-default",
    attrs: {
      "type": "button"
    },
    on: {
      "click": _vm._search
    }
  }, [_vm._v("搜索")])])])]), _vm._v(" "), _c('div', {
    staticClass: "col-xs-3"
  }, [_c('div', {
    staticClass: "form-group rs"
  }, _vm._l((_vm.state), function(state) {
    return _c('label', {
      staticClass: "radio-inline"
    }, [_c('input', {
      directives: [{
        name: "model",
        rawName: "v-model",
        value: (_vm.search.state),
        expression: "search.state"
      }],
      attrs: {
        "type": "radio",
        "name": "optionsRadios"
      },
      domProps: {
        "value": state.value,
        "checked": _vm._q(_vm.search.state, state.value)
      },
      on: {
        "click": _vm._search,
        "__c": function($event) {
          _vm.search.state = state.value
        }
      }
    }), _vm._v(_vm._s(state.label) + "\n            ")])
  }))]), _vm._v(" "), _c('div', {
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
  }, [_vm._m(0), _vm._v(" "), _c('tbody', _vm._l((_vm.auth), function(auth) {
    return _c('tr', {
      key: auth.id,
      class: _vm.verified2stye(auth.verified)
    }, [_c('td', [_vm._v(_vm._s(auth.user_id))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(auth.realname))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(auth.user.name))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(auth.idcard))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(auth.phone))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(auth.info))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(auth.storage))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(_vm.verified2label(auth.verified)))]), _vm._v(" "), _c('td', [_c('button', {
      staticClass: "btn btn-primary btn-sm",
      attrs: {
        "type": "button",
        "disabled": auth.verified === 1
      },
      on: {
        "click": function($event) {
          _vm.manageAuth(auth)
        }
      }
    }, [_vm._v("审核")]), _vm._v(" "), _c('button', {
      staticClass: "btn btn-danger btn-sm",
      attrs: {
        "type": "button",
        "disabled": auth.verified === 2
      },
      on: {
        "click": function($event) {
          _vm.manageAuth(auth)
        }
      }
    }, [_vm._v("驳回")])])])
  }))])])])
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('thead', [_c('tr', [_c('th', [_vm._v("用户ID")]), _vm._v(" "), _c('th', [_vm._v("用户名")]), _vm._v(" "), _c('th', [_vm._v("真实姓名")]), _vm._v(" "), _c('th', [_vm._v("身份证号")]), _vm._v(" "), _c('th', [_vm._v("联系方式")]), _vm._v(" "), _c('th', [_vm._v("认证补充")]), _vm._v(" "), _c('th', [_vm._v("认证资料")]), _vm._v(" "), _c('th', [_vm._v("认证状态")]), _vm._v(" "), _c('th', [_vm._v("操作")])])])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-00f0eed1", module.exports)
  }
}

/***/ }),
/* 47 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "component-container container-fluid"
  }, [_c('transition', {
    attrs: {
      "name": "fade"
    }
  }, [(!!_vm.tips.error || !!_vm.tips.success || false) ? _c('div', {
    staticClass: "alert alert-dismissible",
    class: _vm.tips.success ? 'alert-success' : 'alert-danger'
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
  }, [_vm._v("×")])]), _vm._v(" " + _vm._s(_vm.tips.error || _vm.tips.success) + "\n    ")]) : _vm._e()]), _vm._v(" "), _c('div', {
    staticClass: "panel panel-default"
  }, [_c('div', {
    staticClass: "panel-heading"
  }, [_c('div', {
    staticClass: "row"
  }, [_c('div', {
    staticClass: "col-xs-3"
  }, [_c('div', {
    staticClass: "input-group",
    class: {
      'has-errors has-error': _vm.search.error
    }
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.search.key),
      expression: "search.key"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "placeholder": "输入关键字"
    },
    domProps: {
      "value": (_vm.search.key)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.search.key = $event.target.value
      }
    }
  }), _vm._v(" "), _c('span', {
    staticClass: "input-group-btn"
  }, [_c('input', {
    staticClass: "btn btn-default",
    attrs: {
      "type": "button",
      "value": "搜索"
    },
    on: {
      "click": function($event) {
        _vm.getReportList(1)
      }
    }
  })])])]), _vm._v(" "), _c('div', {
    staticClass: "col-xs-4"
  }, [_c('div', {
    staticClass: "form-group rs"
  }, _vm._l((_vm.state), function(state) {
    return _c('label', {
      staticClass: "radio-inline"
    }, [_c('input', {
      directives: [{
        name: "model",
        rawName: "v-model",
        value: (_vm.search.state),
        expression: "search.state"
      }],
      attrs: {
        "type": "radio",
        "name": "optionsRadios"
      },
      domProps: {
        "value": state.value,
        "checked": _vm._q(_vm.search.state, state.value)
      },
      on: {
        "click": function($event) {
          _vm.getReportList(1)
        },
        "__c": function($event) {
          _vm.search.state = state.value
        }
      }
    }), _vm._v(_vm._s(state.label) + "\n            ")])
  }))]), _vm._v(" "), _c('div', {
    staticClass: "col-xs-4 text-right"
  }, [_c('Page', {
    attrs: {
      "current": _vm.current_page,
      "last": _vm.last_page
    },
    on: {
      "pageGo": _vm.pageGo
    }
  })], 1)])]), _vm._v(" "), _c('div', {
    staticClass: "panel-bady"
  }, [_c('table', {
    staticClass: "table table-hove text-center table-striped"
  }, [_vm._m(0), _vm._v(" "), _c('tbody', _vm._l((_vm.report), function(report) {
    return _c('tr', {
      key: report.id,
      class: _vm.state2style(report.state)
    }, [_c('td', [_vm._v(_vm._s(report.user.name))]), _vm._v(" "), _c('td', [_c('a', {
      attrs: {
        "href": report.source_url,
        "target": "_bank",
        "title": "report.source_url"
      }
    }, [_vm._v("前往查看")])]), _vm._v(" "), _c('td', [_vm._v(_vm._s(report.reason))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(report.created_at))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(_vm.state2label(report.state)))]), _vm._v(" "), _c('td', [_c('button', {
      staticClass: "btn btn-primary btn-sm",
      attrs: {
        "type": "button",
        "disabled": report.verified === 1
      },
      on: {
        "click": function($event) {
          _vm.managereport(report)
        }
      }
    }, [_vm._v("审核")]), _vm._v(" "), _c('button', {
      staticClass: "btn btn-danger btn-sm",
      attrs: {
        "type": "button",
        "disabled": report.verified === 2
      },
      on: {
        "click": function($event) {
          _vm.managereport(report)
        }
      }
    }, [_vm._v("驳回")])])])
  }))])])])], 1)
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('thead', [_c('tr', [_c('th', [_vm._v("举报者")]), _vm._v(" "), _c('th', [_vm._v("资源连接")]), _vm._v(" "), _c('th', [_vm._v("举报理由")]), _vm._v(" "), _c('th', [_vm._v("举报时间")]), _vm._v(" "), _c('th', [_vm._v("处理状态")]), _vm._v(" "), _c('th', [_vm._v("操作")])])])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-05562c04", module.exports)
  }
}

/***/ }),
/* 48 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', [_c('ul', {
    staticClass: "nav nav-tabs component-controller-nav"
  }, [_c('router-link', {
    attrs: {
      "to": "/authlist",
      "tag": "li",
      "active-class": "active"
    }
  }, [_c('a', {
    attrs: {
      "href": "#"
    }
  }, [_vm._v("认证管理")])]), _vm._v(" "), _c('router-link', {
    attrs: {
      "to": "/report",
      "tag": "li",
      "active-class": "active"
    }
  }, [_c('a', {
    attrs: {
      "href": "#"
    }
  }, [_vm._v("举报管理")])])], 1), _vm._v(" "), _c('router-view')], 1)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-0c6093a2", module.exports)
  }
}

/***/ }),
/* 49 */,
/* 50 */,
/* 51 */,
/* 52 */,
/* 53 */,
/* 54 */,
/* 55 */,
/* 56 */,
/* 57 */,
/* 58 */,
/* 59 */,
/* 60 */,
/* 61 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});
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

exports.default = {
  name: 'Page',
  props: ['current', 'last'],
  computed: {
    pagination: function pagination() {
      // 当前页
      var current = 1;
      current = isNaN(current = ~~this.current) ? 1 : current;
      // 最后页码
      var last = 1;
      last = isNaN(last = ~~this.last) ? 1 : last;
      // 是否显示
      var show = last > 1;
      // 前三页
      var minPage = current - 3;
      minPage = minPage <= 1 ? 1 : minPage;
      // 后三页
      var maxPage = current + 3;
      maxPage = maxPage >= last ? last : maxPage;
      // 是否有上一页
      var isPrevPage = false;
      // 前页页码
      var prevPages = [];
      // 前页计算
      if (current > minPage) {
        // 有上一页按钮
        isPrevPage = current - 1; // 如果有，传入上一页页码.
        // 回归第一页
        if (minPage > 1) {
          prevPages.push({
            name: current < 6 ? 1 : '1...',
            page: 1
          });
        }
        // 前页码
        for (var i = minPage; i < current; i++) {
          prevPages.push({
            name: i,
            page: i
          });
        }
      }
      // 是否有下一页
      var isNextPage = false;
      // 后页页码
      var nextPages = [];
      // 后页计算
      if (current < maxPage) {
        // 后页码
        for (var _i = current + 1; _i <= maxPage; _i++) {
          nextPages.push({
            name: _i,
            page: _i
          });
        }
        // 进入最后页
        if (maxPage < last) {
          nextPages.push({
            name: current + 4 === last ? last : '...' + last,
            page: last
          });
        }
        // 是否有下一页按钮
        isNextPage = current + 1;
      }
      return {
        show: show,
        current: current,
        prevPages: prevPages,
        nextPages: nextPages,
        isNextPage: isNextPage,
        isPrevPage: isPrevPage
      };
    }
  },
  methods: {
    pageGo: function pageGo(page) {
      return page && this.$emit('pageGo', page);
    }
  }
};

/***/ }),
/* 62 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(2)(
  /* script */
  __webpack_require__(61),
  /* template */
  __webpack_require__(63),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "G:\\MyWork\\project\\thinksns-plus\\resources\\repositorie\\sources\\plus-component-pc\\public\\component\\Page.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Page.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-7ed03f90", Component.options)
  } else {
    hotAPI.reload("data-v-7ed03f90", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 63 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.pagination.show),
      expression: "pagination.show"
    }]
  }, [_c('ul', {
    staticClass: "pagination ma0"
  }, [_c('li', [_c('a', {
    attrs: {
      "href": "#",
      "aria-label": "Previous",
      "disabled": !!!_vm.pagination.isPrevPage
    },
    on: {
      "click": function($event) {
        $event.preventDefault();
        $event.stopPropagation();
        _vm.pageGo(_vm.pagination.isPrevPage)
      }
    }
  }, [_c('span', {
    attrs: {
      "aria-hidden": "true"
    }
  }, [_vm._v("«")])])]), _vm._v(" "), _vm._l((_vm.pagination.prevPages), function(item) {
    return _c('li', {
      key: item.page
    }, [_c('a', {
      attrs: {
        "href": "#"
      },
      on: {
        "click": function($event) {
          $event.preventDefault();
          $event.stopPropagation();
          _vm.pageGo(item.page)
        }
      }
    }, [_vm._v(_vm._s(item.name))])])
  }), _vm._v(" "), _c('li', {
    staticClass: "active"
  }, [_c('a', {
    attrs: {
      "href": "#"
    },
    on: {
      "click": function($event) {
        $event.preventDefault();
        $event.stopPropagation();
        _vm.pageGo(_vm.pagination.current)
      }
    }
  }, [_vm._v(_vm._s(_vm.pagination.current))])]), _vm._v(" "), _vm._l((_vm.pagination.nextPages), function(item) {
    return _c('li', {
      key: item.page
    }, [_c('a', {
      attrs: {
        "href": "#"
      },
      on: {
        "click": function($event) {
          $event.preventDefault();
          $event.stopPropagation();
          _vm.pageGo(item.page)
        }
      }
    }, [_vm._v(_vm._s(item.name))])])
  }), _vm._v(" "), _c('li', [_c('a', {
    attrs: {
      "href": "#",
      "aria-label": "Next",
      "disabled": !!!_vm.pagination.isNextPage
    },
    on: {
      "click": function($event) {
        $event.preventDefault();
        $event.stopPropagation();
        _vm.pageGo(_vm.pagination.isNextPage)
      }
    }
  }, [_c('span', {
    attrs: {
      "aria-hidden": "true"
    }
  }, [_vm._v("»")])])])], 2)])
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-7ed03f90", module.exports)
  }
}

/***/ })
],[35]);
//# sourceMappingURL=index.js.map
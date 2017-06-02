webpackJsonp([1],{

/***/ 10:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _vue = __webpack_require__(2);

var _vue2 = _interopRequireDefault(_vue);

var _vueRouter = __webpack_require__(45);

var _vueRouter2 = _interopRequireDefault(_vueRouter);

var _AuthList = __webpack_require__(42);

var _AuthList2 = _interopRequireDefault(_AuthList);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

_vue2.default.use(_vueRouter2.default);

// components.


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
  }]
});

exports.default = router;

/***/ }),

/***/ 13:
/***/ (function(module, exports, __webpack_require__) {


/* styles */
__webpack_require__(38)

var Component = __webpack_require__(8)(
  /* script */
  null,
  /* template */
  __webpack_require__(44),
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

/***/ 32:
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

var _request = __webpack_require__(34);

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

/***/ 33:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _vue = __webpack_require__(2);

var _vue2 = _interopRequireDefault(_vue);

var _router = __webpack_require__(10);

var _router2 = _interopRequireDefault(_router);

var _app = __webpack_require__(13);

var _app2 = _interopRequireDefault(_app);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

window.$ = window.jQuery = __webpack_require__(12);
__webpack_require__(11);

var app = new _vue2.default({
  router: _router2.default,
  el: '#app',
  render: function render(h) {
    return h(_app2.default);
  }
});

/***/ }),

/***/ 34:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.createAPI = exports.createRequestURI = undefined;

var _axios = __webpack_require__(14);

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
__webpack_require__(37)

var Component = __webpack_require__(8)(
  /* script */
  __webpack_require__(32),
  /* template */
  __webpack_require__(43),
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

/***/ 43:
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

/***/ 44:
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

/***/ })

},[33]);
//# sourceMappingURL=index.js.map
<template>
  <div class="component-container container-fluid">
    <!-- error -->
    <div v-show="error" class="alert alert-danger alert-dismissible" role="alert">
      <button type="button" class="close" @click.prevent="dismisError">
        <span aria-hidden="true">&times;</span>
      </button>
      {{ error }}
    </div>
    <!-- 认证列表面板 -->
    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="row">
          <div class="col-xs-3">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="用户名" v-model='search.key'>
              <span class="input-group-btn">
                <button class="btn btn-default" type="button" @click='_search'>搜索</button>
              </span>
            </div>
          </div>
          <div class="col-xs-3">
            <div class="form-group rs">
              <label class="radio-inline" v-for='state in state'>
                <input type="radio" name="optionsRadios" v-model='search.state' @click='_search' :value="state.value">{{state.label}}
              </label>
            </div>
          </div>
          <div class="col-xs-2 col-xs-offset-4 text-right">
            <ul class="pagination" style="margin: 0;">
              <li :class="parseInt(this.currentPage) <= 1 ? 'disabled' : null">
                <a href="#" aria-label="Previous" @click.stop.prevent="prevPage">
                  <span aria-hidden="true"><<</span>
                </a>
              </li>
              <li>
                <a href="#" aria-label="Next" @click.stop.prevent="nextPage">
                  <span aria-hidden="true">>></span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <!-- Table -->
      <table class="table table-hove text-center table-striped">
        <thead>
          <tr>
            <th>用户ID</th>
            <th>用户名</th>
            <th>真实姓名</th>
            <th>身份证号</th>
            <th>联系方式</th>
            <th>认证补充</th>
            <th>认证资料</th>
            <th>认证状态</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="auth in auth" :key="auth.id" :class="verified2stye(auth.verified)">
            <td>{{ auth.user_id }}</td>
            <td>{{ auth.realname }}</td>
            <td>{{ auth.user.name }}</td>
            <td>{{ auth.idcard }}</td>
            <td>{{ auth.phone }}</td>
            <td>{{ auth.info }}</td>
            <td>{{ auth.storage }}</td>
            <td>{{ verified2label(auth.verified) }}</td>
            <td>
              <!-- 审核 -->
              <button type="button" class="btn btn-primary btn-sm" :disabled='auth.verified === 1' @click='manageAuth(auth)'>审核</button>
              <!-- 驳回 -->
              <button type="button" class="btn btn-danger btn-sm" :disabled='auth.verified === 2' @click='manageAuth(auth)'>驳回</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
  import request, {
    createRequestURI
  } from '../../util/request';

  export default {
    data: () => ({
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
    }),
    created() {
      this.getAuthList()
    },
    methods: {
      dismisError() {
        this.error = null;
      },
      getAuthList() {
        this.loadding = true;
        request.get('/pc/admin/auth').then(response => {
          this.auth = response.data.data;
          this.loadding = false;
        }).catch(({
          response: {
            data: {
              errors = ['加载数据失败']
            } = {}
          } = {}
        }) => {
          this.loadding = false;
        });
      },
      manageAuth(auth) {
        let state = '',
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
        request.post('/pc/admin/auth/audit/' + _id, {
            state
          })
          .then(response => {
            if (response.data.status) {
              this.auditID = null;
              this.getAuthList();
            }
          }).catch(({
            response: {
              data: {
                errors = ['删除失败']
              } = {}
            } = {}
          }) => {
            this.deleteID = null;
          });
      },
      verified2label(v) {
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
      verified2stye(v) {
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
      _search() {
        this.loadding = true;
        this.auth = null;
        request.get('/pc/admin/auth', {
          params: {
            ...this.search
          }
        }).then(response => {
          this.auth = response.data.data;
          this.loadding = false;
        }).catch(({
          response: {
            data: {
              errors = ['加载数据失败']
            } = {}
          } = {}
        }) => {
          this.loadding = false;
        });
      },
    }
  }

</script>

<style>
  .table th {
    text-align: center;
  }
  
  .mb0 {
    margin-bottom: 5px !important;
  }
  
  .rs {
    margin-top: 7px;
    margin-bottom: 0px;
    /*    margin-left: -5%;*/
  }
  
  .loadding {
    text-align: center;
    font-size: 42px;
  }
  
  .loaddingIcon {
    animation-name: "TurnAround";
    animation-duration: 1.4s;
    animation-timing-function: linear;
    animation-iteration-count: infinite;
  }
  
  .has-errors input {
    border-color: #a94442;
    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
  }

</style>

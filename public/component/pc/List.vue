<template>
  <div class="component-container container-fluid">
    <!-- error -->
    <div v-show="error" class="alert alert-danger alert-dismissible" role="alert">
      <button type="button" class="close" @click.prevent="dismisError">
        <span aria-hidden="true">&times;</span>
      </button>
      {{ error }}
    </div>
    <!-- 资讯列表面板 -->
    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="row">
          <div class="col-xs-3">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="用户ID">
              <span class="input-group-btn">
                <button class="btn btn-default" type="button">搜索</button>
              </span>
            </div>
          </div>
          <div class="col-xs-3">
            <div class="form-group rs">
              <label class="radio-inline">
                <input type="radio" name="optionsRadios" value="all">全部
              </label>
              <label class="radio-inline">
                <input type="radio" name="optionsRadios" value="done">已审核
              </label>
              <label class="radio-inline">
                <input type="radio" name="optionsRadios" value="will">未审核
              </label>
              <label class="radio-inline">
                <input type="radio" name="optionsRadios" value="drafe">草稿
              </label>
            </div>
          </div>
          <!-- pager -->
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
          <tr v-for="news in news" :key="news.id">
            <td>{{ "用户ID" }}</td>
            <td>{{ "用户名" }}</td>
            <td>{{ "真实姓名" }}</td>
            <td>{{ "身份证号" }}</td>
            <td>{{ "联系方式" }}</td>
            <td>{{ "认证补充" }}</td>
            <td>{{ "认证资料" }}</td>
            <td>{{ "认证状态" }}</td>
            <td>
              <!-- 编辑 -->
              <router-link type="button" class="btn btn-primary btn-sm" to="/news/add_recommend">审核</router-link>
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

  export default {
    data: () => ({
      loading: false,
      error: null,
      search: {
        state: -1,
        key: '',
      },
      currentPage: 1,
      news: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14,15]
    }),
    methods: {
      dismisError() {
        this.error = null;
      }
    }
  }

</script>

<style>
  .table th {
    text-align: center;
  }
  .mb0{
    margin-bottom: 5px !important;
  }
  .rs{
    margin-top: 7px; 
    margin-bottom: 0px;
/*    margin-left: -5%;*/
  }
</style>

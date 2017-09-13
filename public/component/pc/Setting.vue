<template>
  <div class="component-container container-fluid">
    <div class="panel panel-default">
      <!-- Title -->
      <div class="panel-heading">基础配置</div>
            <!-- Loading -->
      <div v-if="loadding.state === 0" class="panel-body text-center">
        <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
        加载中...
      </div>
      <!-- Body -->
      <div v-else-if="loadding.state === 1" class="panel-body form-horizontal">
        <div class="form-group">
          <label class="col-sm-2 control-label" for="url">站点logo</label>
          <div class="col-sm-4">
            <Upload :imgs='site.logo' @getTask_id="getlogo_id" @updata='updataImg' />
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="position">二维码</label>
          <div class="col-sm-4">
              <Upload :imgs='site.qrcode' @getTask_id="getqrcode_id" @updata='updataImg' />
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="name">网站欢迎语</label>
          <div class="col-sm-6">
            <input type="text" name="name" class="form-control" id="name" v-model="site.greet">
          </div>
        </div>
        <!-- button -->
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-4">
            <button v-if="submit.state === true" class="btn btn-primary" type="submit" disabled="disabled">
              <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
              提交...
            </button>
            <button v-else type="button" class="btn btn-primary" @click.stop.prevent="submitHandle">提交</button>
          </div>
          <div class="col-sm-6 help-block">
            <span :class="`text-${submit.type}`">{{ submit.message }}</span>
          </div>
        </div>
      </div>
      <!-- Loading Error -->
      <div v-else class="panel-body">
        <div class="alert alert-danger" role="alert">{{ loadding.message }}</div>
        <button type="button" class="btn btn-primary" @click.stop.prevent="request">刷新</button>
      </div>
    </div>
  </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';
import Upload from '../Upload_v2';

const NavmanageComponent = {
  components: {
      Upload,
  },
  data: () => ({
    loadding: {
      state: 0,
      message: '',
    },
    submit: {
      state: false,
      type: 'muted',
      message: '',
    },
    site: {
      logo: '',
      qrcode: '',
      greet: '欢迎使用TS+',
    }
  }),
  methods: {
	requestSiteInfo () {
      request.get(createRequestURI('site/baseinfo'), {
        validateStatus: status => status === 200
      }).then(({ data = {} }) => {
        this.site = data;
        this.loadding.state = 1;
      }).catch(({ response: { data: { message = '加载失败' } = {} } = {} }) => {
        this.loadding.state = 0;
        window.alert(message);
      });
    },
    submitHandle() {
      this.submit.state = true;
      request.patch(
        createRequestURI('site/baseinfo'),
        this.site,
        { validateStatus: status => status === 201 }
      ).then(({ message = '操作成功' }) => {
        console.log(message)
        this.submit.state = false;
        this.submit.type = 'success';
        this.submit.message = message;
      }).catch(({ response: { message: [ message = '提交失败' ] = [] } = {} }) => {
        this.submit.state = false;
        this.submit.type = 'danger';
        this.submit.message = message;
      });
    },
    // 获取图片ID || 图片上传任务ID
    getlogo_id(task_id) {
        this.site.logo = task_id;
    },
    getqrcode_id (task_id) {
      this.site.qrcode = task_id;
    },
    // 清除图片ID || 任务ID
    updataImg() {
        this.image = null;
    },
  },
	created() {
		this.requestSiteInfo();
	}
};

export default NavmanageComponent;
</script>

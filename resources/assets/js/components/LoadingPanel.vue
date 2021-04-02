<template>
  <div class="row" v-loading="true" id="loading"></div>
</template>
<script>
import Vue from 'vue';
import { Loading } from 'element-ui';

Vue.use(Loading.directive);
export default {};
</script>
<style>
#loading {
  min-height: 200px;
  display: flex;
  align-items: center;
}

.el-loading-spinner .path {
  stroke: #66615b !important;
}

.el-loading-mask {
  background: transparent !important;
}
</style>

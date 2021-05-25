<template>
  <div>
    <h1 class="text-white">{{ translations.finish.title }}</h1>
    <div class="card">
      <div class="card-header wizard-header p-3">
        <el-steps :active="active" finish-status="success" align-center>
          <el-step :title="translations.company.title"></el-step>
          <el-step :title="translations.currencies.title"></el-step>
          <el-step :title="translations.taxes.title"></el-step>
          <el-step :title="translations.finish.title"></el-step>
        </el-steps>
      </div>
      <div class="card-body bg-default">
        <div class="row">
          <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="content-header">
              <h3 class="text-white">{{ translations.finish.recommended_apps }}</h3>
            </div>
            <div class="row">
              <div v-for="(item, index) in modules" :key="index" class="col-md-3">
                <div class="card">
                  <div class="card-header py-2">
                    <h4 class="ml--3 mb-0 float-left">
                      <a
                        :href="item.slug"
                        >{{ item.name }}</a
                      >
                    </h4>
                  </div>
                  <a :href="route_url + '/' + item.slug"
                    ><img
                      v-for="(file, indis) in item.files" :key="indis"
                      v-if="file.media_type == 'image' && file.pivot.zone == 'thumbnail'"
                      :src="file.path_string"
                      :alt="item.name"
                      class="card-img-top border-radius-none"
                  /></a>
                  <div class="card-footer py-2">
                    <div class="float-left ml--3 mt--1">
                      <i v-for="(stars, indis) in item.vote" :key="indis" class="fa fa-star text-xs text-yellow"></i>
                      <small class="text-xs"> {{ item.total_review }} </small>
                    </div>
                    <div class="float-right mr--3">
                      <small><strong> {{ item.price }} </strong></small>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-12"><ul></ul></div>
          </div>
        </div>
      </div>
      <div class="card-footer">
        <div class="row">
          <div class="col-md-12 d-flex justify-content-between">
            <base-button type="white" native-type="submit" @click="prev()"
              >{{ translations.finish.previous }}</base-button
            >
            <base-button type="success" native-type="submit" @click="finish()"
              >{{ translations.finish.go_to_dashboard }}</base-button
            >
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

import { Step, Steps } from "element-ui";

export default {
  name: "Finish",
  components: {
    [Step.name]: Step,
    [Steps.name]: Steps,
  },
  created() {
    window.axios({
      method: 'GET',
      url
    }).then(response => {

    }).catch(error => {
      
    });
  },
  props: {
    modules: {
      type: [Object, Array]
    },
    translations: {
      type: [Object, Array]
    }
  },
  data() {
    return {
      active: 3,
      route_url: url,
    };
  },
  methods: {
    finish() {
      window.location.href = "";
    },
    prev() {
      if (this.active-- > 2);
      this.$router.push("/wizard/taxes");
    },
  },
};
</script>

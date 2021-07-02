/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

 require('./../../bootstrap');

 import Vue from 'vue';
 
 import DashboardPlugin from './../../plugins/dashboard-plugin';
 
 import Global from './../../mixins/global';
 
 import Form from './../../plugins/form';
 import BulkAction from './../../plugins/bulk-action';
 
 // plugin setup
 Vue.use(DashboardPlugin);
 
 const app = new Vue({
     el: '#app',
 
     mixins: [
         Global
     ],
 
     data: function () {
         return {
             form: new Form('item'),
             bulk_action: new BulkAction('items'),
             regex_condition: [
                 '..',
                 '.,',
                 ',.',
                 ',,'
             ],
             splice_value: null
         }
     },
     
     watch: {
         'form.sale_price': function (newVal, oldVal) {
             if (newVal != '' && newVal.search('^(?=.*?[0-9])[0-9.,]+$') == -1) {
                 this.form.sale_price = oldVal;
             }
 
             if (newVal.search('^(?=.*?[0-9])[0-9.,]+$') == 0) {
                 for (let item of this.regex_condition) {
                     if (this.form.sale_price.includes(item)) {
                        this.splice_value = this.form.sale_price.replace(item, '');
                        this.form.sale_price = this.splice_value;
                     }
                 }
             }
         },
 
         'form.purchase_price': function (newVal, oldVal) {
             if (newVal != '' && newVal.search('^(?=.*?[0-9])[0-9.,]+$') == -1) {
                 this.form.purchase_price = oldVal;
             }

             if (newVal.search('^(?=.*?[0-9])[0-9.,]+$') == 0) {
                for (let item of this.regex_condition) {
                    if (this.form.purchase_price.includes(item)) {
                        this.splice_value = this.form.purchase_price.replace(item, '');
                       this.form.purchase_price = this.splice_value;
                    }
                }
            }
         }
     },
 });
 
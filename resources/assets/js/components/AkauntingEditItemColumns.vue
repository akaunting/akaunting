<template>
    <div class="item-columns-edit group">
        <button 
            type="button"
            class="w-6 h-7 flex items-center rounded-lg p-0 group-hover:bg-gray-100"
            style="color: rgb(136, 152, 170);"
            @click="onEditItemColumns"
        >
            <span class="material-icons-outlined w-full text-lg text-gray-300 group-hover:text-gray-500">edit</span>
        </button>

        <component v-bind:is="edit_html" @submit="onSubmit" @cancel="onCancel"></component>
    </div>
</template>

<script>
import Vue from 'vue';

import AkauntingModalAddNew from './AkauntingModalAddNew';

import Form from './../plugins/form';

export default {
  name: 'akaunting-edit-item-columns',

    components: {
        AkauntingModalAddNew,
    },

  props: {
    placeholder: {
      type: String,
      default: 'Type an item name',
      description: 'Input placeholder'
    },
    type: {
      type: String,
      default: 'invoice',
      description: 'document type'
    },
    editColumn: {
        type: Object,
        default: function () {
            return {
                text: 'Add New Item',
                status: false,
                new_text: 'New',
                buttons: {}
            };
        },
        description: "Selectbox Add New Item Feature"
    },
  },

  data() {
    return {
      form: {},
      edit_column: {
          text: this.editColumn.text,
          show: false,
          buttons: this.editColumn.buttons,
      },
      edit_html: '',
    };
  },

  methods: {
    onEditItemColumns() {
      let edit_column = this.edit_column;

      let type = this.type;

      window.axios.get(url + '/modals/documents/item-columns/edit?type=' + type)
      .then(response => {
          edit_column.show = true;
          edit_column.html = response.data.html;

          this.edit_html = Vue.component('add-new-component', function (resolve, reject) {
              resolve({
                  template: '<div><akaunting-modal-add-new :show="edit_column.show" @submit="onSubmit" @cancel="onCancel" :buttons="edit_column.buttons" :title="edit_column.text" :is_component=true :message="edit_column.html"></akaunting-modal-add-new></div>',

                  components: {
                    AkauntingModalAddNew,
                  },

                  data: function () {
                      return {
                          edit_column: edit_column,
                      }
                  },

                  methods: {
                      onSubmit(event) {
                          this.$emit('submit', event);
                      },

                      onCancel(event) {
                          this.$emit('cancel', event);
                      }
                  }
              })
          });
      })
      .catch(e => {
          console.log(e);
      })
      .finally(function () {
          // always executed
      });
    },

    onSubmit(event) {
        this.form = event;

        this.loading = true;

        let data = this.form.data();

        FormData.prototype.appendRecursive = function(data, wrapper = null) {
            for(var name in data) {
                if (wrapper) {
                    if ((typeof data[name] == 'object' || data[name].constructor === Array) && ((data[name] instanceof File != true ) && (data[name] instanceof Blob != true))) {
                        this.appendRecursive(data[name], wrapper + '[' + name + ']');
                    } else {
                        this.append(wrapper + '[' + name + ']', data[name]);
                    }
                } else {
                    if ((typeof data[name] == 'object' || data[name].constructor === Array) && ((data[name] instanceof File != true ) && (data[name] instanceof Blob != true))) {
                        this.appendRecursive(data[name], name);
                    } else {
                        this.append(name, data[name]);
                    }
                }
            }
        };

        let form_data = new FormData();
        form_data.appendRecursive(data);

        window.axios({
            method: this.form.method,
            url: this.form.action,
            data: form_data,
            headers: {
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'multipart/form-data'
            }
        })
        .then(response => {
            //this.form.loading = false;

            location.reload();
        })
        .catch(error => {
            this.form.loading = false;
            console.log(error);
        });
    },

    onCancel() {
        this.edit_column.show = false;
        this.edit_column.html = null;
        this.edit_html = null;

        let documentClasses = document.body.classList;

        documentClasses.remove('overflow-y-hidden', 'overflow-overlay');
    },
  },
};
</script>

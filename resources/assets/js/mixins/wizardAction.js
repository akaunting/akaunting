
export default {
    data: function () {
        return {
            current_tab: undefined,
            new_datas: false,
            model: {
                name: "",
                rate: "",
                select: "",
                enabled: 1
            },
            error_field: {},
            create_tax_text: true,
            button_loading: false,
        }
    },
    methods: {
        onAddItem() {
            this.new_datas = true;
            this.current_tab = undefined;
            this.error_field = {};

            if (this.model) {
                this.model.name = '';
                this.model.rate = '';
                this.model.select = '';
            }
        },

        onEditItem(item, index) {
            this.new_datas = false;
            this.current_tab = index;
            this.error_field = {};

            if (this.model) {
                this.model.name = item.name ? item.name : '';
                this.model.rate = item.rate ? item.rate : '';
                this.model.select = item.code ? item.code : '';
            }
        },

        onCancelItem() {
            this.current_tab = undefined;
        },

        onDataChange() {
            this.new_datas = false;
            this.current_tab = undefined;
            this.model.name = '';
            this.model.rate = '';
            this.model.select = '';
            this.model.enabled = 1;
        },

        onSuccessMessage(response) {
            let type = response.data.success ? 'success' : 'error';
            let timeout = 1000;

            if (response.data.important) {
                timeout = 0;
            }

            this.$notify({
                message: response.data.message,
                timeout: timeout,
                icon: "error_outline",
                type,
            });

            this.button_loading = false;

            this.onDataChange();
        },

        onDeleteItemMessage(event) {
            let type = event.success ? 'success' : 'error';
            let timeout = 1000;

            if (event.important) {
                timeout = 0;
            }

            this.$notify({
                message: event.message,
                timeout: timeout,
                icon: "",
                type,
            });

            this.onDataChange();
        },

        onSubmitEvent(form_method, form_url, plus_data, form_list, form_id) {
            const formData = new FormData(this.$refs["form"]);
            const data = {};
            this.button_loading = true;

            for (let [key, val] of formData.entries()) {
                Object.assign(data, {
                    [key]: val,
                });
            }
            
            if(plus_data == 'type') {
                Object.assign(data, {
                    ['type']: 'normal',
                });
            }

            window.axios({
                    method: form_method,
                    url: form_url,
                    data: data,
                })
                .then(response => {
                    if(form_list.length != undefined) {
                        if(form_method == 'POST') {
                            form_list.push({
                                "id": response.data.data.id,
                                "name": response.data.data.name,
                                "code": response.data.data.code,
                                "rate": response.data.data.rate,
                                "enabled": response.data.data.enabled != undefined ? response.data.data.enabled : 'true'
                            });
                        }
    
                        if(form_method == 'PATCH') {
                            form_list.forEach(item => {
                                if (item.id == form_id) {
                                    item.name = response.data.data.name;
                                    item.code = response.data.data.code;
                                    item.rate = response.data.data.rate;
                                }
                            });
                        }
                    }
                    this.onSuccessMessage(response);
                }, this)
                .catch(error => {
                    this.onFailError(error);
                }, this);
        },

        onEjetItem(event, form_list, event_id) {
            form_list.forEach(function (item, index) {
                if (item.id == event_id) {
                  form_list.splice(index, 1);
                  return;
                }
              }, this);

              this.component = "";
              document.body.classList.remove("overflow-hidden");
              this.onDeleteItemMessage(event);
        },

        onFailErrorGet(field_name) {
            if(this.error_field[field_name]) {
                return this.error_field[field_name][0];
            }
            this.button_loading = false;
        },

        onFailError(error) {
            this.error_field = error.response.data.errors;
            this.button_loading = false;
        }
    }
}


export default {
    data: function () {
        return {
            current_tab: undefined,
            new_datas: false,
            model: {
                name: "",
                rate: "",
                select: "",
                default_currency: 0,
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
                this.model.default = 0;
                this.model.default_currency = 0;
                this.model.enabled = 1;
            }
        },

        onEditItem(item, index) {
            this.new_datas = false;
            this.current_tab = index;
            this.error_field = {};

            if (this.model) {
                this.model.name = item.name ? item.name : '';
                this.model.rate = item.rate ? item.rate : '';
                this.model.enabled = 1;
                this.model.default = item.default ? item.default : 0;
                this.model.default_currency = item.default ? item.default : 0;
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
            this.model.default = 0;
            this.model.default_currency = 0;
            this.model.enabled = 1;
        },

        onSuccessMessage(response) {
            let type = response.data.success ? 'success' : 'error';
            let timeout = 1000;

            if (response.data.important) {
                timeout = 0;
            }

            this.$notify({
                verticalAlign: 'bottom',
                horizontalAlign: 'left',
                message: response.data.message,
                timeout: timeout,
                icon: "error_outline",
                type,
            });

            this.button_loading = false;

            this.onDataChange();
        },

        onDeleteItemMessage(event) {
            let type = event.success ? 'success' : 'danger';
            let timeout = 5000;

            if (event.important) {
                timeout = 0;
            }

            this.$notify({
                verticalAlign: 'bottom',
                horizontalAlign: 'left',
                message: event.message,
                timeout: timeout,
                icon: "error_outline",
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
            
            if (plus_data == 'type') {
                Object.assign(data, {
                    ['type']: 'normal',
                });
            }

            if (data.default_currency == 1) {
                data.rate = 1;
            }

            data.enabled = 1;

            window.axios({
                    method: form_method,
                    url: form_url,
                    data: data,
                })
                .then(response => {
                    if (form_list.length != undefined) {
                        if (form_method == 'POST') {
                            if (data.default_currency == 1) {
                                form_list.forEach(item => {
                                    item.default = 0;
                                    item.default_currency = 0;
                                });
                            }

                            form_list.push({
                                "id": response.data.data.id,
                                "name": response.data.data.name,
                                "code": response.data.data.code,
                                "rate": response.data.data.rate,
                                "enabled": response.data.data.enabled != undefined ? response.data.data.enabled : 'true',
                                "default": response.data.data.default ? response.data.data.default : 0,
                                "default_currency": response.data.data.default ? response.data.data.default : 0
                            });
                        }
    
                        if (form_method == 'PATCH') {
                            form_list.forEach(item => {
                                if (data.default_currency == 1) {
                                    item.default = 0;
                                    item.default_currency = 0;
                                }

                                if (item.id == form_id) {
                                    item.name = response.data.data.name;
                                    item.code = response.data.data.code;
                                    item.rate = response.data.data.rate;
                                    item.default = response.data.data.default ? response.data.data.default : 0;
                                    item.default_currency = response.data.data.default ? response.data.data.default : 0;
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
            if (this.error_field[field_name]) {
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

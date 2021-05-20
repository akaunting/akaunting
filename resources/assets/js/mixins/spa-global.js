export default {
    data: function () {
        return {
            currentTab: undefined,
            newDatas: false,
            model: {
                name: "",
                rate: "",
                select: "",
                enabled: 1
            },
        }
    },

    methods: {
        addItem() {
            this.newDatas = true;
            this.currentTab = undefined;
            if (this.model) {
                this.model.name = '';
                this.model.rate = '';
                this.model.select = '';
            }
        },

        handeClickEdit(item, index) {
            this.newDatas = false;
            this.currentTab = index;
            if (this.model) {
                this.model.name = item.name ? item.name : '';
                this.model.rate = item.rate ? item.rate : '';
                this.model.select = item.code ? item.code : '';
            }
        },
        dataHandleEvent() {
            this.newDatas = false;
            this.currentTab = undefined;
            this.model.name = '';
            this.model.rate = '';
            this.model.select = '';
            this.model.enabled = 1;
        },
        onSuccessEvent(response) {
            let type = response.data.success ? 'success' : 'error';
            let timeout = 1000;

            if (response.data.important) {
                timeout = 0;
            }

            this.$notify({
                message: response.data.message,
                timeout: timeout,
                icon: "fas fa-bell",
                type,
            });

            this.dataHandleEvent();
            
        },
        onSuccessDelete(event) {
            let type = event.success ? 'success' : 'error';
            let timeout = 1000;

            if (event.important) {
                timeout = 0;
            }

            this.$notify({
                message: event.message,
                timeout: timeout,
                icon: "fas fa-bell",
                type,
            });

            this.dataHandleEvent();
        },
        onEditEvent(form_method, form_url, plus_data, form_list, form_id) {
            let self = this;
            const formData = new FormData(this.$refs["form"]);
            const data = {};

            for (let [key, val] of formData.entries()) {
                Object.assign(data, {
                    [key]: val,
                    ['type']: 'normal'
                });
            }

            if (!plus_data || plus_data == undefined) {
                delete data.type;
            }

            window.axios({
                    method: form_method,
                    url: form_url,
                    data: data,
                })
                .then(response => {
                    form_list.forEach(item => {
                        if (item.id == form_id) {
                            item.name = response.data.data.name;
                            item.code = response.data.data.code;
                            item.rate = response.data.data.rate;
                            item.type = plus_data == undefined ? 'normal' : ''
                        }
                    });

                    this.onSuccessEvent(response);
                }, this)
                .catch(error => {
                    this.success = false;
                });
        },
        onSubmitEvent(form_method, form_url, plus_data, form_list) {
            const formData = new FormData(this.$refs["form"]);
            const data = {};

            for (let [key, val] of formData.entries()) {
                Object.assign(data, {
                    [key]: val,
                    ['type']: 'normal'
                });
            }

            if (!plus_data || plus_data == undefined) {
                delete data.type;
            }

            window.axios({
                    method: form_method,
                    url: form_url,
                    data: data,
                })
                .then(response => {
                    form_list.push({
                        "id": response.data.data.id,
                        "name": response.data.data.name,
                        "code": response.data.data.code,
                        "rate": response.data.data.rate,
                        "enabled": response.data.data.enabled != undefined ? response.data.data.enabled : 'true'
                    });

                    this.onSuccessEvent(response);
                }, this)
                .catch(error => {
                    this.success = false;
                });
        },
    },
}

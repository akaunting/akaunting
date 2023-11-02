import Errors from './error';

export default class Form {
    constructor(form_id) {
        let form = document.getElementById(form_id);

        if (!form) {
            return;
        }

        this['method'] = form.getAttribute('method').toLowerCase();
        this['action'] = form.getAttribute('action');

        for (let form_element of document.getElementById(form_id).getElementsByTagName("input")) {
            if (form_element.getAttribute('id') == 'global-search') {
                continue;
            }

            var name = form_element.getAttribute('name');
            var type = form_element.getAttribute('type');

            if (name == 'method') {
                continue;
            }

            /*
            if (name != null && name.indexOf('.') != '-1') {
                let partial_name = name.split('.');

                switch(partial_name.length) {
                    case 2:
                        this[partial_name[0]] = [];
                        this[partial_name[0]][partial_name[1]] = '';

                        break;
                    case 3:
                        this[partial_name[0]] = [];
                        this[partial_name[0]][partial_name[1]] = [];
                        this[partial_name[0]][partial_name[1]][partial_name[2]] = '';

                        break;
                    case 4:
                        this[partial_name[0]] = [];
                        this[partial_name[0]][partial_name[1]] = [];
                        this[partial_name[0]][partial_name[1]][partial_name[2]] = [];
                        this[partial_name[0]][partial_name[1]][partial_name[2]][partial_name[3]] = '';

                        break;
                    case 5:
                        this[partial_name[0]] = [];
                        this[partial_name[0]][partial_name[1]] = [];
                        this[partial_name[0]][partial_name[1]][partial_name[2]] = [];
                        this[partial_name[0]][partial_name[1]][partial_name[2]][partial_name[3]] = [];
                        this[partial_name[0]][partial_name[1]][partial_name[2]][partial_name[3]][partial_name[4]] = '';

                        break;
                }

                continue;
            }
            */

            if (form_element.getAttribute('data-item')) {
                if (!this['items']) {
                    var item = {};
                    var row = {};

                    item[0] = row;
                    this['items'] = item;
                }

                if (!this['items'][0][form_element.getAttribute('data-item')]) {
                    this['items'][0][form_element.getAttribute('data-item')] = '';
                }

                this['item_backup'] = this['items'];

                continue;
            }

            if (form_element.getAttribute('data-field')) {
                if (!this[form_element.getAttribute('data-field')]) {
                    var field = {};

                    this[form_element.getAttribute('data-field')] = field;
                }

                /*
                if (!this[form_element.getAttribute('data-field')][name]) {
                    this[form_element.getAttribute('data-field')][name] = '';
                }
                */

                if (type == 'radio') {
                    if (!this[form_element.getAttribute('data-field')][name]) {
                        this[form_element.getAttribute('data-field')][name] = (form_element.getAttribute('value') ? form_element.getAttribute('value') : 0) || 0;
                    } else if (form_element.checked) {
                        this[form_element.getAttribute('data-field')][name] = (form_element.getAttribute('value') ? form_element.getAttribute('value') : 0) || 0;
                    } else if (form_element.getAttribute('checked')) {
                        this[form_element.getAttribute('data-field')][name] = (form_element.getAttribute('value') ? form_element.getAttribute('value') : 0) || 0;
                    }
                } else if (type == 'checkbox') {
                    if (this[form_element.getAttribute('data-field')][name]) {
                        if (!this[form_element.getAttribute('data-field')][name].push) {
                            this[form_element.getAttribute('data-field')][name] = [this[form_element.getAttribute('data-field')][name]];
                        }

                        if (form_element.checked) {
                            this[form_element.getAttribute('data-field')][name].push(form_element.value);
                        }
                    } else {
                        if (form_element.checked) {
                            if (form_element.dataset.type != undefined) {
                                if (form_element.dataset.type == 'multiple') {
                                    this[name] = [];

                                    this[form_element.getAttribute('data-field')][name].push(form_element.value);
                                } else {
                                    this[form_element.getAttribute('data-field')][name] = form_element.value;
                                }
                            } else {
                                this[form_element.getAttribute('data-field')][name] = form_element.value;
                            }
                        } else {
                            if (form_element.dataset.type != undefined) {
                                if (form_element.dataset.type == 'multiple') {
                                    this[form_element.getAttribute('data-field')][name] = [];
                                } else {
                                    this[form_element.getAttribute('data-field')][name] = '';
                                }
                            } else {
                                this[form_element.getAttribute('data-field')][name] = '';
                            }
                        }
                    }
                } else {
                    this[form_element.getAttribute('data-field')][name] = form_element.getAttribute('value') || '';
                }

                continue;
            }

            if (type == 'radio') {
                if (!this[name]) {
                    this[name] = (form_element.getAttribute('value') ? form_element.getAttribute('value') : 0) || 0;
                } else if (form_element.checked) {
                    this[name] = (form_element.getAttribute('value') ? form_element.getAttribute('value') : 0) || 0;
                } else if (form_element.getAttribute('checked')) {
                    this[name] = (form_element.getAttribute('value') ? form_element.getAttribute('value') : 0) || 0;
                }
            } else if (type == 'checkbox') {
                if (this[name]) {
                    if (!this[name].push) {
                        this[name] = [this[name]];
                    }

                    if (form_element.checked) {
                        this[name].push(form_element.value);
                    }
                } else {
                    if (form_element.checked) {
                        if (form_element.dataset.type != undefined) {
                            if (form_element.dataset.type == 'multiple') {
                                this[name] = [];

                                this[name].push(form_element.value);
                            } else {
                                this[name] = form_element.value;
                            }
                        } else {
                            this[name] = form_element.value;
                        }
                    } else {

                        if (form_element.dataset.type != undefined) {
                            if (form_element.dataset.type == 'multiple') {
                                this[name] = [];
                            } else {
                                this[name] = '';
                            }
                        } else {
                            this[name] = '';
                        }
                    }
                }
            } else {
                this[name] = form_element.getAttribute('value') || '';
            }
        }

        for (let form_element of document.getElementById(form_id).getElementsByTagName("textarea")) {
            var name = form_element.getAttribute('name');

            if (name == 'method') {
                continue;
            }

            /*
            if (name != null && name.indexOf('.') != '-1') {
                let partial_name = name.split('.');

                switch(partial_name.length) {
                    case 2:
                        this[partial_name[0]] = [];
                        this[partial_name[0]][partial_name[1]] = '';

                        break;
                    case 3:
                        this[partial_name[0]] = [];
                        this[partial_name[0]][partial_name[1]] = [];
                        this[partial_name[0]][partial_name[1]][partial_name[2]] = '';

                        break;
                    case 4:
                        this[partial_name[0]] = [];
                        this[partial_name[0]][partial_name[1]] = [];
                        this[partial_name[0]][partial_name[1]][partial_name[2]] = [];
                        this[partial_name[0]][partial_name[1]][partial_name[2]][partial_name[3]] = '';

                        break;
                    case 5:
                        this[partial_name[0]] = [];
                        this[partial_name[0]][partial_name[1]] = [];
                        this[partial_name[0]][partial_name[1]][partial_name[2]] = [];
                        this[partial_name[0]][partial_name[1]][partial_name[2]][partial_name[3]] = [];
                        this[partial_name[0]][partial_name[1]][partial_name[2]][partial_name[3]][partial_name[4]] = '';

                        break;
                }

                continue;
            }
            */

            if (form_element.getAttribute('data-item')) {
                if (!this['items']) {
                    var item = {};
                    var row = {};

                    item[0] = row;
                    this['items'] = item;
                }

                if (!this['items'][0][form_element.getAttribute('data-item')]) {
                    this['items'][0][form_element.getAttribute('data-item')] = '';
                }

                this['item_backup'] = this['items'];

                continue;
            }

            if (form_element.getAttribute('data-field')) {
                if (!this[form_element.getAttribute('data-field')]) {
                    var field = {};

                    this[form_element.getAttribute('data-field')] = field;
                }

                if (!this[form_element.getAttribute('data-field')][name]) {
                    this[form_element.getAttribute('data-field')][name] = '';
                }

                continue;
            }

            if (this[name]) {
                if (!this[name].push) {
                    this[name] = [this[name]];
                }

                this[name].push(form_element.value || '');
            } else {
                this[name] = form_element.value || '';
            }
        }

        for (let form_element of document.getElementById(form_id).getElementsByTagName("select")) {
            var name = form_element.getAttribute('name');

            if (name == 'method') {
                continue;
            }

            if (form_element.getAttribute('data-item')) {
                if (!this['items']) {
                    var item = {};
                    var row = {};

                    item[0] = row;
                    this['items'] = item;
                }

                if (!this['items'][0][form_element.getAttribute('data-item')]) {
                    this['items'][0][form_element.getAttribute('data-item')] = '';
                }

                this['item_backup'] = this['items'];

                continue;
            }

            if (form_element.getAttribute('data-field')) {
                if (!this[form_element.getAttribute('data-field')]) {
                    var field = {};

                    this[form_element.getAttribute('data-field')] = field;
                }

                if (!this[form_element.getAttribute('data-field')][name]) {
                    this[form_element.getAttribute('data-field')][name] = '';
                }

                continue;
            }

            if (this[name]) {
                if (!this[name].push) {
                    this[name] = [this[name]];
                }

                this[name].push(form_element.getAttribute('value') || '');
            } else {
                this[name] = form_element.getAttribute('value') || '';
            }
        }

        this.errors = new Errors();

        this.loading = false;

        this.response = {};
    }

    data() {
        let data = Object.assign({}, this);

        delete data.method;
        delete data.action;
        delete data.errors;
        delete data.loading;
        delete data.response;

        return data;
    }

    reset() {
        for (let form_element of document.getElementsByTagName("input")) {
            var name = form_element.getAttribute('name');

            if (this[name]) {
                this[name] = '';
            }
        }

        for (let form_element of document.getElementsByTagName("textarea")) {
            var name = form_element.getAttribute('name');

            if (this[name]) {
                this[name] = '';
            }
        }

        for (let form_element of document.getElementsByTagName("select")) {
            var name = form_element.getAttribute('name');

            if (this[name]) {
                this[name] = '';
            }
        }
    }

    oldSubmit() {
        this.loading = true;

       window.axios({
            method: this.method,
            url: this.action,
            data: this.data()
        })
        .then(this.onSuccess.bind(this))
        .catch(this.onFail.bind(this));
    }

    submit() {
        FormData.prototype.appendRecursive = function(data, wrapper = null) {
            for (var name in data) {
                if (name == "previewElement" || name == "previewTemplate") {
                    continue;
                }

                if (wrapper) {
                    if ((typeof data[name] == 'object' || Array.isArray(data[name])) && ((data[name] instanceof File != true ) && (data[name] instanceof Blob != true))) {
                        this.appendRecursive(data[name], wrapper + '[' + name + ']');
                    } else {
                        this.append(wrapper + '[' + name + ']', data[name]);
                    }
                } else {
                    if ((typeof data[name] == 'object' || Array.isArray(data[name])) && ((data[name] instanceof File != true ) && (data[name] instanceof Blob != true))) {
                        this.appendRecursive(data[name], name);
                    } else {
                        this.append(name, data[name]);
                    }
                }
            }
        };

        this.loading = true;

        let data = this.data();

        let form_data = new FormData();
        form_data.appendRecursive(data);

        window.axios({
            method: this.method,
            url: this.action,
            data: form_data,
            headers: {
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'multipart/form-data'
            }
        })
        .then(this.onSuccess.bind(this))
        .catch(this.onFail.bind(this));
    }

    async asyncSubmit() {
        FormData.prototype.appendRecursive = function(data, wrapper = null) {
            for (var name in data) {
                if (name == "previewElement" || name == "previewTemplate") {
                    continue;
                }

                if (wrapper) {
                    if ((typeof data[name] == 'object' || Array.isArray(data[name])) && ((data[name] instanceof File != true ) && (data[name] instanceof Blob != true))) {
                        this.appendRecursive(data[name], wrapper + '[' + name + ']');
                    } else {
                        this.append(wrapper + '[' + name + ']', data[name]);
                    }
                } else {
                    if ((typeof data[name] == 'object' || Array.isArray(data[name])) && ((data[name] instanceof File != true ) && (data[name] instanceof Blob != true))) {
                        this.appendRecursive(data[name], name);
                    } else {
                        this.append(name, data[name]);
                    }
                }
            }
        };

        this.loading = true;

        let data = this.data();

        let form_data = new FormData();
        form_data.appendRecursive(data);

        await window.axios({
            method: this.method,
            url: this.action,
            data: form_data,
            headers: {
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'multipart/form-data'
            }
        })
        .then(this.onSuccess.bind(this))
        .catch(this.onFail.bind(this));
    }

    onSuccess(response) {
        this.errors.clear();

        this.loading = false;

        if (response.data.redirect) {
            this.loading = true;

            // Empty hash because /sale/customer/1#transaction redirect to sale/invoice/create.
            window.location.hash = '';

            window.location.href = response.data.redirect;

            if (typeof window.location.hash != "undefined" && window.location.hash.length) {
                location.reload();
            }
        }

        this.response = response.data;
    }

    // Form fields check validation issue
    onFail(error) {
        if (error.request) {
            if (error.request.status == 419) {
                window.location.href = '';
                return;
            }
        }

        if (typeof this.errors != "undefined") {
            this.errors.record(error.response.data.errors);
        }

        this.loading = false;
    }
}

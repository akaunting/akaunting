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

            if (type == 'radio') {
                if (!this[name]) {
                    this[name] = (form_element.getAttribute('value') ? 1 : 0) || 0;
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
                        this[name] = form_element.value;
                    } else {
                        this[name] = [];
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

    onSuccess(response) {
        this.errors.clear();

        this.loading = false;

        if (response.data.redirect) {
            this.loading = true;

            window.location.href = response.data.redirect;
        }

        this.response = response.data;
    }

    // Form fields check validation issue
    onFail(error) {
        this.errors.record(error.response.data.errors);

        this.loading = false;
    }
}

import Errors from './error';
import axios from "axios";

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

    submit() {
        this.loading = true;

        axios({
            method: this.method,
            url: this.action,
            data: this.data()
        })
        .then(this.onSuccess.bind(this))
        .catch(this.onFail.bind(this));
    }

    submitTest() {
        this.loading = true;

        let data = this.data();
        let form_data = new FormData();

        for (let key in data) {
            if ((typeof data[key] != 'object') && (typeof data[key] != 'array') ) {
                form_data.append(key, data[key]);
            } else {
                form_data.append(key, JSON.stringify(data[key]));
            }
        }

        axios({
            method: this.method,
            url: this.action,
            data: form_data,
            headers: {
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

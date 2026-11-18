import NProgress from 'nprogress'
import 'nprogress/nprogress.css'
import NProgressAxios from './nprogress-axios';

export default class BulkAction {
    constructor(path) {
        //This path use action url
        this['path'] = path;
        // Count selected items
        this['count'] = '';
        // Select action value ex: delete, export
        this['value'] = '*';
        // Select action message
        this['message'] = '';
        // Action type
        this['type'] = '';
        // Bulk action view status
        this['show'] = false;
        // Bulk action modal status
        this['modal'] = false;
        // Bulk action modal action
        this['loading'] = false;
        // Selected item list
        this['selected'] = [];
        // Select all items
        this['select_all'] = false;
        // Every selected record is enabled, so enabling them changes nothing
        this['all_enabled'] = false;
        // Every selected record is disabled, so disabling them changes nothing
        this['all_disabled'] = false;
    }

    // Change checkbox status
    select() {
        this.show = true;
        this.select_all = false;

        this.count = this.selected.length;

        this.selectStatus();

        if (this.count == document.querySelectorAll('[data-bulk-action]').length) {
            this.select_all = true;
        }

        if (! this.count) {
            this.show = false;

            this.hideSearchHTML();
        }
    }

    // Select all items action
    selectAll() {
        this.show = false;
        this.selected = [];
        this.hideSearchHTML();

        if (! this.select_all) {
            this.show = true;

            for (let input of document.querySelectorAll('[data-bulk-action]')) {
                this.selected.push(input.getAttribute('value'));
            }
        }

        this.count = this.selected.length;

        this.selectStatus();
    }

    // Works out whether the selection is all enabled or all disabled. Anything it cannot tell
    // leaves both flags off, so the bar keeps offering both actions as it did before.
    selectStatus() {
        this.all_enabled = false;
        this.all_disabled = false;

        // A checkbox ticked by hand holds its id as a number, one ticked by select all as a string
        let selected = this.selected.map(id => String(id));

        if (! selected.length) {
            return;
        }

        let enabled = 0;
        let disabled = 0;
        let from_attribute = 0;

        for (let input of document.querySelectorAll('[data-bulk-action]')) {
            if (! selected.includes(String(input.value))) {
                continue;
            }

            if (input.hasAttribute('data-enabled')) {
                from_attribute++;
            }

            let status = this.recordStatus(input);

            if (status === null) {
                return;
            }

            if (status) {
                enabled++;
            } else {
                disabled++;
            }
        }

        // Some selected record has no checkbox on this page to read it from
        if ((enabled + disabled) !== selected.length) {
            return;
        }

        // A disabled mark proves a record is disabled, so disable can always drop out. Its absence
        // only proves a record is enabled on a page that marks disabled records at all, so enable
        // drops out only when the page shows a mark somewhere (or every status came from data-enabled).
        let marks_disabled = (from_attribute === selected.length)
            || !! document.querySelector('[data-index-disable]');

        this.all_enabled = ! disabled && marks_disabled;
        this.all_disabled = ! enabled;
    }

    // Whether the record behind a checkbox is enabled: true, false, or null when it cannot tell.
    // A data-enabled attribute on the checkbox wins; otherwise the record is disabled when its
    // row or card shows the disabled mark, the way every list marks a disabled record.
    recordStatus(input) {
        let enabled = input.getAttribute('data-enabled');

        if (enabled === 'true') {
            return true;
        }

        if (enabled === 'false') {
            return false;
        }

        let row = input.closest('tr, [data-index-card]');

        if (! row) {
            return null;
        }

        return ! row.querySelector('[data-index-disable]');
    }

    // Whether the bar offers an action for the current selection: enable and disable are left
    // out when they would change nothing, every other action is always offered
    canShow(type) {
        if (type === 'enable') {
            return ! this.all_enabled;
        }

        if (type === 'disable') {
            return ! this.all_disabled;
        }

        return true;
    }

    change(type) {
        let action = document.getElementById('index-bulk-actions-' + type);

        this.value = type;

        this.message = action.getAttribute('data-message');

        if (typeof(this.message) == "undefined") {
            this.message = '';
        }

        this.path = action.getAttribute('data-path');

        this.type = '*';

        if (action.getAttribute('data-type')) {
            this.type = action.getAttribute('data-type');
        }

        return this.message;
    }

    // Selected item use action
    action() {
        if (this.value == '*') {
            return;
        }

        this.loading = true;

        // before version 2.0.23
        if (this.value == 'export') {
            this.type = 'download';
        }

        switch (this.type) {
            case 'download':
                let download_promise = Promise.resolve(window.axios({
                    url: this.path,
                    method: 'POST',
                    data:{
                        'handle': this.value,
                        'selected': this.selected
                    },
                    responseType: 'blob',
                }));

                download_promise.then((response) => {
                    if (response.data.type != 'application/json') {
                        const blob = new Blob([response.data], {type: response.data.type});
                        const url = window.URL.createObjectURL(blob);
                        const link = document.createElement('a');

                        link.href = url;

                        const contentDisposition = response.headers['content-disposition'];

                        let fileName = 'unknown';

                        if (contentDisposition) {
                            const fileNameMatch = contentDisposition.match(/filename=(.+)/);

                            if (fileNameMatch.length === 2) {
                                fileName = fileNameMatch[1];
                            }
                        }

                        link.setAttribute('download', fileName);

                        document.body.appendChild(link);

                        link.click();
                        link.remove();

                        window.URL.revokeObjectURL(url);

                        this.loading = false;
                        this.modal = false;
                        this.value = '*';
                        this.clear();

                        return;
                    }

                    window.location.reload(false);
                });

              break;
            case 'modal':
                this.loading = false;

                let modal_promise = Promise.resolve(window.axios.post(this.path, {
                    'handle': this.value,
                    'selected': this.selected
                }));

                modal_promise.then(response => {
                    let vue = document.querySelector('#app').__vue__;

                    if (vue === undefined) {
                        vue = document.querySelector('#main-body').__vue__;
                    }

                    vue.onDynamicComponentWithParams({
                        modal: true,
                        url: this.path,
                        title: response.data.data.title,
                        html: response.data.html,
                        buttons: response.data.data.buttons
                    });
                })
                .catch(error => {
                    //this.loading = false;
                    //this.modal = false;
                });

                break;
            default:
                let type_promise = Promise.resolve(window.axios.post(this.path, {
                    'handle': this.value,
                    'selected': this.selected
                }));

                type_promise.then(response => {
                    if (response.data.redirect === true) {
                        window.location.reload(false);
                    } else if (typeof response.data.redirect === 'string') {
                        window.location.href = response.data.redirect;
                    }
                })
                .catch(error => {
                    //this.loading = false;
                    //this.modal = false;

                    //window.location.reload(false);
                })
                .finally(function () {
                    //window.location.reload(false);
                });
        }
    }

    // Selected items clear
    clear() {
        this.show = false;
        this.select_all = false;
        this.selected = [];
        this.all_enabled = false;
        this.all_disabled = false;

        this.hideSearchHTML();
    }

    hideSearchHTML() {
        setInterval(() => {
            const search_box_html = document.querySelector('.js-search-box-hidden');

            if (search_box_html) {
                search_box_html.classList.add('d-none');
            }
        }, 5);
    };

    // Change enabled status
    status(item_id, event, notify) {
        let item = event.target;
        let status = (event.target.checked) ? 'enable' : 'disable';

        window.axios.get(this.path + '/' + item_id + '/' + status)
        .then(response => {
            let type = (response.data.success) ? 'success' : 'warning';

            if (! response.data.success) {
                if (item.checked) {
                    item.checked = false;
                } else {
                    item.checked = true;
                }
            }

            notify({
                message: response.data.message,
                timeout: 5000,
                icon: 'fas fa-bell',
                type
            });
        })
        .catch(error => {
        });
    }
}

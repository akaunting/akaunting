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
    }

    // Change checkbox status
    select() {
        this.show = true;
        this.select_all = false;

        this.count = this.selected.length;

        if (this.count == document.querySelectorAll('[data-bulk-action]').length) {
            this.select_all = true;
        }

        if (!this.count) {
            this.show = false;
        }
    }

    // Select all items action
    selectAll() {
        this.show = false;
        this.selected = [];

        if (!this.select_all) {
            this.show = true;

            for (let input of document.querySelectorAll('[data-bulk-action]')) {
                this.selected.push(input.getAttribute('value'));
            }
        }

        this.count = this.selected.length;
    }

    change(event) {
        this.message = event.target.options[event.target.options.selectedIndex].dataset.message;

        if (typeof(this.message) == "undefined") {
            this.message = '';
        }

        return this.message;
    }

    // Selected item use action
    action() {
        if (this.value == '*') {
            return;
        }

        var path = document.getElementsByName("bulk_action_path")[0].getAttribute('value');

        this.loading = true;

        if (this.value != 'export') {
            window.axios.post(path, {
                'handle': this.value,
                'selected': this.selected
            })
            .then(response => {
                if (response.data.redirect) {
                    window.location.reload(false);
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
        } else {
            window.axios({
                url: path,
                method: 'POST',
                data:{
                    'handle': this.value,
                    'selected': this.selected
                },
                responseType: 'blob',
            }).then((response) => {
                console.log(response.data);
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
            });
        }
    }

    // Selected items clear
    clear() {
        this.show = false;
        this.select_all = false;
        this.selected = [];
    }

    // Change enabled status
    status(item_id, event, notify) {
        var item = event.target;
        var status = (event.target.checked) ? 'enable' : 'disable';

        window.axios.get(this.path + '/' + item_id + '/' + status)
        .then(response => {
            var type = (response.data.success) ? 'success' : 'warning';

            if (!response.data.success) {
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

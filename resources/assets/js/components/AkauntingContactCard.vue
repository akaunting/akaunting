<template>
    <div :id="'select-contact-card-' + _uid" class="document-add-body-form-contact ml-3">
        <div class="document-contact" :class="[{'fs-exclude': show.contact_selected}]">
            <div class="document-contact-without-contact">
                <div v-if="!show.contact_selected" class="document-contact-without-contact-box-contact-select fs-exclude">
                    <div class="aka-select aka-select--medium is-open" tabindex="0">
                        <div>
                            <div class="aka-box aka-box--large" :class="[{'aka-error': error}]">
                                <div class="aka-box-content">
                                    <div class="document-contact-without-contact-box">
                                        <button type="button" class="btn-aka-link aka-btn--fluid document-contact-without-contact-box-btn" @click="onContactList">
                                           <i class="far fa-user fa-2x"></i> &nbsp; <span class="text-add-contact"> {{ addContactText }} </span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div v-if="error" class="invalid-feedback d-block mt--2 mb-2"
                                v-html="error">
                            </div>
                        </div>

                        <div class="aka-select-menu" v-if="show.contact_list">
                            <div class="aka-select-search-container">
                                <span class="aka-prefixed-input aka-prefixed-input--fluid">
                                    <div class="input-group input-group-merge">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fa fa-search"></i>
                                            </span>
                                        </div>

                                        <input
                                            type="text"
                                            data-input="true"
                                            class="form-control"
                                            autocapitalize="default" autocorrect="ON"
                                            :placeholder="placeholder"
                                            :ref="'input-contact-field-' + _uid"
                                            v-model="search"
                                            @input="onInput"
                                            @keyup.enter="onInput"
                                        />
                                    </div>
                                </span>
                            </div>

                            <ul class="aka-select-menu-options">
                                <div class="aka-select-menu-option" v-for="(contact, index) in sortContacts" :key="index" @click="onContactSeleted(index, contact.id)">
                                    <div>
                                        <strong class="text-strong"><span>{{ contact.name }}</span></strong>
                                    </div>
                                </div>

                                <div class="aka-select-menu-option" v-if="!sortContacts.length">
                                    <div>
                                        <strong class="text-strong" v-if="!contacts.length && !search"><span>{{ noDataText }}</span></strong>

                                        <strong class="text-strong" v-else><span>{{ noMatchingDataText }}</span></strong>
                                    </div>
                                </div>
                            </ul>

                            <div class="aka-select-footer" tabindex="0" @click="onContactCreate">
                                <span>
                                    <i class="fas fa-plus"></i> {{ createNewContactText }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else class="document-contact-with-contact-bill-to">
                    <div>
                        <span class="aka-text aka-text--block-label">{{ contactInfoText }}</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless p-0">
                            <tbody>
                                <tr>
                                    <th class="p-0" style="text-align:left;">
                                        <strong class="d-block">{{ contact.name }}</strong>
                                    </th>
                                </tr>
                                <tr v-if="contact.address">
                                    <th class="p-0" style="text-align:left; white-space: normal;">
                                        {{ contact.address }}
                                    </th>
                                </tr>
                                <tr v-if="contact.location">
                                    <th class="p-0" style="text-align:left; white-space: normal;">
                                        {{ contact.location }}
                                    </th>
                                </tr>
                                <tr v-if="contact.tax_number">
                                    <th class="p-0" style="text-align:left;">
                                        {{ taxNumberText }}: {{ contact.tax_number }}
                                    </th>
                                </tr>
                                <tr v-if="contact.phone">
                                    <th class="p-0" style="text-align:left;">
                                        {{ contact.phone }}
                                    </th>
                                </tr>
                                <tr v-if="contact.email">
                                    <th class="p-0" style="text-align:left;">
                                        {{ contact.email }}
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <button type="button" class="btn btn-link p-0" @click="onContactEdit">
                        {{ editContactText.replace(':contact_name', contact.name).replace(':field', contact.name) }}
                    </button>&nbsp;•&nbsp;
                    <button type="button" class="btn btn-link p-0" @click="onContactList">
                        {{ chooseDifferentContactText }}
                    </button>
                </div>
            </div>

            <component v-bind:is="add_new_html" @submit="onSubmit" @cancel="onCancel"></component>
        </div>
    </div>
</template>

<script>
import Vue from 'vue';

import { Select, Option, OptionGroup, ColorPicker } from 'element-ui';

import AkauntingModalAddNew from './AkauntingModalAddNew';
import AkauntingModal from './AkauntingModal';
import AkauntingMoney from './AkauntingMoney';
import AkauntingRadioGroup from './forms/AkauntingRadioGroup';
import AkauntingSelect from './AkauntingSelect';
import AkauntingDate from './AkauntingDate';

import Form from './../plugins/form';

export default {
    name: 'akaunting-contact-card',

    components: {
        [Select.name]: Select,
        [Option.name]: Option,
        [OptionGroup.name]: OptionGroup,
        [ColorPicker.name]: ColorPicker,
        AkauntingModalAddNew,
        AkauntingModal,
        AkauntingMoney,
        AkauntingRadioGroup,
        AkauntingSelect,
        AkauntingDate,
    },

    props: {
        placeholder: {
            type: String,
            default: 'Type a contact name',
            description: 'Input placeholder'
        },
        searchRoute: {
            type: String,
            default: '',
            description: 'Contact search route'
        },
        createRoute: {
            type: String,
            default: '',
            description: 'Contact create route'
        },
        error: {
            type: String,
            default: '',
            description: "Input error (below input)"
        },
        selected: {
            type: Object,
            default: function() {
                return {
                    index: 0,
                    key: '',
                    value: '',
                    type: 'customer',
                    id: 0,
                    name: '',
                    email: '',
                    tax_number: '',
                    currency_code: '',
                    phone: '',
                    website: '',
                    address: '',
                    city: '',
                    zip_code:'',
                    state:'',
                    country:'',
                    location:'',
                    reference: ''
                };
            },
            description: 'List of Contacts'
        },
        contacts: {
            type: Array,
            default: () => [],
            description: 'List of Contacts'
        },

        addNew: {
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
        addContactText: {
            type: String,
            default: 'Add a customer',
            description: ""
        },
        createNewContactText: {
            type: String,
            default: 'Create a new customer',
            description: ""
        },
        editContactText: {
            type: String,
            default: 'Edit :contact_name',
            description: ""
        },
        contactInfoText: {
            type: String,
            default: 'Bill to',
            description: ""
        },
        taxNumberText: {
            type: String,
            default: 'Tax number',
            description: ""
        },
        chooseDifferentContactText: {
            type: String,
            default: 'Choose a different customer',
            description: ""
        },
        noDataText: {
            type: String,
            default: 'No Data',
            description: "Selectbox empty options message"
        },
        noMatchingDataText: {
            type: String,
            default: 'No Matchign Data',
            description: "Selectbox search option not found item message"
        },
    },

    data() {
        return {
        contact_list: [],
        contact: this.selected,
        search: '', // search cloumn model
        show: {
            contact_selected: false,
            contact_list: false,
        },

        form: {},
        add_new: {
            text: this.addNew.text,
            show: false,
            buttons: this.addNew.buttons,
        },
        add_new_html: '',
        };
    },

    methods: {
        onContactList() {
            this.show.contact_list = true;
            this.show.contact_selected = false;
            this.contact = {};

            setTimeout(function() {
                this.$refs['input-contact-field-' + this._uid].focus();
            }.bind(this), 100);

            this.$emit('change', {
                index: 0,
                key: '',
                value: '',
                type: 'customer',
                id: 0,
                name: '',
                email: '',
                tax_number: '',
                currency_code: '',
                phone: '',
                website: '',
                address: '',
                city: '',
                zip_code: '',
                state: '',
                country: '',
                location: '',
                reference: ''
            });
        },

        onInput() {
            window.axios.get(this.searchRoute + '?search="' + this.search + '" limit:10')
            .then(response => {
                this.contact_list = [];

                let contacts = response.data.data;

                contacts.forEach(function (contact, index) {
                    this.contact_list.push({
                        index: index,
                        key: contact.id,
                        value: (contact.title) ? contact.title : (contact.display_name) ? contact.display_name : contact.name,
                        type: (contact.type) ? contact.type : 'customer',
                        id: contact.id,
                        name: (contact.title) ? contact.title : (contact.display_name) ? contact.display_name : contact.name,
                        email: (contact.email) ? contact.email : '',
                        tax_number: (contact.tax_number) ? contact.tax_number : '',
                        currency_code: (contact.currency_code) ? contact.currency_code : '',
                        phone: (contact.phone) ? contact.phone : '',
                        website: (contact.website) ? contact.website : '',
                        address: (contact.address) ? contact.address : '',
                        city: (contact.city) ? contact.city : '',
                        zip_code: (contact.zip_code) ? contact.zip_code : '',
                        state: (contact.state) ? contact.state : '',
                        country: (contact.country) ? contact.country : '',
                        location: (contact.location) ? contact.location : '',
                        reference: (contact.reference) ? contact.reference : ''
                    });
                }, this);
            })
            .catch(error => {

            });

            this.$emit('input', this.search);
        },

        onContactSeleted(index, contact_id) {
            this.contact_list.forEach(function (contact, index) {
                if (contact_id == contact.id) {
                    this.contact = contact;
                }
            }, this);

            this.show.contact_list = false;
            this.show.contact_selected = true;
            this.error = '';

            this.$emit('change', this.contact);
        },

        onContactCreate() {
            let add_new = this.add_new;

            window.axios.get(this.createRoute)
            .then(response => {
                this.show.contact_selected = false;
                this.show.contact_list = false;

                add_new.show = true;
                add_new.html = response.data.html;

                this.add_new_html = Vue.component('add-new-component', function (resolve, reject) {
                    resolve({
                        template: '<div><akaunting-modal-add-new :show="add_new.show" @submit="onSubmit" @cancel="onCancel" :buttons="add_new.buttons" :title="add_new.text" :is_component=true :message="add_new.html"></akaunting-modal-add-new></div>',

                        components: {
                            [Select.name]: Select,
                            [Option.name]: Option,
                            [OptionGroup.name]: OptionGroup,
                            [ColorPicker.name]: ColorPicker,
                            AkauntingModalAddNew,
                            AkauntingModal,
                            AkauntingMoney,
                            AkauntingRadioGroup,
                            AkauntingSelect,
                            AkauntingDate,
                        },

                        data: function () {
                            return {
                                add_new: add_new,
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

        onContactEdit() {
            let add_new = this.add_new;
            let path = this.createRoute.replace('/create', '/');

            path += this.contact.id + '/edit';

            add_new.text = this.editContactText.replace(':contact_name', this.contact.name).replace(':field', this.contact.name);

            window.axios.get(path)
            .then(response => {
                //this.show.contact_selected = false;
                this.show.contact_list = false;

                add_new.show = true;
                add_new.html = response.data.html;

                this.add_new_html = Vue.component('add-new-component', function (resolve, reject) {
                    resolve({
                        template: '<div><akaunting-modal-add-new :show="add_new.show" @submit="onSubmit" @cancel="onCancel" :buttons="add_new.buttons" :title="add_new.text" :is_component=true :message="add_new.html"></akaunting-modal-add-new></div>',

                        components: {
                            [Select.name]: Select,
                            [Option.name]: Option,
                            [OptionGroup.name]: OptionGroup,
                            [ColorPicker.name]: ColorPicker,
                            AkauntingModalAddNew,
                            AkauntingModal,
                            AkauntingMoney,
                            AkauntingRadioGroup,
                            AkauntingSelect,
                            AkauntingDate,
                        },

                        data: function () {
                            return {
                                add_new: add_new,
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
                this.form.loading = false;

                if (response.data.success) {
                    let contact = response.data.data;

                    this.contact = contact;

                    this.show.contact_list = false;
                    this.show.contact_selected = true;

                    this.add_new.show = false;

                    this.add_new.html = '';
                    this.add_new_html = null;

                    this.$emit('new', contact);

                    this.$emit('change', this.contact);

                    let documentClasses = document.body.classList;

                    documentClasses.remove("modal-open");
                }
            })
            .catch(error => {
                this.form.loading = false;

                console.log(error);
            });
        },

        onCancel() {
            this.add_new.show = false;
            this.add_new.html = null;
            this.add_new_html = null;

            let documentClasses = document.body.classList;

            documentClasses.remove("modal-open");
        },

        closeIfClickedOutside(event) {
            if (!document.getElementById('select-contact-card-' + this._uid).contains(event.target) && event.target.className != 'btn btn-link p-0') {
                this.show.contact_list = false;

                document.removeEventListener('click', this.closeIfClickedOutside);
            }
        },
    },

    created() {
        // Option set sort_option data
        if (!Array.isArray(this.contacts)) {
            let index = 0;

            for (const [key, value] of Object.entries(this.contacts)) {
                this.contact_list.push({
                    index: index,
                    key: key,
                    value: value,
                    type: 'customer',
                    id: key,
                    name: value,
                    email: '',
                    tax_number: '',
                    currency_code: '',
                    phone: '',
                    website: '',
                    address: '',
                    city: '',
                    zip_code: '',
                    state: '',
                    country: '',
                    location: '',
                    reference: ''
                });

                index++;
            }
        } else {
            this.contacts.forEach(function (contact, index) {
                this.contact_list.push({
                    index: index,
                    key: contact.id,
                    value: (contact.title) ? contact.title : (contact.display_name) ? contact.display_name : contact.name,
                    type: (contact.type) ? contact.type : 'customer',
                    id: contact.id,
                    name: (contact.title) ? contact.title : (contact.display_name) ? contact.display_name : contact.name,
                    email: (contact.email) ? contact.email : '',
                    tax_number: (contact.tax_number) ? contact.tax_number : '',
                    currency_code: (contact.currency_code) ? contact.currency_code : '',
                    phone: (contact.phone) ? contact.phone : '',
                    website: (contact.website) ? contact.website : '',
                    address: (contact.address) ? contact.address : '',
                    city: (contact.city) ? contact.city : '',
                    zip_code: (contact.zip_code) ? contact.zip_code : '',
                    state: (contact.state) ? contact.state : '',
                    country: (contact.country) ? contact.country : '',
                    location: (contact.location) ? contact.location : '',
                    reference: (contact.reference) ? contact.reference : ''
                });
            }, this);
        }

        if (this.selected.id) {
            this.show.contact_list = false;
            this.show.contact_selected = true;

            this.$emit('change', this.contact);
        }
    },

    computed: {
        sortContacts() {
            this.contact_list.sort(function (a, b) {
                var nameA = a.value.toUpperCase(); // ignore upper and lowercase
                var nameB = b.value.toUpperCase(); // ignore upper and lowercase

                if (nameA < nameB) {
                    return -1;
                }

                if (nameA > nameB) {
                    return 1;
                }

                // names must be equal
                return 0;
            });

            return this.contact_list.filter(contact => {
                return contact.value.toLowerCase().includes(this.search.toLowerCase())
            });
        },
    },

    watch: {
        show: {
            handler: function(newValue) {
                if (newValue) {
                    document.addEventListener('click', this.closeIfClickedOutside);
                }
            },
            deep: true
        }
    },
};
</script>

<style>
    .aka-error, .aka-error:hover {
        border-color: #ef3232 !important;
        background-color: #fb634038;
    }
</style>

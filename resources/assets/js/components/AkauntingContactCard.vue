<template>
    <div :id="'select-contact-card-' + _uid">
        <div class="relative" :class="[{'fs-exclude': show.contact_selected}]">
            <div v-if="!show.contact_selected">
                <div class="aka-select aka-select--medium is-open" tabindex="0">
                    <div class="w-full h-33 bg-white hover:bg-gray-100 rounded-lg border border-light-gray disabled:bg-gray-200 mt-1 text-purple font-medium" :class="[{'border-red': error}]">
                        <div class="text-black h-full">
                            <button type="button" class="w-full h-full flex flex-col items-center justify-center" @click="onContactList">
                                <span class="material-icons-outlined text-7xl text-black-400 pointer-events-none">person_add</span>
                                <span class="text-add-contact pointer-events-none"> {{ addContactText }} </span>
                            </button>
                        </div>
                    </div>

                    <div v-if="error" class="text-red text-sm mt-1 block mb-2"
                        v-html="error">
                    </div>

                    <div class="absolute top-0 left-0 right-0 bg-white border rounded-lg" style="z-index: 999;" v-if="show.contact_list">
                        <div class="relative">
                            <span class="material-icons-round absolute left-4 top-3 text-lg">search</span>
                            <input
                                type="text"
                                data-input="true"
                                class="w-full text-sm py-2.5 mt-1 border text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple px-10 border-t-0 border-l-0 border-r-0 border-gray-200 rounded-none"
                                autocapitalize="default" autocorrect="ON"
                                :placeholder="placeholder"
                                :ref="'input-contact-field-' + _uid"
                                :value="search"
                                @input="onInput($event)"
                                @keyup.enter="onInput($event)"
                            />
                        </div>

                        <ul class="w-full text-sm rounded-lg border-light-gray text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple p-0 border-0 mt-0 cursor-pointer">
                            <div class="hover:bg-gray-100 px-4 py-2" v-for="(contact, index) in sortContacts" :key="index" @click="onContactSeleted(index, contact.id)">
                                <span>{{ contact.name }}</span>
                            </div>

                            <div class="hover:bg-gray-100 px-4 py-2" v-if="!sortContacts.length">
                                <div>
                                    <span v-if="!contacts.length && !search">{{ noDataText }}</span>

                                    <span v-else>{{ noMatchingDataText }}</span>
                                </div>
                            </div>
                        </ul>

                        <div class="flex items-center justify-center h-11 text-sm text-center text-purple font-bold border border-l-0 border-r-0 border-b-0 rounded-bl-lg rounded-br-lg hover:bg-gray-100 cursor-pointer" tabindex="0" @click="onContactCreate">
                            <span class="material-icons text-lg font-bold mr-1 mt-1">add</span>
                            {{ createNewContactText }}
                        </div>
                    </div>
                </div>
            </div>  
            <div v-else class="document-contact-with-contact-bill-to">
                <div>
                    <span class="text-sm">{{ contactInfoText }}</span>
                </div>  
                <div class="overflow-x-visible mt-0">
                    <table class="table table-borderless p-0">
                        <tbody>
                            <tr>
                                <th class="font-medium text-left text-sm p-0">
                                    <span class="block">{{ contact.name }}</span>
                                </th>
                            </tr>
                            <tr v-if="contact.address">
                                <th class="font-normal text-xs text-left p-0">
                                    <div class="w-60 truncate">
                                        {{ contact.address }}
                                    </div>
                                </th>
                            </tr>
                            <tr v-if="contact.location">
                                <th class="font-normal text-xs text-left p-0">
                                    {{ contact.location }}
                                </th>
                            </tr>
                            <tr v-if="contact.tax_number">
                                <th class="font-normal text-xs text-left p-0">
                                    {{ taxNumberText }}: {{ contact.tax_number }}
                                </th>
                            </tr>
                            <tr v-if="contact.phone">
                                <th class="font-normal text-xs text-left p-0">
                                    {{ contact.phone }} &nbsp;
                                    <span v-if="contact.email">
                                    - {{ contact.email }}
                                    </span>
                                </th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>  
            <div :class="show.contact_selected ? 'flex' : 'hidden'" class="absolute flex-col mt-2">
                <button type="button" class="p-0 text-xs text-purple ltr:text-left rtl:text-right" @click="onContactEdit">
                    <span class="bg-no-repeat bg-0-2 bg-0-full hover:bg-full-2 bg-gradient-to-b from-transparent to-purple transition-backgroundSize">
                        {{ editContactText.replace(':contact_name', contact.name).replace(':field', contact.name) }}
                    </span>
                </button>
                <button type="button" class="p-0 text-xs text-purple ltr:text-left rtl:text-right" @click="onContactList">
                    <span class="bg-no-repeat bg-0-2 bg-0-full hover:bg-full-2 bg-gradient-to-b from-transparent to-purple transition-backgroundSize">
                        {{ chooseDifferentContactText }}
                    </span>
                </button>
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
import AkauntingRadioGroup from './AkauntingRadioGroup';
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
            type: [Array, Object],
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

        onInput(event) {
            this.search = event.target.value;

            window.axios.get(this.searchRoute + '?search="' + this.search + '" enabled:1 limit:10')
            .then(response => {
                this.contact_list = [];

                let contacts = response.data.data;

                contacts.forEach(function (contact, index) {
                    if (contact.enabled) {
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
                    }
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
                        template: '<div><akaunting-modal-add-new modal-dialog-class="max-w-md" modal-position-top :show="add_new.show" @submit="onSubmit" @cancel="onCancel" :buttons="add_new.buttons" :title="add_new.text" :is_component=true :message="add_new.html"></akaunting-modal-add-new></div>',

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
                        template: '<div><akaunting-modal-add-new modal-dialog-class="max-w-md" modal-position-top :show="add_new.show" @submit="onSubmit" @cancel="onCancel" :buttons="add_new.buttons" :title="add_new.text" :is_component=true :message="add_new.html"></akaunting-modal-add-new></div>',

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

                    this.contact_list.push({
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
                    
                    this.$emit('new', contact);
                    this.$emit('change', this.contact);

                    let documentClasses = document.body.classList;

                    documentClasses.remove('overflow-y-hidden', 'overflow-overlay');
                }
            })
            .catch(error => {
                this.form.loading = false;
                this.form.errors.record(error.response.data.errors);
            });
        },

        onCancel() {
            this.add_new.show = false;
            this.add_new.html = null;
            this.add_new_html = null;

            let documentClasses = document.body.classList;

            documentClasses.remove('overflow-y-hidden', 'overflow-overlay');
        },

        closeIfClickedOutside(event) {
            if (!document.getElementById('select-contact-card-' + this._uid).contains(event.target)) {
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

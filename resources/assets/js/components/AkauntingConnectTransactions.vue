<template>
    <SlideYUpTransition :duration="animationDuration">
        <div class="modal w-full h-full fixed top-0 left-0 right-0 z-50 overflow-y-auto overflow-hidden modal-add-new fade items-center justify-center"
            :class="[{'show flex flex-wrap modal-background': show}, {'hidden': !show}]"
            v-show="show"
            tabindex="-1"
            role="dialog"
            :aria-hidden="!show">
            <div class="w-full my-10 m-auto flex flex-col" :class="modalDialogClass ? modalDialogClass : 'max-w-screen-sm'">
                <slot name="modal-content">
                    <div class="bg-body rounded-lg modal-content">
                        <div class="p-5">
                            <div class="flex items-center justify-between border-b pb-5">
                                <slot name="card-header">
                                    <h4 class="text-base font-medium">
                                        {{ translations.title }}
                                    </h4>

                                    <button type="button" class="text-lg" @click="onCancel" aria-hidden="true">
                                        <span class="rounded-md border-b-2 px-2 py-1 text-sm bg-gray-100">esc</span>
                                    </button>
                                </slot>
                            </div>
                        </div>

                        <slot name="modal-body">
                            <div class="px-5">
                                <template v-if="transaction">
                                    <div class="flex flex-col items-start gap-y-3">
                                        <div class="text-left text-sm">
                                            <div class="font-medium">
                                                {{ translations.contact }}
                                            </div>

                                            <span>
                                                {{ transaction.contact.name }}
                                            </span>
                                        </div>

                                        <div class="text-left text-sm">
                                            <div class="font-medium">
                                                {{ translations.category }}
                                            </div>

                                            <span>
                                                {{ transaction.category.name }}
                                            </span>
                                        </div>

                                        <div class="text-left text-sm">
                                            <div class="font-medium">
                                                {{ translations.account }}
                                            </div>

                                            <span>
                                                {{ transaction.account.name }}
                                            </span>
                                        </div>
                                    </div>
                                </template>

                                <div class="relative sm:col-span-6 pt-3">
                                    <div style="table-layout: fixed;">
                                        <div class="overflow-x-visible overflow-y-hidden">
                                            <table class="w-full" id="items" style="table-layout: fixed">
                                                <thead class="border-b">
                                                    <tr>
                                                        <th colspan="3" class="w-12/12 px-0 text-left border-t-0 border-r-0 border-b-0">
                                                            {{ translations.document }}
                                                        </th>
                                                    </tr>
                                                </thead>

                                                <colgroup>
                                                    <col style="width: 20%;">
                                                    <col style="width: 80%;">
                                                </colgroup>

                                                <tbody>
                                                    <tr v-for="(row, index) in form.items" :index="index" class="border-b border-gray-200">
                                                        <td class="px-0 border-r-0 border-b-0 truncate">
                                                            <div class="text-sm">
                                                                <div class="truncate">
                                                                    <b>{{ translations.number }}:</b> {{ row.number }}
                                                                </div>

                                                                <div class="truncate" v-if="row.notes">
                                                                    <b>{{ translations.notes }}:</b> {{ row.notes }}
                                                                </div>
                                                            </div>

                                                            <div class="row" style="font-size: 13px;">
                                                                <div class="col-md-12 long-texts">
                                                                    <b>{{ translations.contact }}:</b> {{ row.contact }}
                                                                </div>
                                                            </div>
                                                        </td>

                                                        <td class="px-0 border-l-0 border-b-0 border-r-0">
                                                            <div class="flex items-center justify-end">
                                                                <akaunting-money
                                                                    :col="''"
                                                                    :masked="true"
                                                                    name="amount"
                                                                    title=""
                                                                    :currency="currency"
                                                                    :dynamic-currency="currency"
                                                                    :value="row.amount"
                                                                    :row-input="true"
                                                                    :money-class="'text-right input-price'"
                                                                    @input="checkAmount(index, $event)"
                                                                ></akaunting-money>

                                                                <akaunting-money
                                                                    class="hidden"
                                                                    :masked="true"
                                                                    name="max_amount"
                                                                    title=""
                                                                    :group_class="null"
                                                                    :currency="currency"
                                                                    v-model="row.max_amount"
                                                                    :dynamic-currency="currency"
                                                                    :row-input="true"
                                                                    :disabled="true"
                                                                ></akaunting-money>

                                                                <div class="pl-2 group">
                                                                    <button type="button" @click="onDeleteItem(index)" class="w-6 h-7 flex items-center rounded-lg p-0 group-hover:bg-gray-100">
                                                                        <span class="w-full material-icons-outlined text-lg text-gray-300 group-hover:text-gray-500">delete</span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <tr id="addItem">
                                                        <td colspan="3" class="w-12/12 p-0">
                                                            <akaunting-document-button
                                                                :items="documents"
                                                                :selectedItems="form.items"
                                                                :dynamic-currency="currency"
                                                                @document-selected="onAddItem($event)"
                                                                :no-data-text="translations.no_data"
                                                                :placeholder="translations.placeholder_search"
                                                                :add-item-text="translations.add_an"
                                                            ></akaunting-document-button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="sm:col-span-6">
                                    <div class="overflow-y-hidden py-5">
                                        <table id="totals" class="float-right">
                                            <colgroup>
                                                <col style="width: 51.5%;">
                                                <col style="width: 30%;">
                                                <col style="width: 18.5%;">
                                            </colgroup>
                                            <tbody id="document-total-rows">
                                                <tr id="tr-total">
                                                    <td class="border-t-0 p-0"></td>

                                                    <td class="font-medium text-sm text-right border-r-0 border-b-0 align-middle py-0 pr-0">
                                                        {{ translations.total }}
                                                    </td>

                                                    <td class="text-sm text-right border-b-0 p-0">
                                                        <div>
                                                            <money
                                                                name="total_amount"
                                                                :value="total_amount"
                                                                v-bind="money"
                                                                masked
                                                                disabled
                                                                class="px-0 disabled-money text-right banking-price-text"
                                                                style="height: unset;"
                                                            ></money>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr id="tr-transaction-amount">
                                                    <td class="border-t-0 p-0"></td>

                                                    <td class="font-medium text-sm text-right border-r-0 border-b-0 align-middle py-0 pr-0">
                                                        {{ translations.transaction + ' ' + translations.amount }}
                                                    </td>

                                                    <td class="text-sm text-right border-b-0 p-0">
                                                        <div>
                                                            <money
                                                                :name="'transaction_amount'"
                                                                :value="transaction.amount"
                                                                v-bind="money"
                                                                masked
                                                                disabled
                                                                class="px-0 disabled-money text-right banking-price-text"
                                                                style="height: unset;"
                                                                v-if="transaction"
                                                            ></money>
                                                        </div>
                                                        <akaunting-money
                                                            class="hidden"
                                                            :masked="true"
                                                            name="transaction_amount"
                                                            title=""
                                                            :group_class="null"
                                                            :currency="currency"
                                                            :value="transaction_amount"
                                                            @input="transaction_amount = $event"
                                                            :dynamic-currency="currency"
                                                            :row-input="true"
                                                            :disabled="true"
                                                        ></akaunting-money>
                                                    </td>
                                                </tr>

                                                <tr id="tr-difference">
                                                    <td class="border-t-0 p-0"></td>

                                                    <td class="font-medium text-sm text-right border-r-0 border-b-0 align-middle py-0 pr-0">
                                                        {{ translations.difference }}
                                                    </td>

                                                    <td class="text-right text-sm border-b-0 p-0">
                                                        <div>
                                                            <money
                                                                name="difference_amount"
                                                                :value="difference_amount"
                                                                v-bind="money"
                                                                masked
                                                                disabled
                                                                class="px-0 disabled-money text-right banking-price-text"
                                                                style="height: unset;"
                                                            ></money>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </slot>

                        <div class="p-5 border-gray-300">
                            <slot name="card-footer">
                                <div class="flex items-center justify-end">
                                    <button type="button" class="px-6 py-1.5 mr-2 hover:bg-gray-200 rounded-lg" @click="onCancel">
                                        {{ translations.cancel }}
                                    </button>

                                    <button type="button"
                                        :disabled="differenceAmount != 0 || (differenceAmount == 0 && form.loading)"
                                        class="relative px-6 py-1.5 bg-green hover:bg-green-700 text-white rounded-lg disabled:bg-green-100"
                                        @click="onConfirm"
                                    >
                                        <i
                                            v-if="form.loading"
                                            class="animate-submit delay-[0.28s] absolute w-2 h-2 rounded-full left-0 right-0 -top-3.5 m-auto before:absolute before:w-2 before:h-2 before:rounded-full before:animate-submit before:delay-[0.14s] after:absolute after:w-2 after:h-2 after:rounded-full after:animate-submit before:-left-3.5 after:-right-3.5 after:delay-[0.42s]"
                                        >
                                        </i>
                                        <span :class="[{'opacity-0': differenceAmount != 0}]">{{ translations.save }}</span>
                                    </button>
                                </div>
                            </slot>
                        </div>
                    </div>
                </slot>
            </div>
        </div>
    </SlideYUpTransition>
</template>

<script>
import { SlideYUpTransition } from "vue2-transitions";
import AkauntingSelect from './AkauntingSelect';
import AkauntingMoney from './AkauntingMoney';
import AkauntingDocumentButton from './AkauntingDocumentButton';
import {Money} from 'v-money';

export default {
    name: 'akaunting-connect-transactions',

    components: {
        SlideYUpTransition,
        AkauntingSelect,
        AkauntingMoney,
        AkauntingDocumentButton,
        Money,
    },

    props: {
        show: Boolean,
        transaction: Object,
        currency: Object,
        documents: Array,
        translations: Object,
        modalDialogClass: {
            type: String,
            default: '',
            description: "Modal Body size Class"
        },
        animationDuration: {
            type: Number,
            default: 800,
            description: "Modal transition duration"
        },
    },

    data() {
        return {
            form: {
                items: [],
                loading: false,
            },
            transaction_amount: "",
            money: {},
            totalAmount: 0,
            differenceAmount: 0,
        };
    },

    created() {
        this.totalAmount = this.total_amount;
        this.differenceAmount = this.difference_amount;
    },

    computed: {
        total_amount: function () {
            let amount = 0;

            this.form.items.forEach(function(item) {
                amount += this.convertMoneyToFloat(item.amount);
            }, this);

            this.totalAmount = parseFloat(amount.toFixed(this.currency.precision));

            return parseFloat(amount.toFixed(this.currency.precision));
        },

        difference_amount: function () {
            if (! this.transaction_amount) {
                this.differenceAmount = 0;

                return 0;
            }

            let transaction_amount = this.convertMoneyToFloat(this.transaction_amount);
            let amount = parseFloat((this.total_amount - transaction_amount).toFixed(this.currency.precision));

            this.differenceAmount = amount;

            return amount;
        }
    },

    mounted() {
        window.addEventListener('keyup',(e) => {
            if (e.key === 'Escape') { 
                this.onCancel();
            }
        });
    },

    methods: {
        onConfirm() {
            this.form.loading = true;

            this.$emit("confirm");
            this.onConnectTransaction();
        },

        onCancel() {
            this.$emit('close-modal');
        },

        onAddItem(document) {
            this.form.items.push(
                Object.assign({}, {
                    id: document.id,
                    document_id: document.id,
                    number: document.document_number,
                    contact: document.contact_name,
                    notes: document.notes,
                    amount: (document.amount - document.paid).toFixed(this.currency.precision),
                    max_amount: (document.amount - document.paid).toFixed(this.currency.precision),
                })
            );
        },

        onDeleteItem(index) {
            this.form.items.splice(index, 1);
        },

        async onConnectTransaction() {
            let self = this;

            let connect_transaction = Promise.resolve(window.axios.post(url + '/banking/transactions/' + this.transaction.id + '/connect', {
                data: {
                    items: this.form.items
                }
            }));

            connect_transaction.then(response => {
                if (response.data.redirect) {
                    window.location.href = response.data.redirect;
                }
            })
            .catch(error => {
            })
            .finally(function () {
                self.$emit("close-modal");
            });
        },

        convertMoneyToFloat(money) {
            // "$198.4"
            if (typeof(money) != "string") {
                money = money.toString();
            }

            // 198.4
            let regex = new RegExp(this.currency.thousands_separator, 'gi');

            money = money.replace(this.currency.symbol, '').replace(regex, '').replace(this.currency.decimal_mark, '.');

            // "198.40"
            money = parseFloat(money).toFixed(this.currency.precision);

            // 198.40
            return parseFloat(money);
        },

        checkAmount(index, amount) {
            let max_amount = this.convertMoneyToFloat(this.form.items[index].max_amount);
            let changed_amount = this.convertMoneyToFloat(amount);

            this.form.items[index].amount = changed_amount.toFixed(this.currency.precision);

            setTimeout(function () {
                if (changed_amount > max_amount || changed_amount == 0) {
                    this.form.items[index].amount = max_amount.toFixed(this.currency.precision);
                }
            }.bind(this), 50);
        }
    },

    watch: {
        show: function (newValue) {
            if (newValue) {
                this.form.items = [];
            }
        },

        transaction: function (transaction) {
            this.transaction_amount = transaction.amount;
        },

        currency: function (currency) {
            this.money = {
                decimal: currency.decimal_mark,
                thousands: currency.thousands_separator,
                prefix: (currency.symbol_first) ? currency.symbol : '',
                suffix: (!currency.symbol_first) ? currency.symbol : '',
                precision: parseInt(currency.precision),
            };
        }
    },
}
</script>

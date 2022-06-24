<template>
    <div>
        <div class="flex flex-col" v-if="Object.keys(cards).length">
            <div class="gap-y-2">
                <div
                    class="py-2 border-b hover:bg-gray-100 cursor-pointer"
                    v-for="(name, key, id) in cards" :key="key"
                    @click="onRegisterCard(id)"
                >
                    <label>
                        <input
                            type="radio"
                            :disabled="loading"
                            :checked="register_card == id"
                        >
                        <span class="ltr:ml-2 rtl:mr-2">{{ name }}</span>
                    </label>
                </div>
            </div>

            <div class="py-2 border-b hover:bg-gray-100 cursor-pointer" @click="onAddNewCard()">
                <label>
                    <input
                        type="radio"
                        id="card-new-card"
                        name="new-card"
                        :disabled="loading"
                        :checked="new_card"
                    >
                    <span class="ltr:ml-2 rtl:mr-2">{{ textNewCard }}</span>
                </label>
            </div>

            <div class="flex justify-end" v-for="(name, key, id) in cards" :key="key">
                <button
                    v-if="register_card == id"
                    type="button"
                    :id="'card-'+ key + '-' + id"
                    @click="onSelectedCard(key)"
                    class="relative flex items-center justify-center px-6 py-1.5 my-2 bg-green hover:bg-green-700 text-white rounded-lg"
                    :disabled="loading"
                >
                    <i
                        v-if="loading || register_card_loading"
                        class="animate-submit delay-[0.28s] absolute w-2 h-2 rounded-full left-0 right-0 -top-3.5 m-auto before:absolute before:w-2 before:h-2 before:rounded-full before:animate-submit before:delay-[0.14s] after:absolute after:w-2 after:h-2 after:rounded-full after:animate-submit before:-left-3.5 after:-right-3.5 after:delay-[0.42s]"
                    >
                    </i>

                    <span :class="[{'opacity-0': loading || register_card_loading}]">
                        {{ textButton }}
                </span>
                </button>
            </div>

            <div v-if="new_card" class="w-full mt-3" id="collapseNewCard">
                <Card
                    :fields="fields"
                    :labels="formData"
                    :isCardNumberMasked="isCardNumberMasked"
                    :randomBackgrounds="randomBackgrounds"
                    :backgroundImage="backgroundImage"
                />

                <div class="grid sm:grid-cols-8 gap-x-4 gap-y-6 my-3.5">
                    <div class="sm:col-span-8">
                        <label for="cardName" class="text-black text-sm font-medium">
                            {{ textCardName }}
                        </label>

                        <input
                            type="text"
                            :id="fields.cardName"
                            v-letter-only
                            @input="changeName"
                            class="w-full text-sm px-3 py-2.5 mt-1 rounded-lg border border-light-gray text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple"
                            :placeholder="placeholderCardName"
                            :value="formData.cardName"
                            data-card-field
                            autocomplete="off"
                        />

                        <div class="text-red text-sm mt-1 block" v-if="validations.card_name" v-html="validations.card_name[0]"></div>
                    </div>

                    <div class="sm:col-span-8">
                        <label for="cardNumber" class="text-black text-sm font-medium">
                            {{ textCardNumber }}
                        </label>

                        <input
                            type="tel"
                            :id="fields.cardNumber"
                            @input="changeNumber"
                            @focus="focusCardNumber"
                            @blur="blurCardNumber"
                            class="w-full text-sm px-3 py-2.5 mt-1 rounded-lg border border-light-gray text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple"
                            :placeholder="placeholderCardNumber"
                            :value="formData.cardNumber"
                            :maxlength="cardNumberMaxLength"
                            data-card-field
                            autocomplete="off"
                        />

                        <div class="text-red text-sm mt-1 block" v-if="validations.card_number" v-html="validations.card_number[0]"></div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="cardMonth" class="text-black text-sm font-medium">
                            {{ textExpirationDate }}
                        </label>

                        <select
                            class="w-full text-sm px-3 py-2.5 mt-1 rounded-lg border border-light-gray text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple"
                            :id="fields.cardMonth"
                            v-model="formData.cardMonth"
                            @change="changeMonth"
                            data-card-field
                        >
                            <option value disabled selected>{{ textMonth }}</option>
                            <option
                                v-bind:value="n < 10 ? '0' + n : n"
                                v-for="n in 12"
                                v-bind:disabled="n < minCardMonth"
                                v-bind:key="n"
                            >{{generateMonthValue(n)}}</option>
                        </select>
                    </div>

                    <div class="sm:col-span-3 flex items-end">
                        <select
                            class="w-full text-sm px-3 py-2.5 mt-1 rounded-lg border border-light-gray text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple"
                            :id="fields.cardYear"
                            v-model="formData.cardYear"
                            @change="changeYear"
                            data-card-field
                        >
                            <option value="" disabled>{{ textYear }}</option>
                            <option
                                v-bind:value="$index + minCardYear"
                                v-for="(n, $index) in 12"
                                v-bind:key="n"
                            >{{$index + minCardYear}}</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="cardCvv" class="text-black text-sm font-medium">
                            {{ textCvv }}
                        </label>

                        <input
                            type="tel"
                            class="w-full text-sm px-3 py-2.5 mt-1 rounded-lg border border-light-gray text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple"
                            :placeholder="placeholderCvv"
                            v-number-only
                            :id="fields.cardCvv"
                            maxlength="4"
                            :value="formData.cardCvv"
                            @input="changeCvv"
                            data-card-field
                            autocomplete="off"
                        />

                        <div class="text-red text-sm mt-1 block" v-if="validations.card_cvv" v-html="validations.card_cvv[0]"></div>
                    </div>

                    <div class="sm:col-span-8 flex" :class="storeCard ? 'justify-between' : 'justify-end'">
                        <div class="flex items-center" v-if="storeCard">
                            <input @input="changeStoreCard" :id="'store_card' + _uid" name="store_card" type="checkbox" value="true" class="rounded-sm text-purple border-gray-300 cursor-pointer disabled:bg-gray-200 focus:outline-none focus:ring-transparent">

                            <label :for="'store_card' + _uid" class="text-black text-sm font-medium ltr:ml-2 rtl:ml-2">
                                <strong>{{ textStoreCard }}</strong>
                            </label>
                        </div>

                        <button class="relative flex items-center justify-center px-6 py-1.5 bg-green hover:bg-green-700 text-white rounded-lg" v-on:click="invaildCard" :disabled="loading">
                            <i
                                v-if="loading"
                                class="animate-submit delay-[0.28s] absolute w-2 h-2 rounded-full left-0 right-0 -top-3.5 m-auto before:absolute before:w-2 before:h-2 before:rounded-full before:animate-submit before:delay-[0.14s] after:absolute after:w-2 after:h-2 after:rounded-full after:animate-submit before:-left-3.5 after:-right-3.5 after:delay-[0.42s]"
                            >
                            </i>

                            <span :class="[{'opacity-0': loading}]">
                                {{ textButton }}
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col" v-if="! Object.keys(cards).length">
            <Card
                :fields="fields"
                :labels="formData"
                :isCardNumberMasked="isCardNumberMasked"
                :randomBackgrounds="randomBackgrounds"
                :backgroundImage="backgroundImage"
            />

            <div class="grid sm:grid-cols-8 gap-x-4 gap-y-6 my-3.5">
                <div class="sm:col-span-8">
                    <label for="cardName" class="text-black text-sm font-medium">
                        {{ textCardName }}
                    </label>

                    <input
                        type="text"
                        :id="fields.cardName"
                        v-letter-only
                        @input="changeName"
                        class="w-full text-sm px-3 py-2.5 mt-1 rounded-lg border border-light-gray text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple"
                        :placeholder="placeholderCardName"
                        :value="formData.cardName"
                        data-card-field
                        autocomplete="off"
                    />

                    <div class="text-red text-sm mt-1 block" v-if="validations.card_name" v-html="validations.card_name[0]"></div>
                </div>

                <div class="sm:col-span-8">
                    <label for="cardNumber" class="text-black text-sm font-medium">
                        {{ textCardNumber }}
                    </label>

                    <input
                        type="tel"
                        :id="fields.cardNumber"
                        @input="changeNumber"
                        @focus="focusCardNumber"
                        @blur="blurCardNumber"
                        class="w-full text-sm px-3 py-2.5 mt-1 rounded-lg border border-light-gray text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple"
                        :placeholder="placeholderCardNumber"
                        :value="formData.cardNumber"
                        :maxlength="cardNumberMaxLength"
                        data-card-field
                        autocomplete="off"
                    />

                    <div class="text-red text-sm mt-1 block" v-if="validations.card_number" v-html="validations.card_number[0]"></div>
                </div>

                <div class="sm:col-span-3">
                    <label for="cardMonth" class="text-black text-sm font-medium">
                        {{ textExpirationDate }}
                    </label>

                    <div>
                        <select
                            class="w-full text-sm px-3 py-2.5 mt-1 rounded-lg border border-light-gray text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple"
                            :id="fields.cardMonth"
                            v-model="formData.cardMonth"
                            @change="changeMonth"
                            data-card-field
                        >
                            <option value="" disabled>{{ textMonth }}</option>
                            <option
                                v-bind:value="n < 10 ? '0' + n : n"
                                v-for="n in 12"
                                v-bind:disabled="n < minCardMonth"
                                v-bind:key="n"
                            >{{ generateMonthValue(n) }}</option>
                        </select>
                    </div>
                </div>

                <div class="sm:col-span-3 flex items-end">
                    <select
                        class="w-full text-sm px-3 py-2.5 mt-1 rounded-lg border border-light-gray text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple"
                        :id="fields.cardYear"
                        v-model="formData.cardYear"
                        @change="changeYear"
                        data-card-field
                    >
                        <option value="" disabled>{{ textYear }}</option>
                        <option
                            v-bind:value="$index + minCardYear"
                            v-for="(n, $index) in 12"
                            v-bind:key="n"
                        >{{ $index + minCardYear }}</option>
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label for="cardCvv" class="text-black text-sm font-medium">
                        {{ textCvv }}
                    </label>

                    <input
                        type="tel"
                        class="w-full text-sm px-3 py-2.5 mt-1 rounded-lg border border-light-gray text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple"
                        :placeholder="placeholderCvv"
                        v-number-only
                        :id="fields.cardCvv"
                        maxlength="4"
                        :value="formData.cardCvv"
                        @input="changeCvv"
                        data-card-field
                        autocomplete="off"
                    />

                    <div class="text-red text-sm mt-1 block" v-if="validations.card_cvv" v-html="validations.card_cvv[0]"></div>
                </div>

                <div class="sm:col-span-8 flex" :class="storeCard ? 'justify-between' : 'justify-end'">
                    <div class="flex items-center" v-if="storeCard">
                        <input @input="changeStoreCard" :id="'store_card' + _uid" name="store_card" type="checkbox" value="true" class="rounded-sm text-purple border-gray-300 cursor-pointer disabled:bg-gray-200 focus:outline-none focus:ring-transparent">

                        <label :for="'store_card' + _uid" class="text-black text-sm font-medium ltr:ml-2 rtl:ml-2">
                            <strong>{{ textStoreCard }}</strong>
                        </label>
                    </div>

                    <button v-if="! hideButton" class="relative flex items-center justify-center px-6 py-1.5 bg-green hover:bg-green-700 text-white rounded-lg" v-on:click="invaildCard" :disabled="loading">
                        <i
                            v-if="loading"
                            class="animate-submit delay-[0.28s] absolute w-2 h-2 rounded-full left-0 right-0 -top-3.5 m-auto before:absolute before:w-2 before:h-2 before:rounded-full before:animate-submit before:delay-[0.14s] after:absolute after:w-2 after:h-2 after:rounded-full after:animate-submit before:-left-3.5 after:-right-3.5 after:delay-[0.42s]"
                        >
                        </i>
                        <span :class="[{'opacity-0': loading}]">
                            {{ textButton }}
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import axios from 'axios';
import Card from './Card';

export default {
    name: 'CardForm',

    directives: {
        'number-only': {
            bind (el) {
                function checkValue (event) {
                    event.target.value = event.target.value.replace(/[^0-9]/g, '');

                    if (event.charCode >= 48 && event.charCode <= 57) {
                        return true;
                    }

                    event.preventDefault()
                }

                el.addEventListener('keypress', checkValue);
            }
        },

        'letter-only': {
            bind (el) {
                function checkValue (event) {
                    if (event.charCode >= 48 && event.charCode <= 57) {
                        event.preventDefault();
                    }

                    return true;
                }

                el.addEventListener('keypress', checkValue)
            }
        }
    },

    props: {
        cards: {
            type: [Array, Object],
            default: function () {
                return [];
            },
            description: "Add Card Style"
        },

        storeCard: {
            type: Boolean,
            default: false,
            icon: '',
            description: "Save card"
        },

        path: {
            type: String,
            default: null,
            description: "Card Form Post Path"
        },

        textCardNumber: {
            type: String,
            default: 'Card Number',
            description: "Label Card Number Text"
        },

        placeholderCardNumber: {
            type: String,
            default: 'Enter Number',
            description: "Placeholder Card Number Text"
        },

        textCard: {
            type: String,
            default: 'Cards',
            description: "Label Card Text"
        },

        textNewCard: {
            type: String,
            default: 'New Card',
            description: "Label New Card Name Text"
        },

        textCardName: {
            type: String,
            default: 'Card Name',
            description: "Label Card Name Text"
        },

        placeholderCardName: {
            type: String,
            default: 'Enter Name',
            description: "Placeholder Card Name Text"
        },

        textExpirationDate: {
            type: String,
            default: 'Expiration Date',
            description: "Label Expiration Date Text"
        },

        textMonth: {
            type: String,
            default: 'Month',
            description: "Label Month Text"
        },

        textYear: {
            type: String,
            default: 'Year',
            description: "Label Year Text"
        },

        textCvv: {
            type: String,
            default: 'CVV',
            description: "Label CVV Text"
        },

        placeholderCvv: {
            type: String,
            default: 'Enter CVV',
            description: "Placeholder Card CVV Text"
        },

        hideButton: {
            type: Boolean,
            default: false,
            description: "Confirm button"
        },

        textButton: {
            type: String,
            default: 'Confirm',
            description: "Add Card Style"
        },

        textStoreCard: {
            type: String,
            default: 'Store Card',
            description: "Selected store card"
        },

        formData: {
            type: Object,
            default: () => {
                return {
                    cardName: '',
                    cardNumber: '',
                    cardMonth: '',
                    cardYear: '',
                    cardCvv: '',
                    storeCard: false,
                    card_id: 0,
                }
            }
        },

        backgroundImage: [String, Object],

        randomBackgrounds: {
            type: Boolean,
            default: true
        }
    },

    components: {
        Card
    },

    data() {
        return {
            loading: false,
            validations:{},
            fields: {
                cardNumber: 'v-card-number',
                cardName: 'v-card-name',
                cardMonth: 'v-card-month',
                cardYear: 'v-card-year',
                cardCvv: 'v-card-cvv',
                storeCard: 'v-card-store-card'
            },
            minCardYear: new Date().getFullYear(),
            isCardNumberMasked: true,
            mainCardNumber: this.cardNumber,
            cardNumberMaxLength: 19,
            card_id: 0,
            new_card: false,
            register_card: 0,
            register_card_loading: false
        }
    },

    computed: {
        minCardMonth() {
            if (this.formData.cardYear === this.minCardYear) {
                return new Date().getMonth() + 1;
            }

            return 1;
        }
    },

    watch: {
        cardYear() {
            if (this.formData.cardMonth < this.minCardMonth) {
                this.formData.cardMonth = '';
            }
        }
    },

    mounted() {
        this.maskCardNumber();
    },

    methods: {
        onRegisterCard(id) {
            this.register_card = id;
            this.new_card = false;
            this.register_card_loading = true;

            setTimeout(() => {
                this.register_card_loading = false;
            }, 800);
        },

        onAddNewCard() {
            this.new_card = true;
            this.register_card = null;
        },

        onSelectedCard(card_id) {
            this.card_id = card_id;
            this.formData.card_id = card_id;

            this.invaildCard();
        },

        changeStoreCard(e) {
            this.formData.storeCard = false;

            if (e.target.checked) {
                this.formData.storeCard = true;
            }
        },

        generateMonthValue(n) {
            return n < 10 ? `0${n}` : n;
        },

        changeName(e) {
            this.formData.cardName = e.target.value;

            this.$emit('input-card-name', this.formData.cardName);
        },

        changeNumber(e) {
            this.formData.cardNumber = e.target.value;

            let value = this.formData.cardNumber.replace(/\D/g, '');

            // american express, 15 digits
            if ((/^3[47]\d{0,13}$/).test(value)) {
                this.formData.cardNumber = value.replace(/(\d{4})/, '$1 ').replace(/(\d{4}) (\d{6})/, '$1 $2 ');
                this.cardNumberMaxLength = 17;
            } else if ((/^3(?:0[0-5]|[68]\d)\d{0,11}$/).test(value)) { // diner's club, 14 digits
                this.formData.cardNumber = value.replace(/(\d{4})/, '$1 ').replace(/(\d{4}) (\d{6})/, '$1 $2 ');
                this.cardNumberMaxLength = 16;
            } else if ((/^\d{0,16}$/).test(value)) { // regular cc number, 16 digits
                this.formData.cardNumber = value.replace(/(\d{4})/, '$1 ').replace(/(\d{4}) (\d{4})/, '$1 $2 ').replace(/(\d{4}) (\d{4}) (\d{4})/, '$1 $2 $3 ');
                this.cardNumberMaxLength = 19;
            }

            this.$emit('input-card-number', this.formData.cardNumber);
        },

        changeMonth() {
            this.$emit('input-card-month', this.formData.cardMonth);
        },

        changeYear() {
            this.$emit('input-card-year', this.formData.cardYear);
        },

        changeCvv(e) {
            this.formData.cardCvv = e.target.value;

            this.$emit('input-card-cvv', this.formData.cardCvv);
        },

        invaildCard() {
            this.loading = true;

            let number = this.formData.cardNumber;
            let sum = 0;
            let isOdd = true;

            this.unMaskCardNumber();

            /*
            for (let i = number.length - 1; i >= 0; i--) {
                let num = number.charAt(i);

                if (isOdd) {
                    sum += num;
                } else {
                    num = num * 2;

                    if (num > 9) {
                        num = num.toString().split('').join('+');
                    }

                    sum += num;
                }

                isOdd = !isOdd;
            }
            */

            this.formData.card_id = this.card_id;

            if (sum % 10 !== 0) {
                alert('invaild card number');
                this.loading = false;
            } else {
                axios.post(this.path, this.formData)
                .then(response => {
                    this.loading = false;

                    if (response.data.redirect) {
                        this.loading = true;

                        window.location.href = response.data.redirect;
                    }
                })
                .catch(error => {
                    this.loading = false;

                    this.validations = error.response.data.errors;
                });
            }
        },

        blurCardNumber() {
            if (this.isCardNumberMasked) {
                this.maskCardNumber();
            }
        },

        maskCardNumber() {
            this.mainCardNumber = this.formData.cardNumber;

            let arr = this.formData.cardNumber.split('');

            arr.forEach((element, index) => {
                if (index > 4 && index < 14 && element.trim() !== '') {
                    arr[index] = '*';
                }
            });

            this.formData.cardNumber = arr.join('');
        },

        unMaskCardNumber() {
            this.formData.cardNumber = this.mainCardNumber;
        },

        focusCardNumber() {
            this.unMaskCardNumber();
        },

        toggleMask() {
            this.isCardNumberMasked = !this.isCardNumberMasked;

            if (this.isCardNumberMasked) {
                this.maskCardNumber();
            } else {
                this.unMaskCardNumber();
            }
        },

        showNewCard() {
        }
    }
}
</script>

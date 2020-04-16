<template>
<div>
    <div class="card" v-if="card">
        <div class="row align-items-center">
            <div class="col-md-6 p-5">
                <div class="form-group">
                    <label for="cardName" class="form-control-label">{{ textCardName }}</label>
                    <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-font"></i>
                            </span>
                        </div>
                        <input
                            type="text"
                            :id="fields.cardName"
                            v-letter-only
                            @input="changeName"
                            class="form-control"
                            :placeholder="placeholderCardName"
                            :value="formData.cardName"
                            data-card-field
                            autocomplete="off"
                        />
                    </div>
                </div>

                <div class="form-group">
                    <label for="cardNumber" class="form-control-label">{{ textCardNumber }}</label>
                    <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-credit-card"></i>
                            </span>
                        </div>
                        <input
                            type="tel"
                            :id="fields.cardNumber"
                            @input="changeNumber"
                            @focus="focusCardNumber"
                            @blur="blurCardNumber"
                            class="form-control"
                            :placeholder="placeholderCardNumber"
                            :value="formData.cardNumber"
                            :maxlength="cardNumberMaxLength"
                            data-card-field
                            autocomplete="off"
                        />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-7">
                        <label for="cardMonth" class="form-control-label">{{ textExpirationDate }}</label>
                        <div class="card-form__group">
                            <select
                                class="card-input__input -select"
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

                            <select
                                class="card-input__input -select"
                                :id="fields.cardYear"
                                v-model="formData.cardYear"
                                @change="changeYear"
                                data-card-field
                                >
                                    <option value disabled selected>{{ textYear }}</option>
                                    <option
                                        v-bind:value="$index + minCardYear"
                                        v-for="(n, $index) in 12"
                                        v-bind:key="n"
                                    >{{$index + minCardYear}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="cardCvv" class="form-control-label">{{ textCvv }}</label>
                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-key"></i>
                                    </span>
                                </div>
                                <input
                                    type="tel"
                                    class="form-control"
                                    :placeholder="placeholderCvv"
                                    v-number-only
                                    :id="fields.cardCvv"
                                    maxlength="4"
                                    :value="formData.cardCvv"
                                    @input="changeCvv"
                                    data-card-field
                                    autocomplete="off"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <button class="btn btn-icon btn-success" v-on:click="invaildCard">
                    <span class="btn-inner--icon"><i class="fas fa-check"></i></span>
                    <span class="btn-inner--text">{{ textButton }}</span>
                </button>
            </div>

            <div class="col-md-6 mt--3">
                <Card
                    :fields="fields"
                    :labels="formData"
                    :isCardNumberMasked="isCardNumberMasked"
                    :randomBackgrounds="randomBackgrounds"
                    :backgroundImage="backgroundImage"
                />
            </div>
        </div>
    </div>

    <div class="row align-items-center" v-if="!card">
        <div class="col-md-6 p-5">
            <div class="form-group">
                <label for="cardNumber" class="form-control-label">{{ textCardNumber }}</label>
                <div class="input-group input-group-merge">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-credit-card"></i>
                        </span>
                    </div>
                    <input
                        type="tel"
                        :id="fields.cardNumber"
                        @input="changeNumber"
                        @focus="focusCardNumber"
                        @blur="blurCardNumber"
                        class="form-control"
                        :placeholder="placeholderCardNumber"
                        :value="formData.cardNumber"
                        :maxlength="cardNumberMaxLength"
                        data-card-field
                        autocomplete="off"
                    />
                </div>
                <div class="invalid-feedback d-block" v-if="validations.card_number" v-html="validations.card_number[0]"></div>
            </div>

            <div class="form-group">
                <label for="cardName" class="form-control-label">{{ textCardName }}</label>
                <div class="input-group input-group-merge">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-font"></i>
                        </span>
                    </div>
                    <input
                        type="text"
                        :id="fields.cardName"
                        v-letter-only
                        @input="changeName"
                        class="form-control"
                        :placeholder="placeholderCardName"
                        :value="formData.cardName"
                        data-card-field
                        autocomplete="off"
                    />
                </div>
                <div class="invalid-feedback d-block" v-if="validations.card_name" v-html="validations.card_name[0]"></div>
            </div>

            <div class="row">
                <div class="col-md-7">
                    <label for="cardMonth" class="form-control-label">{{ textExpirationDate }}</label>
                    <div class="form-group d-flex">
                        <div class="input-group input-group-merge">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                            </div>
                            <select
                                class="form-control w-50"
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
                                    :selected="selected == value"
                                >{{generateMonthValue(n)}}</option>
                            </select>
                        </div>
                        <div class="input-group input-group-merge ml-4">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                            </div>
                            <select
                                class="form-control w-50"
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
                                        :selected="selected == value"
                                    >{{$index + minCardYear}}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="form-group">
                        <label for="cardCvv" class="form-control-label">{{ textCvv }}</label>
                        <div class="input-group input-group-merge">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-key"></i>
                                </span>
                            </div>
                            <input
                                type="tel"
                                class="form-control"
                                :placeholder="placeholderCvv"
                                v-number-only
                                :id="fields.cardCvv"
                                maxlength="4"
                                :value="formData.cardCvv"
                                @input="changeCvv"
                                data-card-field
                                autocomplete="off"
                            />
                        </div>
                        <div class="invalid-feedback d-block" v-if="validations.card_cvv" v-html="validations.card_cvv[0]"></div>
                    </div>
                </div>
            </div>

            <button class="btn btn-icon btn-success" v-on:click="invaildCard" :disabled="loading">
                <div v-if="loading" class="aka-loader-frame">
                    <div class="aka-loader"></div>
                </div>
                <span v-if="!loading" class="btn-inner--icon">
                    <i class="fas fa-check"></i>
                </span>
                <span v-if="!loading" class="btn-inner--text">{{ textButton }}</span>
            </button>
        </div>

        <div class="col-md-6 mt--3">
            <Card
                :fields="fields"
                :labels="formData"
                :isCardNumberMasked="isCardNumberMasked"
                :randomBackgrounds="randomBackgrounds"
                :backgroundImage="backgroundImage"
            />
        </div>
    </div>
</div>
</template>

<script>
import axios from 'axios';

import Card from './Card';
import './../../../css/creditcard/style.scss';

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
        card: {
            type: Boolean,
            default: false,
            icon: '',
            description: "Add Card Style"
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

        textButton: {
            type: String,
            default: 'Confirm',
            description: "Add Card Style"
        },

        formData: {
            type: Object,
            default: () => {
                return {
                    cardName: '',
                    cardNumber: '',
                    cardMonth: '',
                    cardYear: '',
                    cardCvv: ''
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
                cardCvv: 'v-card-cvv'
            },
            minCardYear: new Date().getFullYear(),
            isCardNumberMasked: true,
            mainCardNumber: this.cardNumber,
            cardNumberMaxLength: 19
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

            this.unMaskCardNumber();

            let number = this.formData.cardNumber;
            let sum = 0;
            let isOdd = true;

            /*for (let i = number.length - 1; i >= 0; i--) {
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
            }*/

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
        }
    }
}
</script>

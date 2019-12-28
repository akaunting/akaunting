<template>
    <div class="card">
        <div class="row align-items-center">
            <div class="col-md-6 p-5">

                <div class="form-group">
                    <label for="cardNumber" class="form-control-label">Card Number</label>
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
                        placeholder="Enter Number"
                        :value="formData.cardNumber"
                        :maxlength="cardNumberMaxLength"
                        data-card-field
                        autocomplete="off"
                        />
                    </div>
                </div>

                <div class="form-group">
                    <label for="cardName" class="form-control-label">Card Name</label>
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
                        placeholder="Enter Name"
                        :value="formData.cardName"
                        data-card-field
                        autocomplete="off"
                        />
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-7">
                        <label for="cardMonth" class="form-control-label">Expiration Date</label>
                        <div class="card-form__group">
                            <select
                            class="card-input__input -select"
                            :id="fields.cardMonth"
                            v-model="formData.cardMonth"
                            @change="changeMonth"
                            data-card-field
                            >
                            <option value disabled selected>Month</option>
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
                                <option value disabled selected>Year</option>
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
                            <label for="cardCvv" class="form-control-label">CVV</label>
                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-key"></i>
                                    </span>
                                </div>
                                <input
                                type="tel"
                                class="form-control"
                                placeholder="Enter Cvv"
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
                    <span class="btn-inner--text">Submit</span>
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
import Card from './Card';
import './../../../css/creditcard/style.scss';

export default {
  name: 'CardForm',
  directives: {
    'number-only': {
      bind (el) {
        function checkValue (event) {
          event.target.value = event.target.value.replace(/[^0-9]/g, '')
          if (event.charCode >= 48 && event.charCode <= 57) {
            return true
          }
          event.preventDefault()
        }
        el.addEventListener('keypress', checkValue)
      }
    },
    'letter-only': {
      bind (el) {
        function checkValue (event) {
          if (event.charCode >= 48 && event.charCode <= 57) {
            event.preventDefault()
          }
          return true
        }
        el.addEventListener('keypress', checkValue)
      }
    }
  },
  props: {
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
  data () {
    return {
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
    minCardMonth () {
      if (this.formData.cardYear === this.minCardYear) return new Date().getMonth() + 1
      return 1
    }
  },
  watch: {
    cardYear () {
      if (this.formData.cardMonth < this.minCardMonth) {
        this.formData.cardMonth = ''
      }
    }
  },
  mounted () {
    this.maskCardNumber()
  },
  methods: {
    generateMonthValue (n) {
      return n < 10 ? `0${n}` : n
    },

    changeName (e) {
      this.formData.cardName = e.target.value
      this.$emit('input-card-name', this.formData.cardName)
    },

    changeNumber (e) {
      this.formData.cardNumber = e.target.value
      let value = this.formData.cardNumber.replace(/\D/g, '')
      // american express, 15 digits
      if ((/^3[47]\d{0,13}$/).test(value)) {
        this.formData.cardNumber = value.replace(/(\d{4})/, '$1 ').replace(/(\d{4}) (\d{6})/, '$1 $2 ')
        this.cardNumberMaxLength = 17
      } else if ((/^3(?:0[0-5]|[68]\d)\d{0,11}$/).test(value)) { // diner's club, 14 digits
        this.formData.cardNumber = value.replace(/(\d{4})/, '$1 ').replace(/(\d{4}) (\d{6})/, '$1 $2 ')
        this.cardNumberMaxLength = 16
      } else if ((/^\d{0,16}$/).test(value)) { // regular cc number, 16 digits
        this.formData.cardNumber = value.replace(/(\d{4})/, '$1 ').replace(/(\d{4}) (\d{4})/, '$1 $2 ').replace(/(\d{4}) (\d{4}) (\d{4})/, '$1 $2 $3 ')
        this.cardNumberMaxLength = 19
      }
      this.$emit('input-card-number', this.formData.cardNumber)
    },

    changeMonth () {
      this.$emit('input-card-month', this.formData.cardMonth)
    },

    changeYear () {
      this.$emit('input-card-year', this.formData.cardYear)
    },

    changeCvv (e) {
      this.formData.cardCvv = e.target.value
      this.$emit('input-card-cvv', this.formData.cardCvv)
    },

    invaildCard () {
      let number = this.formData.cardNumber
      let sum = 0
      let isOdd = true
      for (let i = number.length - 1; i >= 0; i--) {
        let num = number.charAt(i)
        if (isOdd) {
          sum += num
        } else {
          num = num * 2
          if (num > 9) {
            num = num.toString().split('').join('+')
          }
          sum += num
        }
        isOdd = !isOdd
      }
      if (sum % 10 !== 0) {
        alert('invaild card number')
      }
    },

    blurCardNumber () {
      if (this.isCardNumberMasked) {
        this.maskCardNumber()
      }
    },

    maskCardNumber () {
      this.mainCardNumber = this.formData.cardNumber
      let arr = this.formData.cardNumber.split('')
      arr.forEach((element, index) => {
        if (index > 4 && index < 14 && element.trim() !== '') {
          arr[index] = '*'
        }
      })
      this.formData.cardNumber = arr.join('')
    },

    unMaskCardNumber () {
      this.formData.cardNumber = this.mainCardNumber
    },

    focusCardNumber () {
      this.unMaskCardNumber()
    },

    toggleMask () {
      this.isCardNumberMasked = !this.isCardNumberMasked
      if (this.isCardNumberMasked) {
        this.maskCardNumber()
      } else {
        this.unMaskCardNumber()
      }
    }
  }
}
</script>

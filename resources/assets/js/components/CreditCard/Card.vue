<template>
  <div class="card-item relative w-2/4 lg:w-3/4 h-48 m-auto my-5" :class="{ '-active' : isCardFlipped }">
    <div class="card-item__side h-full rounded-lg shadow-lg overflow-hidden" style="transform: perspective(2000px) rotateY(0deg) rotateX(0deg) rotate(0deg);
  transform-style: preserve-3d;
  transition: all 0.8s cubic-bezier(0.71, 0.03, 0.56, 0.85);
  backface-visibility: hidden;">
      <div
        class="absolute w-full h-full left-0 right-0 top-0 rounded-sm overflow-hidden z-10 pointer-events-none opacity-0"
        style="transition: all 0.35s cubic-bezier(0.71, 0.03, 0.56, 0.85);"
        :class="{'opacity-100' : focusElementStyle }"
        :style="focusElementStyle"
        ref="focusElement"
      ></div>
      <div class="absolute w-full h-full bg-black left-0 top-0 rounded-lg overflow-hidden" style="background-image: linear-gradient(147deg, #354fce 0%, #0c296b 74%);">
      </div>
      <div class="relative h-full py-6 px-4 select-none">
        <div class="flex items-start justify-between px-4">
          <img
            :src="chip_src"
            class="w-12"
          />
          <div class="relative w-full h-12 flex flex-end">
            <transition name="slide-fade-up">
              <img
                :src="card_type_src + cardType + '.png'"
                v-if="cardType"
                :key="cardType"
                alt
                class="w-full h-full object-right-top	object-contain"
              />
            </transition>
          </div>
        </div>
        
        <div class="flex items-start justify-between text-white">
          <label :for="fields.cardName" class="p-2 block cursor-pointer text-white" :ref="fields.cardName">
            <transition name="slide-fade-up">
              <div class="text-lg overflow-hidden" v-if="labels.cardName.length" key="1">
                <transition-group name="slide-fade-right">
                  <span
                    class="text-lg overflow-hidden"
                    v-for="(n, $index) in labels.cardName.replace(/\s\s+/g, ' ')"
                    :key="$index + 1"
                  >{{n}}</span>
                </transition-group>
              </div>
              <div class="text-lg overflow-hidden" v-else key="2">Full Name</div>
            </transition>
          </label>
          <div class="flex flex-wrap shrink-0 text-lg p-2 cursor-pointer" ref="cardDate">
            <label :for="fields.cardMonth" class="card-item__dateItem">
              <transition name="slide-fade-up">
                <span v-if="labels.cardMonth" :key="labels.cardMonth">{{labels.cardMonth}}</span>
                <span v-else key="2">MM</span>
              </transition>
            </label>
            /
            <label for="cardYear" class="card-item__dateItem">
              <transition name="slide-fade-up">
                <span v-if="labels.cardYear" :key="labels.cardYear">{{String(labels.cardYear).slice(2,4)}}</span>
                <span v-else key="2">YY</span>
              </transition>
            </label>
          </div>
        </div>

        <label :for="fields.cardNumber" class="flex justify-around font-medium text-white text-lg p-3 cursor-pointer" :ref="fields.cardNumber">
          <template>
            <span v-for="(n, $index) in currentPlaceholder" :key="$index">
              <transition name="slide-fade-up">
                <div class="w-2" v-if="getIsNumberMasked($index, n)">*</div>
                <div
                  class="w-2"
                  :class="{ '-active' : n.trim() === '' }"
                  :key="currentPlaceholder"
                  v-else-if="labels.cardNumber.length > $index"
                >{{labels.cardNumber[$index]}}</div>
                <div
                  class="w-2"
                  :class="{ '-active' : n.trim() === '' }"
                  v-else
                  :key="currentPlaceholder + 1"
                >{{n}}</div>
              </transition>
            </span>
          </template>
        </label>
      </div>
    </div>
    <div class="card-item__side -back absolute top-0 left-0 w-full p-0 h-full">
      <div class="absolute w-full h-full bg-black left-0 top-0 rounded-lg overflow-hidden" style="background-image: linear-gradient(147deg, #354fce 0%, #0c296b 74%);">
      </div>
      <div class="absolute w-full h-32 mt-12 bg-black"></div>
      <div class="relative p-4 text-right">
        <div class="pr-4 text-white mb-3">CVV</div>
        <div class="h-12 flex items-center justify-end text-black rounded-sm shadow-lg bg-white text-right">
          <span v-for="(n, $index) in labels.cardCvv" :key="$index">*</span>
        </div>
        <div class="relative w-24 h-12 flex justify-end">
          <img
            :src="card_type_src + cardType + '.png'"
            v-if="cardType"
            class="w-full h-full object-right-top object-contain"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Card',
  props: {
    labels: Object,
    fields: Object,
    isCardNumberMasked: Boolean,
    randomBackgrounds: {
      type: Boolean,
      default: true
    },
    backgroundImage: [String, Object]
  },
  data () {
    return {
      focusElementStyle: null,
      currentFocus: null,
      isFocused: false,
      isCardFlipped: false,
      amexCardPlaceholder: '#### ###### #####',
      dinersCardPlaceholder: '#### ###### ####',
      defaultCardPlaceholder: '#### #### #### ####',
      currentPlaceholder: '',
      chip_src: app_url + '/public/img/credit_card/chip.png',
      card_type_src: app_url + '/public/img/credit_card/',
    }
  },
  watch: {
    currentFocus () {
      if (this.currentFocus) {
        this.changeFocus()
      } else {
        this.focusElementStyle = null
      }
    },
    cardType () {
      this.changePlaceholder()
    }
  },
  mounted () {
    this.changePlaceholder()

    let self = this
    let fields = document.querySelectorAll('[data-card-field]')
    fields.forEach(element => {
      element.addEventListener('focus', () => {
        this.isFocused = true
        if (element.id === this.fields.cardYear || element.id === this.fields.cardMonth) {
          this.currentFocus = 'cardDate'
        } else {
          this.currentFocus = element.id
        }
        this.isCardFlipped = element.id === this.fields.cardCvv
      })
      element.addEventListener('blur', () => {
        this.isCardFlipped = !element.id === this.fields.cardCvv
        setTimeout(() => {
          if (!self.isFocused) {
            self.currentFocus = null
          }
        }, 300)
        self.isFocused = false
      })
    })
  },
  computed: {
    cardType () {
      let number = this.labels.cardNumber
      let re = new RegExp('^4')
      if (number.match(re) != null) return 'visa'

      re = new RegExp('^(34|37)')
      if (number.match(re) != null) return 'amex'

      re = new RegExp('^5[1-5]')
      if (number.match(re) != null) return 'mastercard'

      re = new RegExp('^6011')
      if (number.match(re) != null) return 'discover'

      re = new RegExp('^62')
      if (number.match(re) != null) return 'unionpay'

      re = new RegExp('^9792')
      if (number.match(re) != null) return 'troy'

      re = new RegExp('^3(?:0([0-5]|9)|[689]\\d?)\\d{0,11}')
      if (number.match(re) != null) return 'dinersclub'

      re = new RegExp('^35(2[89]|[3-8])')
      if (number.match(re) != null) return 'jcb'

      return '' // default type
    },
    currentCardBackground () {
      if (this.randomBackgrounds && !this.backgroundImage) { // TODO will be optimized
        let random = Math.floor(Math.random() * 25 + 1)
        return `https://raw.githubusercontent.com/muhammederdem/credit-card-form/master/src/assets/images/${random}.jpeg`
      } else if (this.backgroundImage) {
        return this.backgroundImage
      } else {
        return null
      }
    }
  },
  methods: {
    changeFocus () {
      let target = this.$refs[this.currentFocus]
      this.focusElementStyle = target ? {
        width: `${target.offsetWidth}px`,
        height: `${target.offsetHeight}px`,
        transform: `translateX(${target.offsetLeft}px) translateY(${target.offsetTop}px)`
      } : null
    },
    getIsNumberMasked (index, n) {
      return index > 4 && index < 14 && this.labels.cardNumber.length > index && n.trim() !== '' && this.isCardNumberMasked
    },
    changePlaceholder () {
      if (this.cardType === 'amex') {
        this.currentPlaceholder = this.amexCardPlaceholder
      } else if (this.cardType === 'dinersclub') {
        this.currentPlaceholder = this.dinersCardPlaceholder
      } else {
        this.currentPlaceholder = this.defaultCardPlaceholder
      }
      this.$nextTick(() => {
        this.changeFocus()
      })
    }
  }
}
</script>

<style scoped>
.card-item.-active .card-item__side.-front {
  transform: perspective(1000px) rotateY(180deg) rotateX(0deg) rotateZ(0deg);
}
.card-item.-active .card-item__side.-back {
  transform: perspective(1000px) rotateY(0) rotateX(0deg) rotateZ(0deg);
}

.card-item__side {
  border-radius: 15px;
  overflow: hidden;
  transform: perspective(2000px) rotateY(0deg) rotateX(0deg) rotate(0deg);
  transform-style: preserve-3d;
  transition: all 0.8s cubic-bezier(0.71, 0.03, 0.56, 0.85);
  backface-visibility: hidden;
  height: 100%;
}

.card-item__side.-back {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  transform: perspective(2000px) rotateY(-180deg) rotateX(0deg) rotate(0deg);
  z-index: 2;
  padding: 0;
  height: 100%;
}
</style>

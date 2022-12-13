<template>
  <SlideYUpTransition :duration="animationDuration">
    <div class="modal fade"
         @click.self="closeModal"
         :class="[{'show d-block': show}, {'d-none': !show}, {'modal-mini': type === 'mini'}]"
         v-show="show"
         tabindex="-1"
         role="dialog"
         :aria-hidden="!show">

      <div class="modal-dialog modal-dialog-centered"
           :class="[{'modal-notice': type === 'notice', [`modal-${size}`]: size}, modalClasses]">
        <div class="modal-content" :class="[gradient ? `bg-gradient-${gradient}` : '',modalContentClasses]">

          <div class="card-header" :class="[headerClasses]" v-if="$slots.header">
            <slot name="header"></slot>
            <slot name="close-button">
              <button type="button"
                      class="close"
                      v-if="showClose"
                      @click="closeModal"
                      data-dismiss="modal"
                      aria-label="Close">
                <span :aria-hidden="!show">×</span>
              </button>
            </slot>
          </div>

          <div class="modal-body" :class="bodyClasses">
            <slot></slot>
          </div>

          <div class="card-footer" :class="footerClasses" v-if="$slots.footer">
            <slot name="footer"></slot>
          </div>
        </div>
      </div>

    </div>
  </SlideYUpTransition>
</template>
<script>
  import { SlideYUpTransition } from "vue2-transitions";

  export default {
    name: "modal",
    components: {
      SlideYUpTransition
    },
    props: {
      show: Boolean,
      showClose: {
        type: Boolean,
        default: true
      },
      type: {
        type: String,
        default: "",
        validator(value) {
          let acceptedValues = ["", "notice", "mini"];
          return acceptedValues.indexOf(value) !== -1;
        },
        description: 'Modal type (notice|mini|"") '
      },
      modalClasses: {
        type: [Object, String],
        description: "Modal dialog css classes"
      },
      size: {
        type: String,
        description: 'Modal size',
        validator(value) {
          let acceptedValues = ["", "sm", "lg"];
          return acceptedValues.indexOf(value) !== -1;
        },
      },
      modalContentClasses: {
        type: [Object, String],
        description: "Modal dialog content css classes"
      },
      gradient: {
        type: String,
        description: "Modal gradient type (danger, primary etc)"
      },
      headerClasses: {
        type: [Object, String],
        description: "Modal Header css classes"
      },
      bodyClasses: {
        type: [Object, String],
        description: "Modal Body css classes"
      },
      footerClasses: {
        type: [Object, String],
        description: "Modal Footer css classes"
      },
      animationDuration: {
        type: Number,
        default: 500,
        description: "Modal transition duration"
      }
    },
    methods: {
      closeModal() {
        this.$emit("update:show", false);
        this.$emit("close");
      }
    },
    watch: {
      show(val) {
        let documentClasses = document.body.classList;
        if (val) {
          documentClasses.add('overflow-y-hidden', 'overflow-overlay');
        } else {
          documentClasses.remove('overflow-y-hidden', 'overflow-overlay');
        }
      }
    }
  };
</script>
<style>
  .modal.show {
    background-color: rgba(0, 0, 0, 0.3);
  }
</style>

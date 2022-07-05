<template>
  <div :class="[
       {'has-error': error},
       formClasses
       ]">
    <slot name="label">
      <label v-if="label" :class="labelClasses">
        {{label}}
        <span v-if="!notRequired" class="text-red ltr:ml-1 rtl:mr-1">*</span>
      </label>
    </slot>
    <div class="relative" :class="[
       {'form-icon': hasIcon},
       {'focused': focused},
       {'input-group-alternative': alternative},
       {'has-label': label || $slots.label},
       {'prepend-input-icon': prependIcon},
       inputGroupClasses
       ]">
      <div v-if="prependIcon || $slots.prepend" class="input-group-prepend absolute left-2 bottom-3 text-light-gray">
        <span class="input-group-text">
          <slot name="prepend">
            <span class="material-icons w-4 h-5 text-sm">{{ prependIcon }}</span>
          </slot>
        </span>
      </div>
      <slot v-bind="slotData">
        <input
          :value="value"
          :type="type"
          v-on="listeners"
          v-bind="$attrs"
          :valid="!error"
          :required="required"
          class="w-full text-sm px-3 py-2.5 mt-1 rounded-lg border border-light-gray text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple"
          :class="[{'is-valid': valid === true}, {'is-invalid': error}, inputClasses]">
      </slot>
      <div v-if="appendIcon || $slots.append" class="input-group-append absolute ltr:right-2 rtl:left-2 bottom-2 text-light-gray">
          <span class="input-group-text">
              <slot name="append">
                <span class="material-icons w-4 h-5 text-sm">{{ appendIcon }}</span>
              </slot>
          </span>
      </div>
      <slot name="infoBlock"></slot>
      <slot name="error">
        <div v-if="error" class="text-red text-sm mt-1 block"
            v-html="error">
        </div>
      </slot>
      <slot name="success">
        <div class="text-green text-sm mt-1" v-if="!error && valid">
          {{successMessage}}
        </div>
      </slot>
    </div>
    <slot name="error">
      <div v-if="footerError" class="text-red text-sm mt-1 block"
          v-html="footerError">
      </div>
    </slot>
  </div>
</template>
<script>
  export default {
    inheritAttrs: false,
    name: "base-input",
    props: {
      required: {
        type: Boolean,
        description: "Whether input is required (adds an asterix *)"
      },
      group: {
        type: Boolean,
        description: "Whether input is an input group (manual override in special cases)"
      },
      valid: {
        type: Boolean,
        description: "Whether is valid",
        default: undefined
      },
      alternative: {
        type: Boolean,
        description: "Whether input is of alternative layout"
      },
      label: {
        type: String,
        description: "Input label (text before input)"
      },
      error: {
        type: String,
        description: "Input error (below input)"
      },
      footerError: {
        type: String,
        description: "Input error (below input)"
      },
      successMessage: {
        type: String,
        description: "Input success message",
        default: 'Looks good!'
      },
      formClasses: {
        type: String,
        description: "Input form css classes"
      },
      labelClasses: {
        type: String,
        description: "Input label css classes",
        default: "text-black text-sm font-medium"
      },
      inputClasses: {
        type: String,
        description: "Input css classes"
      },
      inputGroupClasses: {
        type: String,
        description: "Input group css classes"
      },
      value: {
        type: [String, Number],
        description: "Input value"
      },
      type: {
        type: String,
        description: "Input type",
        default: "text"
      },
      appendIcon: {
        type: String,
        description: "Append icon (right)"
      },
      prependIcon: {
        type: String,
        description: "Prepend icon (left)"
      },
      notRequired: {
        type: Boolean,
        default: false
      }
    },
    data() {
      return {
        focused: false
      };
    },
    computed: {
      listeners() {
        return {
          ...this.$listeners,
          input: this.updateValue,
          focus: this.onFocus,
          blur: this.onBlur
        };
      },
      slotData() {
        return {
          focused: this.focused,
          error: this.error,
          ...this.listeners
        };
      },
      hasIcon() {
        const { append, prepend } = this.$slots;
        return (
          append !== undefined ||
          prepend !== undefined ||
          this.appendIcon !== undefined ||
          this.prependIcon !== undefined ||
          this.group
        );
      }
    },
    methods: {
      updateValue(evt) {
        let value = evt.target.value;
        this.$emit("input", value);
      },
      onFocus(evt) {
        this.focused = true;
        this.$emit("focus", evt);
      },
      onBlur(evt) {
        this.focused = false;
        this.$emit("blur", evt);
      }
    }
  };
</script>

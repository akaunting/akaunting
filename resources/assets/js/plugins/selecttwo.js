import Vue from 'vue';
/*
// Initialize the annoying-background directive.
export const SelectTwo = {
    twoWay: true,
    bind(el, binding, vnode) {
        var variblee = this;
        var selectbox = el.getAttribute('id');
        var binding2 = binding;
        var vnode2 = vnode;

        $(vnode.elm).select2()
            .on("select2:select", function(e) {
                //this.$set($(vnode.elm).val());
            }.bind($(vnode.elm)));
    },
    update: function(nv, ov) {
        $('#' + nv.id).trigger("change");
    }
}

// You can also make it available globally.
Vue.directive('select-two', SelectTwo);
*/


Vue.component('select2', {
    props: ['options', 'value'],
    template: '#select2-template',
    mounted: function () {
        var vm = this
        $(this.$el)
        // init select2
            .select2({ data: this.options })
            .val(this.value)
            .trigger('change')
            // emit event on change.
            .on('change', function () {
                vm.$emit('input', this.value)
            })
    },
    watch: {
        value: function (value) {
            // update value
            $(this.$el)
                .val(value)
                .trigger('change')
        },
        options: function (options) {
            // update options
            $(this.$el).empty().select2({ data: options })
        }
    },
    destroyed: function () {
        $(this.$el).off().select2('destroy')
    }
})

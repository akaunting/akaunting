<template>
    <!-- Akaunting's select: Element's el-select, which app.css styles as every form's select
         (.el-select .el-input .el-input__inner) and whose list is Element's, not the browser's -->
    <el-select
        :id="id"
        :value="value"
        class="w-full"
        @change="$emit('input', $event)"
    >
        <el-option
            v-for="option in options"
            :key="option[0]"
            :label="option[1]"
            :value="option[0]"
        ></el-option>
    </el-select>
</template>

<script>
import { Select, Option } from 'element-ui';

/**
 * A setting of the bar with a few values, as [value, label] pairs. The id lands on the input el-select renders, so
 * a label's for attribute finds it.
 */
export default {
    name: 'report-select',

    components: {
        [Select.name]: Select,
        [Option.name]: Option,
    },

    props: {
        id: {
            type: String,
            required: true,
        },

        value: {
            type: [String, Number],
            default: '',
        },

        options: {
            type: Array,
            default: () => [],
        },
    },
};
</script>

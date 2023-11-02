<template>
    <div class="tags-group group items-center contents">
        <div 
            v-if="filter.option"
            class="flex items-center bg-purple-lighter text-black border-0 mt-3 px-3 py-4 text-sm cursor-pointer el-tag el-tag--small el-tag-option"
        >
            {{ filter.option }}

            <i 
                v-if="! filter.operator && ! filter.value"
                class="mt-1 ltr:-right-2 rtl:left-0 rtl:right-0 el-tag__close el-icon-close"
                style="font-size: 16px;"
                @click="onDelete()"
            ></i>
        </div>

        <div
            v-if="filter.operator"
            class="flex items-center bg-purple-lighter text-black border-2 border-body border-l border-r border-t-0 border-b-0 mt-3 px-3 py-4 text-sm cursor-pointer el-tag el-tag--small el-tag-operator"
            style="margin-left:0; margin-right:0;"
        >
            <span v-if="filter.operator == '='" class="material-icons text-2xl">drag_handle</span>
            <span v-else-if="filter.operator == '><'" class="material-icons text-2xl transform rotate-90">height</span>
            <span v-else-if="filter.operator == '||'" class="material-icons text-2xl">multiple_stop</span>
            <span v-else class="w-5">
                <img :src="not_equal_image" class="w-5 h-5 object-cover block" />
            </span>

            <i 
                v-if="! filter.value" 
                class="mt-1 ltr:-right-2 rtl:left-0 rtl:right-0 el-tag__close el-icon-close"
                style="font-size: 16px;"
                @click="onDelete()"
            ></i>
        </div>

        <div v-if="filter.value" class="flex items-center bg-purple-lighter text-black border-0 mt-3 px-3 py-4 text-sm cursor-pointer el-tag el-tag--small el-tag-value">
            <span v-if="Array.isArray(filter.value)">
                <span  v-for="(multiple_filter, index) in filter.value">
                    {{ (index == 0) ? multiple_filter.value : ', ' + multiple_filter.value }}
                </span>
            </span>
            <span v-else>
                {{ filter.value }}
            </span>

            <i 
                class="mt-1 ltr:-right-2 rtl:left-0 rtl:right-0 el-tag__close el-icon-close"
                style="font-size: 16px;"
                @click="onDelete()"
            ></i>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'filtered-list',

        props: {
            filter: {
                type: Array|Object,
                default: () => {},
                description: 'List of filter columns'
            },

            index: {
                type: Number,
                default: 0,
                description: 'Filter index',
            },
        },

        methods: {
            onDelete() {
                this.$emit('delete', this.index);
            },
        },
    };
</script>

<style>
    .searh-field .tags-group:hover > span {
        background:#cbd4de;
        background-color: #cbd4de;
        border-color: #cbd4de;
    }

    .searh-field .el-tag.el-tag--primary .el-tag__close.el-icon-close {
        color: #8898aa;
        margin-top: -3px;
    }

    .searh-field .el-tag.el-tag--primary .el-tag__close.el-icon-close:hover {
        background-color: transparent;
    }

    html[dir='ltr'] .searh-field .el-tag-option {
        border-radius: 0.50rem 0 0 0.50rem;
    }

    html[dir='rtl'] .searh-field .el-tag-option {
        border-radius: 0 0.5rem 0.5rem 0;
    }

    .searh-field .el-tag-operator {
        border-radius: 0;
        margin-left: -1px;
        margin-right: -1px;
    }

    html[dir='ltr'] .searh-field .el-tag-value {
        border-radius: 0 0.50rem 0.50rem 0;
        margin-right: 10px;
    }

    html[dir='rtl'] .searh-field .el-tag-value {
        border-radius: 0.5rem 0 0 0.5rem;
        margin-left: 10px;
    }

    html[dir='rtl'] .searh-field .el-tag-operator {
        border-radius: 0;
    }
</style>

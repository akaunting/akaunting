<template>
    <base-input
        :label="title"
        :name="name"
        :readonly="readonly"
        :disabled="disabled"
        :class="[
            {'readonly': readonly},
            {'disabled': disabled},
            formClasses
        ]"
        :error="formError">
        <span v-if="Array.isArray(selectOptions)">
            <el-select v-model="real_model" @input="change" :disabled="disabled" filterable v-if="(disabled) && !multiple && !collapse"
                :placeholder="placeholder">
                <div v-if="addNew.status && options.length != 0" class="el-select-dropdown__wrap" slot="empty">
                    <p class="el-select-dropdown__empty">
                        {{ noMatchingDataText }}
                    </p>
                    <ul class="el-scrollbar__view el-select-dropdown__list">
                        <li class="el-select-dropdown__item el-select__footer">
                            <div @click="onAddItem">
                                <i class="fas fa-plus"></i>
                                <span>
                                    {{ add_new_text }}
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>

                <div v-else-if="addNew.status && options.length == 0" slot="empty">
                    <p class="el-select-dropdown__empty">
                        {{ noDataText }}
                    </p>
                    <ul class="el-scrollbar__view el-select-dropdown__list">
                        <li class="el-select-dropdown__item el-select__footer">
                            <div @click="onAddItem">
                                <i class="fas fa-plus"></i>
                                <span>
                                    {{ add_new_text }}
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>

                <template slot="prefix">
                    <span class="el-input__suffix-inner el-select-icon">
                        <i :class="'select-icon-position el-input__icon fa fa-' + icon"></i>
                    </span>
                </template>

                <el-option v-if="!group" v-for="(option, key) in selectOptions"
                :key="key"
                :label="option.value"
                :value="option.key">
                    <span class="float-left">{{ option.value }}</span>
                    <span class="badge badge-pill badge-success float-right mt-2" v-if="new_options[option.key]">{{ new_text }}</span>
                </el-option>

                <!-- Todo sortable -->
                <el-option-group
                    v-if="group"
                    v-for="(options, name) in selectOptions"
                    :key="name"
                    :label="name">
                    <el-option
                        v-for="(label, value) in options"
                        :key="value"
                        :label="label"
                        :value="value">
                        <span class="float-left">{{ label }}</span>
                        <span class="badge badge-pill badge-success float-right mt-2" v-if="new_options[value]">{{ new_text }}</span>
                    </el-option>
                </el-option-group>

                <el-option v-if="addNew.status && options.length != 0" class="el-select__footer" :disabled="true"  :value="add_new">
                    <div @click="onAddItem">
                        <i class="fas fa-plus"></i>
                        <span>
                            {{ add_new_text }}
                        </span>
                    </div>
                </el-option>
            </el-select>

            <el-select v-model="real_model" @input="change" :disabled="disabled" filterable v-if="!disabled && !multiple"
                :placeholder="placeholder">
                <div v-if="addNew.status && options.length != 0" class="el-select-dropdown__wrap" slot="empty">
                    <p class="el-select-dropdown__empty">
                        {{ noMatchingDataText }}
                    </p>
                    <ul class="el-scrollbar__view el-select-dropdown__list">
                        <li class="el-select-dropdown__item el-select__footer">
                            <div @click="onAddItem">
                                <i class="fas fa-plus"></i>
                                <span>
                                    {{ add_new_text }}
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>

                <div v-else-if="addNew.status && options.length == 0" slot="empty">
                    <p class="el-select-dropdown__empty">
                        {{ noDataText }}
                    </p>
                    <ul class="el-scrollbar__view el-select-dropdown__list">
                        <li class="el-select-dropdown__item el-select__footer">
                            <div @click="onAddItem">
                                <i class="fas fa-plus"></i>
                                <span>
                                    {{ add_new_text }}
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>

                <template slot="prefix">
                    <span class="el-input__suffix-inner el-select-icon">
                        <i :class="'select-icon-position el-input__icon fa fa-' + icon"></i>
                    </span>
                </template>

                <el-option v-if="!group" v-for="(option, key) in selectOptions"
                :key="key"
                :label="option.value"
                :value="option.key">
                    <span class="float-left">{{ option.value }}</span>
                    <span class="badge badge-pill badge-success float-right mt-2" v-if="new_options[option.key]">{{ new_text }}</span>
                </el-option>

                <el-option-group
                    v-if="group"
                    v-for="(options, name) in selectOptions"
                    :key="name"
                    :label="name">
                    <el-option
                        v-for="(label, value) in options"
                        :key="value"
                        :label="label"
                        :value="value">
                        <span class="float-left">{{ label }}</span>
                        <span class="badge badge-pill badge-success float-right mt-2" v-if="new_options[value]">{{ new_text }}</span>
                    </el-option>
                </el-option-group>

                <el-option v-if="addNew.status && options.length != 0" class="el-select__footer" :disabled="true" :value="add_new">
                    <div @click="onAddItem">
                        <i class="fas fa-plus"></i>
                        <span>
                            {{ add_new_text }}
                        </span>
                    </div>
                </el-option>
            </el-select>

            <el-select v-model="real_model" @input="change" filterable v-if="!disabled && multiple && !collapse" multiple
                :placeholder="placeholder">
                <div v-if="addNew.status && options.length != 0" class="el-select-dropdown__wrap" slot="empty">
                    <p class="el-select-dropdown__empty">
                        {{ noMatchingDataText }}
                    </p>
                    <ul class="el-scrollbar__view el-select-dropdown__list">
                        <li class="el-select-dropdown__item el-select__footer">
                            <div @click="onAddItem">
                                <i class="fas fa-plus"></i>
                                <span>
                                    {{ add_new_text }}
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>

                <div v-else-if="addNew.status && options.length == 0" slot="empty">
                    <p class="el-select-dropdown__empty">
                        {{ noDataText }}
                    </p>
                    <ul class="el-scrollbar__view el-select-dropdown__list">
                        <li class="el-select-dropdown__item el-select__footer">
                            <div @click="onAddItem">
                                <i class="fas fa-plus"></i>
                                <span>
                                    {{ add_new_text }}
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>

                <template slot="prefix">
                    <span class="el-input__suffix-inner el-select-icon">
                        <i :class="'select-icon-position el-input__icon fa fa-' + icon"></i>
                    </span>
                </template>

                <el-option v-if="!group" v-for="(option, key) in selectOptions"
                :key="key"
                :label="option.value"
                :value="option.key">
                    <span class="float-left">{{ option.value }}</span>
                    <span class="badge badge-pill badge-success float-right mt-2" v-if="new_options[option.key]">{{ new_text }}</span>
                </el-option>

                <!-- Todo sortable -->
                <el-option-group
                    v-if="group"
                    v-for="(options, name) in selectOptions"
                    :key="name"
                    :label="name">
                    <el-option
                        v-for="(label, value) in options"
                        :key="value"
                        :label="label"
                        :value="value">
                        <span class="float-left">{{ label }}</span>
                        <span class="badge badge-pill badge-success float-right mt-2" v-if="new_options[value]">{{ new_text }}</span>
                    </el-option>
                </el-option-group>

                <el-option v-if="addNew.status && options.length != 0" class="el-select__footer" :disabled="true" :value="add_new">
                    <div @click="onAddItem">
                        <i class="fas fa-plus"></i>
                        <span>
                            {{ add_new_text }}
                        </span>
                    </div>
                </el-option>
            </el-select>

            <el-select v-model="real_model" @input="change" filterable disabled v-if="disabled && multiple && !collapse" multiple
                :placeholder="placeholder">
                <div v-if="addNew.status && options.length != 0" class="el-select-dropdown__wrap" slot="empty">
                    <p class="el-select-dropdown__empty">
                        {{ noMatchingDataText }}
                    </p>
                    <ul class="el-scrollbar__view el-select-dropdown__list">
                        <li class="el-select-dropdown__item el-select__footer">
                            <div @click="onAddItem">
                                <i class="fas fa-plus"></i>
                                <span>
                                    {{ add_new_text }}
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>

                <div v-else-if="addNew.status && options.length == 0" slot="empty">
                    <p class="el-select-dropdown__empty">
                        {{ noDataText }}
                    </p>
                    <ul class="el-scrollbar__view el-select-dropdown__list">
                        <li class="el-select-dropdown__item el-select__footer">
                            <div @click="onAddItem">
                                <i class="fas fa-plus"></i>
                                <span>
                                    {{ add_new_text }}
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>

                <template slot="prefix">
                    <span class="el-input__suffix-inner el-select-icon">
                        <i :class="'select-icon-position el-input__icon fa fa-' + icon"></i>
                    </span>
                </template>

                <el-option v-if="!group" v-for="(option, key) in selectOptions"
                :key="key"
                :label="option.value"
                :value="option.key">
                    <span class="float-left">{{ option.value }}</span>
                    <span class="badge badge-pill badge-success float-right mt-2" v-if="new_options[option.key]">{{ new_text }}</span>
                </el-option>

                <!-- Todo sortable -->
                <el-option-group
                    v-if="group"
                    v-for="(options, name) in selectOptions"
                    :key="name"
                    :label="name">
                    <el-option
                        v-for="(label, value) in options"
                        :key="value"
                        :label="label"
                        :value="value">
                        <span class="float-left">{{ label }}</span>
                        <span class="badge badge-pill badge-success float-right mt-2" v-if="new_options[value]">{{ new_text }}</span>
                    </el-option>
                </el-option-group>

                <el-option v-if="addNew.status && options.length != 0" class="el-select__footer" :disabled="true" :value="add_new">
                    <div @click="onAddItem">
                        <i class="fas fa-plus"></i>
                        <span>
                            {{ add_new_text }}
                        </span>
                    </div>
                </el-option>
            </el-select>

            <el-select v-model="real_model" @input="change" filterable v-if="!disabled && multiple && collapse" multiple collapse-tags
                :placeholder="placeholder">
                <div v-if="addNew.status && options.length != 0" class="el-select-dropdown__wrap" slot="empty">
                    <p class="el-select-dropdown__empty">
                        {{ noMatchingDataText }}
                    </p>
                    <ul class="el-scrollbar__view el-select-dropdown__list">
                        <li class="el-select-dropdown__item el-select__footer">
                            <div @click="onAddItem">
                                <i class="fas fa-plus"></i>
                                <span>
                                    {{ add_new_text }}
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>

                <div v-else-if="addNew.status && options.length == 0" slot="empty">
                    <p class="el-select-dropdown__empty">
                        {{ noDataText }}
                    </p>
                    <ul class="el-scrollbar__view el-select-dropdown__list">
                        <li class="el-select-dropdown__item el-select__footer">
                            <div @click="onAddItem">
                                <i class="fas fa-plus"></i>
                                <span>
                                    {{ add_new_text }}
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>

                <template slot="prefix">
                    <span class="el-input__suffix-inner el-select-icon">
                        <i :class="'select-icon-position el-input__icon fa fa-' + icon"></i>
                    </span>
                </template>

                <el-option v-if="!group" v-for="(option, key) in selectOptions"
                :key="key"
                :label="option.value"
                :value="option.key">
                    <span class="float-left">{{ option.value }}</span>
                    <span class="badge badge-pill badge-success float-right mt-2" v-if="new_options[option.key]">{{ new_text }}</span>
                </el-option>

                <!-- Todo sortable -->
                <el-option-group
                    v-if="group"
                    v-for="(options, name) in selectOptions"
                    :key="name"
                    :label="name">
                    <el-option
                        v-for="(label, value) in options"
                        :key="value"
                        :label="label"
                        :value="value">
                        <span class="float-left">{{ label }}</span>
                        <span class="badge badge-pill badge-success float-right mt-2" v-if="new_options[value]">{{ new_text }}</span>
                    </el-option>
                </el-option-group>

                <el-option v-if="addNew.status && options.length != 0" class="el-select__footer" :disabled="true" :value="add_new">
                    <div @click="onAddItem">
                        <i class="fas fa-plus"></i>
                        <span>
                            {{ add_new_text }}
                        </span>
                    </div>
                </el-option>
            </el-select>

            <component v-bind:is="add_new_html" @submit="onSubmit" @cancel="onCancel"></component>

            <span slot="infoBlock" class="badge badge-success badge-resize float-right" v-if="new_options[real_model]">{{ new_text }}</span>

            <select :name="name" v-model="real_model" class="d-none">
                <option v-for="(label, value) in selectOptions" :key="value" :value="value">{{ label }}</option>
            </select>
        </span>

        <span v-else>
            <el-select v-model="real_model" @input="change" :disabled="disabled" filterable v-if="(disabled) && !multiple && !collapse"
                :placeholder="placeholder">
                <div v-if="addNew.status && options.length != 0" class="el-select-dropdown__wrap" slot="empty">
                    <p class="el-select-dropdown__empty">
                        {{ noMatchingDataText }}
                    </p>
                    <ul class="el-scrollbar__view el-select-dropdown__list">
                        <li class="el-select-dropdown__item el-select__footer">
                            <div @click="onAddItem">
                                <i class="fas fa-plus"></i>
                                <span>
                                    {{ add_new_text }}
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>

                <div v-else-if="addNew.status && options.length == 0" slot="empty">
                    <p class="el-select-dropdown__empty">
                        {{ noDataText }}
                    </p>
                    <ul class="el-scrollbar__view el-select-dropdown__list">
                        <li class="el-select-dropdown__item el-select__footer">
                            <div @click="onAddItem">
                                <i class="fas fa-plus"></i>
                                <span>
                                    {{ add_new_text }}
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>

                <template slot="prefix">
                    <span class="el-input__suffix-inner el-select-icon">
                        <i :class="'select-icon-position el-input__icon fa fa-' + icon"></i>
                    </span>
                </template>

                <el-option v-if="!group" v-for="(label, value) in selectOptions"
                    :key="value"
                    :label="label"
                    :value="value">
                    <span class="float-left">{{ label }}</span>
                    <span class="badge badge-pill badge-success float-right mt-2" v-if="new_options[value]">{{ new_text }}</span>
                </el-option>

                <!-- Todo sortable -->
                <el-option-group
                    v-if="group"
                    v-for="(options, name) in selectOptions"
                    :key="name"
                    :label="name">
                    <el-option
                        v-for="(label, value) in options"
                        :key="value"
                        :label="label"
                        :value="value">
                        <span class="float-left">{{ label }}</span>
                        <span class="badge badge-pill badge-success float-right mt-2" v-if="new_options[value]">{{ new_text }}</span>
                    </el-option>
                </el-option-group>

                <el-option v-if="addNew.status && options.length != 0" class="el-select__footer" :disabled="true"  :value="add_new">
                    <div @click="onAddItem">
                        <i class="fas fa-plus"></i>
                        <span>
                            {{ add_new_text }}
                        </span>
                    </div>
                </el-option>
            </el-select>

            <el-select v-model="real_model" @input="change" filterable v-if="!Array.isArray(selectOptions) && !disabled && !multiple"
                :placeholder="placeholder">
                <div v-if="addNew.status && options.length != 0" class="el-select-dropdown__wrap" slot="empty">
                    <p class="el-select-dropdown__empty">
                        {{ noMatchingDataText }}
                    </p>
                    <ul class="el-scrollbar__view el-select-dropdown__list">
                        <li class="el-select-dropdown__item el-select__footer">
                            <div @click="onAddItem">
                                <i class="fas fa-plus"></i>
                                <span>
                                    {{ add_new_text }}
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>

                <div v-else-if="addNew.status && options.length == 0" slot="empty">
                    <p class="el-select-dropdown__empty">
                        {{ noDataText }}
                    </p>
                    <ul class="el-scrollbar__view el-select-dropdown__list">
                        <li class="el-select-dropdown__item el-select__footer">
                            <div @click="onAddItem">
                                <i class="fas fa-plus"></i>
                                <span>
                                    {{ add_new_text }}
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>

                <template slot="prefix">
                    <span class="el-input__suffix-inner el-select-icon">
                        <i :class="'select-icon-position el-input__icon fa fa-' + icon"></i>
                    </span>
                </template>

                <el-option v-if="!group" v-for="(label, value) in selectOptions"
                    :key="value"
                    :label="label"
                    :value="value">
                    <span class="float-left">{{ label }}</span>
                    <span class="badge badge-pill badge-success float-right mt-2" v-if="new_options[value]">{{ new_text }}</span>
                </el-option>

                <el-option-group
                    v-if="group"
                    v-for="(options, name) in selectOptions"
                    :key="name"
                    :label="name">
                    <el-option
                        v-for="(label, value) in options"
                        :key="value"
                        :label="label"
                        :value="value">
                        <span class="float-left">{{ label }}</span>
                        <span class="badge badge-pill badge-success float-right mt-2" v-if="new_options[value]">{{ new_text }}</span>
                    </el-option>
                </el-option-group>

                <el-option v-if="addNew.status && options.length != 0" class="el-select__footer" :disabled="true" :value="add_new">
                    <div @click="onAddItem">
                        <i class="fas fa-plus"></i>
                        <span>
                            {{ add_new_text }}
                        </span>
                    </div>
                </el-option>
            </el-select>

            <el-select v-model="real_model" @input="change" filterable v-if="!disabled && multiple && !collapse" multiple
                :placeholder="placeholder">
                <div v-if="addNew.status && options.length != 0" class="el-select-dropdown__wrap" slot="empty">
                    <p class="el-select-dropdown__empty">
                        {{ noMatchingDataText }}
                    </p>
                    <ul class="el-scrollbar__view el-select-dropdown__list">
                        <li class="el-select-dropdown__item el-select__footer">
                            <div @click="onAddItem">
                                <i class="fas fa-plus"></i>
                                <span>
                                    {{ add_new_text }}
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>

                <div v-else-if="addNew.status && options.length == 0" slot="empty">
                    <p class="el-select-dropdown__empty">
                        {{ noDataText }}
                    </p>
                    <ul class="el-scrollbar__view el-select-dropdown__list">
                        <li class="el-select-dropdown__item el-select__footer">
                            <div @click="onAddItem">
                                <i class="fas fa-plus"></i>
                                <span>
                                    {{ add_new_text }}
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>

                <template slot="prefix">
                    <span class="el-input__suffix-inner el-select-icon">
                        <i :class="'select-icon-position el-input__icon fa fa-' + icon"></i>
                    </span>
                </template>

                <el-option v-if="!group" v-for="(label, value) in selectOptions"
                    :key="value"
                    :label="label"
                    :value="value">
                    <span class="float-left">{{ label }}</span>
                    <span class="badge badge-pill badge-success float-right mt-2" v-if="new_options[value]">{{ new_text }}</span>
                </el-option>

                <!-- Todo sortable -->
                <el-option-group
                    v-if="group"
                    v-for="(options, name) in selectOptions"
                    :key="name"
                    :label="name">
                    <el-option
                        v-for="(label, value) in options"
                        :key="value"
                        :label="label"
                        :value="value">
                        <span class="float-left">{{ label }}</span>
                        <span class="badge badge-pill badge-success float-right mt-2" v-if="new_options[value]">{{ new_text }}</span>
                    </el-option>
                </el-option-group>

                <el-option v-if="addNew.status && options.length != 0" class="el-select__footer" :disabled="true" :value="add_new">
                    <div @click="onAddItem">
                        <i class="fas fa-plus"></i>
                        <span>
                            {{ add_new_text }}
                        </span>
                    </div>
                </el-option>
            </el-select>

            <el-select v-model="real_model" @input="change" filterable disabled v-if="disabled && multiple && !collapse" multiple
                :placeholder="placeholder">
                <div v-if="addNew.status && options.length != 0" class="el-select-dropdown__wrap" slot="empty">
                    <p class="el-select-dropdown__empty">
                        {{ noMatchingDataText }}
                    </p>
                    <ul class="el-scrollbar__view el-select-dropdown__list">
                        <li class="el-select-dropdown__item el-select__footer">
                            <div @click="onAddItem">
                                <i class="fas fa-plus"></i>
                                <span>
                                    {{ add_new_text }}
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>

                <div v-else-if="addNew.status && options.length == 0" slot="empty">
                    <p class="el-select-dropdown__empty">
                        {{ noDataText }}
                    </p>
                    <ul class="el-scrollbar__view el-select-dropdown__list">
                        <li class="el-select-dropdown__item el-select__footer">
                            <div @click="onAddItem">
                                <i class="fas fa-plus"></i>
                                <span>
                                    {{ add_new_text }}
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>

                <template slot="prefix">
                    <span class="el-input__suffix-inner el-select-icon">
                        <i :class="'select-icon-position el-input__icon fa fa-' + icon"></i>
                    </span>
                </template>

                <el-option v-if="!group" v-for="(label, value) in selectOptions"
                    :key="value"
                    :label="label"
                    :value="value">
                    <span class="float-left">{{ label }}</span>
                    <span class="badge badge-pill badge-success float-right mt-2" v-if="new_options[value]">{{ new_text }}</span>
                </el-option>

                <!-- Todo sortable -->
                <el-option-group
                    v-if="group"
                    v-for="(options, name) in selectOptions"
                    :key="name"
                    :label="name">
                    <el-option
                        v-for="(label, value) in options"
                        :key="value"
                        :label="label"
                        :value="value">
                        <span class="float-left">{{ label }}</span>
                        <span class="badge badge-pill badge-success float-right mt-2" v-if="new_options[value]">{{ new_text }}</span>
                    </el-option>
                </el-option-group>

                <el-option v-if="addNew.status && options.length != 0" class="el-select__footer" :disabled="true" :value="add_new">
                    <div @click="onAddItem">
                        <i class="fas fa-plus"></i>
                        <span>
                            {{ add_new_text }}
                        </span>
                    </div>
                </el-option>
            </el-select>

            <el-select v-model="real_model" @input="change" filterable v-if="!disabled && multiple && collapse" multiple collapse-tags
                :placeholder="placeholder">
                <div v-if="addNew.status && options.length != 0" class="el-select-dropdown__wrap" slot="empty">
                    <p class="el-select-dropdown__empty">
                        {{ noMatchingDataText }}
                    </p>
                    <ul class="el-scrollbar__view el-select-dropdown__list">
                        <li class="el-select-dropdown__item el-select__footer">
                            <div @click="onAddItem">
                                <i class="fas fa-plus"></i>
                                <span>
                                    {{ add_new_text }}
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>

                <div v-else-if="addNew.status && options.length == 0" slot="empty">
                    <p class="el-select-dropdown__empty">
                        {{ noDataText }}
                    </p>
                    <ul class="el-scrollbar__view el-select-dropdown__list">
                        <li class="el-select-dropdown__item el-select__footer">
                            <div @click="onAddItem">
                                <i class="fas fa-plus"></i>
                                <span>
                                    {{ add_new_text }}
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>

                <template slot="prefix">
                    <span class="el-input__suffix-inner el-select-icon">
                        <i :class="'select-icon-position el-input__icon fa fa-' + icon"></i>
                    </span>
                </template>

                <el-option v-if="!group" v-for="(label, value) in selectOptions"
                    :key="value"
                    :label="label"
                    :value="value">
                    <span class="float-left">{{ label }}</span>
                    <span class="badge badge-pill badge-success float-right mt-2" v-if="new_options[value]">{{ new_text }}</span>
                </el-option>

                <!-- Todo sortable -->
                <el-option-group
                    v-if="group"
                    v-for="(options, name) in selectOptions"
                    :key="name"
                    :label="name">
                    <el-option
                        v-for="(label, value) in options"
                        :key="value"
                        :label="label"
                        :value="value">
                        <span class="float-left">{{ label }}</span>
                        <span class="badge badge-pill badge-success float-right mt-2" v-if="new_options[value]">{{ new_text }}</span>
                    </el-option>
                </el-option-group>

                <el-option v-if="addNew.status && options.length != 0" class="el-select__footer" :disabled="true" :value="add_new">
                    <div @click="onAddItem">
                        <i class="fas fa-plus"></i>
                        <span>
                            {{ add_new_text }}
                        </span>
                    </div>
                </el-option>
            </el-select>

            <component v-bind:is="add_new_html" @submit="onSubmit" @cancel="onCancel"></component>

            <span slot="infoBlock" class="badge badge-success badge-resize float-right" v-if="new_options[real_model]">{{ new_text }}</span>

            <select :name="name" v-model="real_model" class="d-none">
                <option v-for="(label, value) in selectOptions" :key="value" :value="value">{{ label }}</option>
            </select>
        </span>
    </base-input>
</template>

<script>
import Vue from 'vue';

import { Select, Option, OptionGroup, ColorPicker } from 'element-ui';

import AkauntingModalAddNew from './AkauntingModalAddNew';
import AkauntingModal from './AkauntingModal';
import AkauntingMoney from './AkauntingMoney';
import AkauntingRadioGroup from './forms/AkauntingRadioGroup';
import AkauntingSelect from './AkauntingSelect';
import AkauntingDate from './AkauntingDate';
import AkauntingRecurring from './AkauntingRecurring';

import Form from './../plugins/form';

export default {
    name: "akaunting-select",

    components: {
        [Select.name]: Select,
        [Option.name]: Option,
        [OptionGroup.name]: OptionGroup,
        [ColorPicker.name]: ColorPicker,
        AkauntingModalAddNew,
        AkauntingRadioGroup,
        AkauntingSelect,
        AkauntingModal,
        AkauntingMoney,
        AkauntingDate,
        AkauntingRecurring,
    },

    props: {
        title: {
            type: String,
            default: '',
            description: "Selectbox label text"
        },
        placeholder: {
            type: String,
            default: '',
            description: "Selectbox input placeholder text"
        },
        formClasses: {
            type: Array,
            default: null,
            description: "Selectbox input class name"
        },
        formError: {
            type: String,
            default: null,
            description: "Selectbox input error message"
        },
        name: {
            type: String,
            default: null,
            description: "Selectbox attribute name"
        },
        value: {
            type: [String, Number, Array, Object],
            default: '',
            description: "Selectbox selected value"
        },
        options: null,

        option_sortable: {
            type: String,
            default: 'value',
            description: "Option Sortable type (key|value)"
        },

        model: {
            type: [String, Number, Array, Object],
            default: '',
            description: "Selectbox selected model"
        },

        icon: {
            type: String,
            description: "Prepend icon (left)"
        },

        addNew: {
            type: Object,
            default: function () {
                return {
                    text: 'Add New Item',
                    status: false,
                    path: null,
                    type: 'modal', // modal, inline
                    field: {},
                    new_text: 'New',
                    buttons: {}
                };
            },
            description: "Selectbox Add New Item Feature"
        },

        group: {
            type: Boolean,
            default: false,
            description: "Selectbox option group status"
        },
        multiple: {
            type: Boolean,
            default: false,
            description: "Multible feature status"
        },
        readonly: {
            type: Boolean,
            default: false,
            description: "Selectbox disabled status"
        },
        disabled: {
            type: Boolean,
            default: false,
            description: "Selectbox disabled status"
        },
        collapse:  {
            type: Boolean,
            default: false,
            description: "Selectbox collapse status"
        },

        noDataText: {
            type: String,
            default: 'No Data',
            description: "Selectbox empty options message"
        },
        noMatchingDataText: {
            type: String,
            default: 'No Matchign Data',
            description: "Selectbox search option not found item message"
        }
    },

    data() {
        return {
            add_new: {
                text: this.addNew.text,
                show: false,
                path: this.addNew.path,
                type: this.addNew.type, // modal, inline
                field: this.addNew.field,
                buttons: this.addNew.buttons
            },
            add_new_text: this.addNew.text,
            new_text: this.addNew.new_text,
            selectOptions: this.options,
            real_model: this.model,
            add_new_html: '',
            form: {},
            new_options: false,
        }
    },

    created() {
        /*
        if (this.group != true && Object.keys(this.options).length) {
            let sortable = [];
            let option_sortable = this.option_sortable;

            for (var option_key in this.options) {
                sortable.push({
                    'key' : option_key,
                    'value': this.options[option_key]
                });
            }

            if (option_sortable == 'value') {
                sortable.sort(function(a, b) {
                    var sortableA = a[option_sortable].toUpperCase();
                    var sortableB = b[option_sortable].toUpperCase();

                    let comparison = 0;

                    if (sortableA > sortableB) {
                        comparison = 1;
                    } else if (sortableA < sortableB) {
                        comparison = -1;
                    }

                    return comparison;
                });
            }

            this.options = sortable;
        }
        */

        this.new_options = {};
    },

    mounted() {
        this.real_model = this.value;

        if (this.model.length) {
            if (eval(this.model) !== undefined) {
                this.real_model = eval(this.model);
            } else {
                this.real_model = this.model;
            }
        }

        if (this.multiple && !this.real_model.length) {
            this.real_model = [];
        }

        this.$emit('interface', this.real_model);
    },

    methods: {
        change() {
            if (typeof(this.real_model) === 'object' && typeof(this.real_model.type) !== 'undefined') {
                return false;
            }

            this.$emit('interface', this.real_model);
            this.$emit('change', this.real_model);
        },

        async onAddItem() {
            // Get Select Input value
            if (this.title) {
                var value = this.$children[0].$children[0].$children[0].$refs.input.value;
            } else {
                var value = this.$children[0].$children[0].$refs.input.value;
            }

            if (this.add_new.type == 'inline') {
                if (value === '') {
                    return false;
                }

                await this.addInline(value);
            } else {
                await this.onModal(value);
            }
        },

        addInline(value) {
        },

        onModal(value) {
            let add_new = this.add_new;

            window.axios.get(this.add_new.path)
            .then(response => {
                add_new.show = true;
                add_new.html = response.data.html;

                this.$children[0].$children[0].visible = false;

                this.add_new_html = Vue.component('add-new-component', function (resolve, reject) {
                    resolve({
                        template: '<div><akaunting-modal-add-new :show="add_new.show" @submit="onSubmit" @cancel="onCancel" :buttons="add_new.buttons" :title="add_new.text" :is_component=true :message="add_new.html"></akaunting-modal-add-new></div>',

                        components: {
                            AkauntingModalAddNew,
                            AkauntingRadioGroup,
                            AkauntingSelect,
                            AkauntingModal,
                            AkauntingMoney,
                            AkauntingDate,
                            AkauntingRecurring,
                            [ColorPicker.name]: ColorPicker,
                        },

                        data: function () {
                            return {
                                add_new: add_new,
                            }
                        },

                        methods: {
                            onSubmit(event) {
                                this.$emit('submit', event);
                            },

                            onCancel(event) {
                                this.$emit('cancel', event);
                            }
                        }
                    })
                });
            })
            .catch(e => {
                this.errors.push(e);
            })
            .finally(function () {
                // always executed
            });
        },

        onSubmit(event) {
            this.form = event;

            this.loading = true;

            let data = this.form.data();

            FormData.prototype.appendRecursive = function(data, wrapper = null) {
                for(var name in data) {
                    if (wrapper) {
                        if ((typeof data[name] == 'object' || data[name].constructor === Array) && ((data[name] instanceof File != true ) && (data[name] instanceof Blob != true))) {
                            this.appendRecursive(data[name], wrapper + '[' + name + ']');
                        } else {
                            this.append(wrapper + '[' + name + ']', data[name]);
                        }
                    } else {
                        if ((typeof data[name] == 'object' || data[name].constructor === Array) && ((data[name] instanceof File != true ) && (data[name] instanceof Blob != true))) {
                            this.appendRecursive(data[name], name);
                        } else {
                            this.append(name, data[name]);
                        }
                    }
                }
            };

            let form_data = new FormData();
            form_data.appendRecursive(data);

            window.axios({
                method: this.form.method,
                url: this.form.action,
                data: form_data,
                headers: {
                    'X-CSRF-TOKEN': window.Laravel.csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(response => {
                this.form.loading = false;

                if (response.data.success) {
                    if (!Object.keys(this.options).length) {
                        this.selectOptions =  {};
                    }

                    this.selectOptions[response.data.data[this.add_new.field.key]] = response.data.data[this.add_new.field.value];
                    this.new_options[response.data.data[this.add_new.field.key]] = response.data.data[this.add_new.field.value];

                    if (this.multiple) {
                        this.real_model.push(response.data.data[this.add_new.field.key].toString());
                    } else {
                        this.real_model = response.data.data[this.add_new.field.key].toString();
                    }

                    this.add_new.show = false;

                    this.add_new.html = '';
                    this.add_new_html = null;

                    this.$emit('new', response.data.data);

                    this.change();
                }
            })
            .catch(error => {
                this.form.loading = false;

                this.form.onFail(error);

                this.method_show_html = error.message;
            });
        },

        onCancel() {
            this.add_new.show = false;
            this.add_new.html = null;
            this.add_new_html = null;
        },

        addModal() {

        },
    },

    watch: {
        options: function (options) {
            // update options
            this.selectOptions = options;

            if (Object.keys(this.new_options).length) {
                if (!Object.keys(this.options).length) {
                    this.selectOptions =  {};
                }

                for (let [key, value] of Object.entries(this.new_options)) {
                    if (!this.selectOptions[key]) {
                        this.selectOptions[key] = value;
                    }
                }
            }
        },

        real_model: function (value) {
            if (this.multiple) {
                return;
            }

            this.change();
        },

        value: function (value) {
            if (this.multiple) {
                this.real_model = value;
            } else {
                this.real_model = value.toString();
            }
        },

        model: function (value) {
            if (this.multiple) {
                this.real_model = value;
            } else {
                this.real_model = value.toString();
            }
        }
    },
}
</script>

<style scoped>
    .form-group .modal {
        z-index: 3050;
    }

    .el-select-dropdown__empty {
        padding: 10px 0 0 !important;
    }

    .el-select__footer {
        text-align: center;
        border-top: 1px solid #dee2e6;
        padding: 0px;
        cursor: pointer;
        color: #3c3f72;
        font-weight: bold;
        height: 38px;
        line-height: 38px;
        margin-top: 5px;
        margin-bottom: -6px;
        border-bottom-left-radius: 4px;
        border-bottom-right-radius: 4px;
    }

    .el-select__footer.el-select-dropdown__item.hover {
        background-color: inherit !important;
    }

    .el-select__footer.el-select-dropdown__item:hover, .el-select__footer.el-select-dropdown__item:focus {
        background-color: #3c3f72 !important;
        color: white !important;
        border-top-color: #3c3f72;
    }

    .el-select__footer div span {
        margin-left: 5px;
    }

    .badge-resize {
        float: right;
        margin-top: -32px;
        margin-right: 35px;
        position: relative;
    }
</style>
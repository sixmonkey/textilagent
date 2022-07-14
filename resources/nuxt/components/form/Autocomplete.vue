<template>
  <vee-validation-provider
    v-slot="{ errors }"
    :name="label"
    :rules="rules"
    :vid="name"
    slim
  >
    <v-component
      :is="tag"
      v-model="localValue"
      :error-messages="errors"
      :items="entries"
      :label="label"
      :loading="isLoading"
      :search-input.sync="search"
      autocomplete="off"
      hide-selected
      v-bind="$attrs"
      v-on="$listeners"
    >
      <template #selection="input">
        <autocomplete-row
          :return-object="$attrs['return-object'] !== undefined || $attrs['return-id'] !== undefined"
          :avatars="avatars"
          :input="input"
        />
      </template>
      <template #item="input">
        <autocomplete-row
          :return-object="$attrs['return-object'] !== undefined || $attrs['return-id'] !== undefined"
          :avatars="avatars"
          :input="input"
        />
      </template>
    </v-component>
  </vee-validation-provider>
</template>

<script>
import _ from 'lodash'
import {VAutocomplete, VCombobox} from 'vuetify/lib'
import Input from './Input'
import AutocompleteRow from '@/components/form/Autocomplete/Row'

export default {
  name: 'AutocompleteComponent',
  components: {AutocompleteRow, VCombobox, VAutocomplete},
  extends: Input,
  inheritAttrs: true,
  props: {
    value: {
      type: [Object, String, Number],
      required: false,
      default: () => null,
    },
    addable: {
      type: Boolean,
      default: () => false,
    },
    avatars: {
      type: Boolean,
      default: () => false,
    },
    entity: {
      type: String,
      required: true,
    },
    label: {
      type: String,
      default() {
        return ''
      },
    },
  },
  data: () => ({
    search: '',
    isLoading: false,
    entries: [],
  }),
  computed: {
    tag() {
      return this.addable ? 'v-combobox' : 'v-autocomplete'
    },
    localValue: {
      get() {
        return this.value
      },
      set(localValue) {
        this.$emit('input', localValue)
      },
    },
  },
  watch: {
    search(val) {
      if (this.entries.length > 0) return
      if (this.isLoading) return

      this.isLoading = true

      this.$axios(`/${this.$pluralize(this.entity)}`)
        .then((res) => {
          this.entries = res.data.data.map((item) =>
            this.$attrs['return-object'] !== undefined || this.$attrs['return-id'] !== undefined
              ? _.pick(item, 'id', 'name')
              : item.name
          )
        })
        .catch((err) => {
          this.$toast.error(err)
        })
        .finally(() => (this.isLoading = false))
    },
    value() {
      const val =
        typeof this.value === 'string' &&
        this.$attrs['return-object'] !== undefined
          ? {id: null, name: this.value}
          : this.value

      this.$emit('input', val)
    },
  },
}
</script>

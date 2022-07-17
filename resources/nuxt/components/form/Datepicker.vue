<template>
  <v-menu
    ref="menu"
    v-model="menu"
    :close-on-content-click="false"
    :return-value.sync="localValue"
    transition="scale-transition"
    offset-y
    min-width="auto"
  >
    <template #activator="{ on, attrs }">
      <v-text-field
        v-model="localValue"
        :label="label"
        :prepend-icon="prependIcon"
        readonly
        clearable
        v-bind="attrs"
        v-on="on"
      ></v-text-field>
    </template>
    <v-date-picker
      v-model="localValue"
      no-title
      scrollable
      range
      type="month"
      v-bind="$attrs"
      v-on="$on"
    >
      <v-spacer></v-spacer>
      <v-btn
        text
        color="primary"
        @click="menu = false"
      >
        Cancel
      </v-btn>
      <v-btn
        text
        color="primary"
        @click="$refs.menu.save(localValue)"
      >
        OK
      </v-btn>
    </v-date-picker>
  </v-menu>
</template>

<script>
export default {
  name: "DatepickerComponent",
  props: {
    value: {
      type: Array,
      required: false,
      default: () => null,
    },
    label: {
      type: String,
      required: false,
      default: () => null,
    },
    prependIcon: {
      type: String,
      required: false,
      default: () => null,
    },
  },
  data() {
    return {
      menu: false,
    }
  },
  computed: {
    localValue: {
      get() {
        return this.value
      },
      set(localValue) {
        this.$emit('input', localValue)
      },
    },
  }
}
</script>

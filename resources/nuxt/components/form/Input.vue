<template>
  <vee-validation-provider
    v-slot="{ errors }"
    :name="label"
    :rules="rules"
    :vid="name"
    slim
  >
    <v-text-field
      :error-messages="errors"
      :label="label"
      autocomplete="off"
      v-bind="$attrs"
      :class="{ 'custom-append': appendText }"
      v-on="$listeners"
    >
      <template v-if="appendText" #append>
        {{ appendText }}
      </template>
    </v-text-field>
  </vee-validation-provider>
</template>

<script>
export default {
  name: 'InputComponent',
  inheritAttrs: false,
  props: {
    rules: {
      type: String,
      default() {
        return ''
      },
    },
    name: {
      type: String,
      default() {
        return ''
      },
    },
    label: {
      type: String,
      default() {
        return ''
      },
    },
    appendText: {
      type: String,
      required: false,
      default() {
        return null
      },
    },
  },
}
</script>
<style lang="scss">
@import '~vuetify/src/styles/styles.sass';

input[type='date']::-webkit-inner-spin-button,
input[type='date']::-webkit-calendar-picker-indicator {
  opacity: 0;
}

.v-application .error--text ::-webkit-datetime-edit-month-field,
.v-application .error--text ::-webkit-datetime-edit-day-field,
.v-application .error--text ::-webkit-datetime-edit-year-field {
  color: map-get($red, accent-2) !important;
}

.text-center input {
  text-align: center;
}

.text-right input {
  text-align: right;
}

.v-input--is-readonly .v-input__slot::before,
.v-input--is-readonly .v-input__slot::after {
  border-style: none !important;
}

.custom-append .v-input__append-inner {
  align-self: initial !important;
  margin-top: 0 !important;
}
</style>

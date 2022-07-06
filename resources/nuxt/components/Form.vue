<template>
  <vee-validation-observer ref="observer" v-slot="{ invalid }" tag="div">
    <v-form v-bind="$attrs" v-on="$listeners">
      <slot :invalid="invalid"></slot>
    </v-form>
  </vee-validation-observer>
</template>

<script>
import {ValidationObserver} from 'vee-validate'

export default {
  name: 'FormComponent', components: {
    VeeValidationObserver: ValidationObserver
  },
  inheritAttrs: false,
  inheritListeners: false,
  methods: {
    validate() {
      return this.$refs.observer.validate()
    },
    setErrors(errors) {
      return this.$refs.observer.setErrors(errors)
    },
  },
}
</script>

import Vue from 'vue'
import {
  digits,
  email,
  max,
  min,
  regex,
  required,
  // eslint-disable-next-line camelcase
  min_value,
} from 'vee-validate/dist/rules'
import { extend, ValidationObserver, ValidationProvider } from 'vee-validate'
import _ from 'underscore'

extend('digits', {
  ...digits,
  message: 'Needs to be {length} digits. ({_value_})',
})

extend('required', {
  ...required,
  message: 'Is required',
})

extend('min', {
  ...min,
  message: 'May not be shorter than {length} characters',
})

extend('min_value', {
  // eslint-disable-next-line camelcase
  ...min_value,
  message: 'May not be less than {min}',
})

extend('max', {
  ...max,
  message: 'May not be greater than {length} characters',
})

extend('regex', {
  ...regex,
  message: '{_value_} does not match {regex}',
})

extend('email', {
  ...email,
  message: 'Email must be valid',
})

extend('differentCustomer', {
  params: ['target'],
  validate(value, { target }) {
    if (target === null || value.id === null) return true
    return !_.isMatch(value, target)
  },
  message: "{_field_} and {target} can't be the same",
})

Vue.component('VeeValidationObserver', ValidationObserver)
Vue.component('VeeValidationProvider', ValidationProvider)

<template>
  <v-tooltip v-model="show" :disabled="!tooltip" top>
    <template #activator="{ on, attrs }">
      <v-avatar
        :class="!background && 'border'"
        :color="image || !background ? null : color"
        :size="size"
        dark
        :v-bind="{ ...$attrs, ...attrs }"
        :v-on="{ ...$listeners, ...on }"
        @mouseenter="show = true"
        @mouseleave="show = false"
      >
        <v-img v-if="image" :alt="name" :src="image" />
        <span v-else :class="classes" class="white--text" v-text="initial" />
      </v-avatar>
    </template>
    {{ name }}
  </v-tooltip>
</template>

<script>
export default {
  name: 'UiAvatar',
  props: {
    name: {
      type: String,
      default: null,
    },
    image: {
      type: [String, null],
      default() {
        return null
      },
    },
    background: {
      type: [Boolean, null],
      default() {
        return true
      },
    },
    tooltip: {
      type: [Boolean, null],
      default() {
        return false
      },
    },
    size: {
      type: [Number, String, null],
      default() {
        return 64
      },
    },
  },
  data: () => ({
    show: false,
  }),
  computed: {
    initial() {
      return this.$getInitial(this.name)
    },
    color() {
      return this.$getColor(this.name)
    },
    classes() {
      const classes = []
      if (this.size > 100) classes.push('text-h4')
      if (this.size < 48) classes.push('text-caption')
      return classes
    },
  },
}
</script>
<style lang="scss" scoped>
.v-avatar {
  cursor: default;
}

.border {
  border: 1px solid white;
}
</style>

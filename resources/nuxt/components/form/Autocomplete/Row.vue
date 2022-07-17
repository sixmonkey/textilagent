<template>
  <div class="text-no-wrap text-truncate">
    <app-avatar
      v-if="avatars"
      :name="returnObject ? input.item[itemText ?? 'name'] : input.item"
      :size="20"
      class="mr-2"
    />
    {{ returnObject ? input.item[itemText ?? 'name'] : input.item }}
  </div>
</template>

<script>
export default {
  name: 'AutocompleteRow',
  props: {
    input: {
      required: true,
      type: Object,
    },
    returnObject: {
      required: true,
      type: Boolean,
    },
    itemText: {
      type: String,
      default: () => null,
    },
    avatars: {
      type: Boolean,
      default: () => false,
    },
  },
}
</script>

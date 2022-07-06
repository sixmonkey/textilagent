<template>
  <v-app id="login" class="primary">
    <nuxt />
  </v-app>
</template>

<script>
export default {
  name: 'BlankLayout',
}
</script>

<style scoped lang="scss">
#login {
  height: 50%;
  width: 100%;
  position: absolute;
  top: 0;
  left: 0;
  content: '';
  z-index: 1;
  background: #cdcdcd url('/bar.jpg') no-repeat center;
  background-size: cover;

  &::before {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    content: '';
    background: rgba(0, 0, 0, 0.3);
  }
}
</style>

<template>
  <q-page class="flex flex-center" v-if="auth">
    <img
      alt="Quasar logo"
      src="~assets/quasar-logo-full.svg"
    >
  </q-page>
</template>

<script>
export default {
  name: 'PageIndex',
  mounted () {
    console.log('mounted')
  },
  /* created () {
    if (!this.$q.cookies.has('token')) {
      // redirigir al inicio
      this.$router.replace('/accounts/auth')
    }
  }, */
  computed: {
    auth () {
      return (this.$q.cookies.has('token'))
    }
  }
}
</script>

<template>
  <q-layout view="lHh Lpr lFf" v-if="this.$q.platform.is.android || this.$q.platform.is.ios">
    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
  <Error v-else/>
</template>

<script>
import { QSpinnerBall } from 'quasar'
import Error from 'pages/error/NotSupport.vue'
export default {
  name: 'AccountsLayout',
  components: { Error },
  created () {
    if (this.$q.cookies.has('token')) {
      this.loading()
      const currentPath = this.$route.fullPath
      if (currentPath === '/accounts/logout') {
        this.$axios.get('/security/accounts/logout')
          .then((response) => {
            if (response.data.code === 0 || response.data.code !== 0) {
              this.$router.replace('/')
            }
            // this.$router.push('/loading')
            this.$q.cookies.remove('token')
            this.$q.cookies.remove('session')
          }).catch((e) => {
            this.$q.notify({
              progress: true,
              type: 'negative',
              message: 'intentelo nuevamente.'
            })
          }).then(() => {
            this.$q.loading.hide()
          })
      } else {
        // redirigir al inicio
        return this.$router.replace('/')
      }
    }
  },
  methods: {
    loading () {
      this.$q.loading.show({
        spinner: QSpinnerBall,
        spinnerColor: 'negative'
      })
    }
  }
}
</script>

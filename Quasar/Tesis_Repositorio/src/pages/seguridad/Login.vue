<template>
  <q-page class="window-height window-width row justify-center items-center" style="background: #004484">
    <div class="column items-center">
      <!-- <div class="row">
        <q-img class="absolute-top" src="~assets/icon-login.jpg" style="height: 100px;" width="50%"></q-img>
      </div>-->
      <div class="row">
        <q-img src="~assets/icon-login.jpg" style="height: 100%;" width="100%"></q-img>
        <h5 class="text-h5 text-white q-my-md text-center">{{ appLoginLabel }}</h5>
      </div>
      <div class="row">
        <q-card square bordered class="q-pa-lg shadow-1">
          <q-card-section>
            <!-- <q-form class="q-gutter-md">-->
              <q-input square filled clearable ref="email" v-model="email" type="email" label="Correo" :rules="[val => !!val || '* Campo Requerido', val => val.match(/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/) || 'coloque un correo valido']" @keydown.enter.prevent="login" />
              <q-input square filled clearable ref="password" v-model="password" type="password" label="Contraseña" :rules="[val => !!val || '* Campo Requerido']" @keydown.enter.prevent="login"/>
            <!-- </q-form>-->
          </q-card-section>
          <q-card-actions class="q-px-md">
            <q-btn @click="login" unelevated color="light-blue-10" size="lg" class="full-width" label="Iniciar sesion" icon-right="login" :loading="submitting"/>
          </q-card-actions>
          <q-card-section class="text-center q-pa-none">
            <q-btn unelevated class="text-grey-6" size="sm" label="¿No estas registrado? Crear una cuenta" to="register"/>
          </q-card-section>
        </q-card>
      </div>
    </div>
  </q-page>
</template>

<script>
import { QSpinnerBall } from 'quasar'
export default {
  name: 'Login',
  data () {
    return {
      email: '',
      password: '',
      appLoginLabel: 'Autenticarse',
      submitting: false
    }
  },
  methods: {
    loading () {
      this.$q.loading.show({
        spinner: QSpinnerBall,
        spinnerColor: 'negative'
      })
    },
    login () {
      this.$refs.email.validate()
      this.$refs.password.validate()
      if (this.$refs.email.hasError || this.$refs.password.hasError) return
      this.loading()
      // se inicia el load
      this.submitting = true
      this.$axios.post('/security/accounts/login', this.$data)
        .then((response) => {
          // console.log(response)
          if (response.data.code === 0) {
            // window.location.href = '/'
            this.$q.notify({
              progress: true,
              type: 'positive',
              message: 'Bienvenido.'
            })

            this.$q.cookies.set('token', response.data.data.Token)
            this.$q.cookies.set('session', response.data.data.Informacion)
            this.$axios.defaults.headers.common.Authorization = this.$q.cookies.has('token') ? this.$q.cookies.get('token') : ''

            this.$router.replace('/')
          } else {
            this.$q.notify({
              progress: true,
              type: 'negative',
              message: response.data.message.user
            })
          }
        }).catch((e) => {
          this.$q.notify({
            progress: true,
            type: 'negative',
            message: 'intentelo nuevamente.'
          })
        }).then(() => {
          this.submitting = false
          this.$q.loading.hide()
        })
    }
  }

}
</script>

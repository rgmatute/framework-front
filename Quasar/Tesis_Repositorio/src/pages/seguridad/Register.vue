<template>
  <q-page class="window-height window-width row justify-center items-center" style="background: #004484">
    <div class="column items-center">
      <div class="row">
        <h5 class="text-h5 text-white q-my-md text-center">{{ appRegisterLabel }}</h5>
      </div>
      <div class="row">
        <q-card square bordered class="q-pa-lg shadow-1">
          <q-card-section>
            <q-form class="q-gutter-sm" @keyup.enter="register">
              <q-input square filled clearable ref="firstname"  v-model="firstname" type="text" label="Nombre"  dense :rules="[val => !!val || '* Campo Requerido']"/>
              <q-input square filled clearable ref="lastname"   v-model="lastname" type="email" label="Apellidos" dense :rules="[val => !!val || '* Campo Requerido']"/>
              <q-input square filled clearable ref="email"      v-model="email" type="email" label="Correo" dense  :rules="[ val => val.match(/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/) || 'coloque un correo valido', val => !!val || '* Campo Requerido' ]" />
              <q-input square filled clearable ref="phone"      v-model="phone" type="text" label="Telefono" dense    :rules="[ val => val.length <= 10 || 'Utilice un máximo de 10 dígitos', val => val.match(/^[0-9]*$/i) || 'Solo se acepta numeros.']" />
              <q-input square filled clearable ref="password"   v-model="password" type="password" label="Contraseña" dense :rules="[val => !!val || '* Campo Requerido']"/>
              <q-input square filled clearable ref="repeatPassword" v-model="repeatPassword" type="password" label="Repetir Contraseña" dense :rules="[val => !!val || '* Campo Requerido']"/>
            </q-form>
          </q-card-section>
          <q-card-actions class="q-px-md">
            <q-btn @click="register" unelevated color="light-blue-10" size="lg" class="full-width" label="Continuar" icon-right="arrow_right_alt" :loading="submitting"/>
          </q-card-actions>
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
      firstname: '',
      lastname: '',
      phone: '',
      email: '',
      password: '',
      repeatPassword: '',
      appRegisterLabel: 'Registro Nuevo',
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
    register () {
      this.$refs.firstname.validate()
      this.$refs.lastname.validate()
      this.$refs.email.validate()
      this.$refs.phone.validate()
      this.$refs.password.validate()
      this.$refs.repeatPassword.validate()

      // if (this.$refs.firstname.hasError || this.$refs.lastname.hasError || this.$refs.lastnemailame.hasError || this.$refs.phone.hasError || this.$refs.password.hasError || this.$refs.password.repeatPassword) return
      if (this.isFormValid) return

      if (this.isPasswordEquals) {
        // se inicia el load
        this.submitting = true
        this.loading()
        this.$axios.post('/security/accounts/register', this.$data)
          .then((response) => {
            if (response.code === 0 && !response.error) {
              // window.location.href = '/'
              this.$q.notify({
                progress: true,
                type: 'positive',
                message: response.message
              })
              return this.$router.replace('/')
            } else {
              this.$q.notify({
                progress: true,
                type: 'negative',
                message: response.message.user
              })
            }
          }).catch((e) => {
            this.$q.notify({
              progress: true,
              type: 'negative',
              message: 'Ups! Servicio no Disponible.'
            })
          }).then(() => {
            this.submitting = false
            this.$q.loading.hide()
          })
      } else {
        this.submitting = false
        this.$q.notify({
          progress: true,
          type: 'negative',
          message: 'Ups! contraseñas deben coincidir.'
        })
        this.password = ''
        this.repeatPassword = ''
      }
    }
  },
  computed: {
    isPasswordEquals () {
      return (this.password === this.repeatPassword)
    },
    isFormValid () {
      return (this.$refs.firstname.hasError || this.$refs.lastname.hasError || this.$refs.email.hasError || this.$refs.phone.hasError || this.$refs.password.hasError || this.$refs.repeatPassword.hasError)
    }
  }
}
</script>

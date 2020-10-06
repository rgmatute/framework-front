<template>
    <q-dialog v-model="prompt" persistent>
      <q-card style="min-width: 350px">
        <q-card-section>
          <div class="text-h6">{{ title }} parametro</div>
        </q-card-section>

        <q-card-section class="q-pt-none">
          <q-input dense v-model="codigo" autofocus @keyup.enter="prompt = false" placeholder="Codigo"/>
          <q-input dense v-model="valor" autofocus @keyup.enter="prompt = false" placeholder="Valor"/>
        </q-card-section>

        <q-card-actions align="right" class="text-primary">
          <q-btn flat label="Cancelar" v-close-popup />
          <q-btn flat :label="title" @click="guardar()"/>
        </q-card-actions>
      </q-card>
    </q-dialog>
</template>

<script>
import { QSpinnerBall } from 'quasar'
export default {
  props: {
    value: {
      type: Boolean,
      default: false
    },
    title: {
      type: String,
      default: ''
    },
    parametro: {
      type: [Object, Array],
      default: null
    }
  },
  data () {
    return {
      prompt: this.value,
      id: '',
      codigo: '',
      valor: ''
    }
  },
  methods: {
    loading () {
      this.$q.loading.show({
        spinner: QSpinnerBall,
        spinnerColor: 'negative'
      })
    },
    guardar () {
      this.loading()

      this.$axios.post('/parametros', this.$data).then((response) => {
        if (response.data.code === 0) {
          this.$emit('refresh', true)
          this.prompt = false
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
  },
  watch: {
    prompt (show) {
      this.$emit('input', show)
    },
    value (show) {
      this.prompt = show
    },
    parametro (parameter) {
      this.id = parameter.id
      this.codigo = parameter.codigo
      this.valor = parameter.valor
    }
  }
}
</script>

<style>

</style>

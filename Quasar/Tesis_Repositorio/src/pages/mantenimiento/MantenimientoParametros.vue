<template>
  <q-page class="" v-if="$q.cookies.get('token')">

      <q-separator />

      <q-table
      title="Parametros"
      :data="data"
      :columns="columns"
      :filter="filter"
      :no-data-label="message"
      no-results-label="El filtro no reveló ningún resultado"
      row-key="name"
    >

    <template v-slot:top>
        <q-btn color="primary" :disable="loading" round icon="add" @click="addNew()"/>
        <q-space />
        <q-input borderless dense debounce="300" v-model="filter" placeholder="Buscar...">
          <q-icon slot="append" name="search" />
        </q-input>
    </template>

    <template v-slot:no-data="{ icon, message, filter }">
        <div class="full-width row flex-center text-accent q-gutter-sm">
          <q-icon size="2em" name="sentiment_dissatisfied" />
          <span>
            {{ message }}
          </span>
          <q-icon size="2em" :name="filter ? 'filter_b_and_w' : icon" />
        </div>
    </template>

    <template v-slot:body-cell-ud="props">
        <q-td :props="props">
          <div>
            <q-btn dense color="primary" round icon="edit" class="q-ml-md" @click="edit(props.row.id)"></q-btn>
            <q-btn dense color="red" round icon="delete" class="q-ml-md" @click="remove(props.row.id)"></q-btn>
          </div>
        </q-td>
      </template>

      </q-table>
      <Parametro v-model="modal" :title="titleModal" :parametro="parametro" @refresh="refresh"/>
  </q-page>
</template>

<script>
import { /* QSpinnerFacebook, */ QSpinnerBall/*, QSpinnerGrid */ } from 'quasar'
import Parametro from 'pages/mantenimiento/parametros/CreaEdita.vue'

export default {
  components: { Parametro },
  data () {
    return {
      filter: '',
      loading: false,
      titleModal: '',
      modal: false,
      message: 'No encontré nada para ti',
      columns: [
        { name: 'Id', align: 'center', label: 'Id', field: 'id', sortable: true },
        { name: 'Codigo', label: 'Codigo', field: 'codigo', sortable: true, align: 'center' },
        { name: 'Valor', label: 'Valor', field: 'valor', align: 'center' },
        { name: 'Creado', label: 'Creado', field: 'created_at', align: 'center' },
        { name: 'ud', label: '', align: 'center' }
      ],
      data: [],
      parametro: {}
    }
  },
  created () {
    this.message = 'Cargando...'
    this.getAll()
    this.$on('refresh', (v) => {
      console.log(v)
      this.getAll()
    })
  },
  methods: {
    _loading () {
      this.$q.loading.show({
        spinner: QSpinnerBall,
        spinnerColor: 'negative'
      })
    },
    getAll () {
      this._loading()
      this.$axios.get('/parametros')
        .then((response) => {
          // console.log(response)
          if (response.data.code === 0) {
            this.data = response.data.data
            this.message = 'No encontré nada para ti'
          } else {
            this.$q.notify({
              progress: true,
              type: 'negative',
              message: response.data.message.user
            })
          }
        }).catch((e) => {
          console.log(e)
          this.$q.notify({
            progress: true,
            type: 'negative',
            message: 'intentelo nuevamente.'
          })
        }).then(() => { this.$q.loading.hide() })
    },
    remove (id) {
      this._loading()
      this.$axios.get(`/parametros/${id}/delete`)
        .then((response) => {
          if (response.data.code === 0) {
            this.getAll()
          } else {
            this.$q.notify({
              progress: true,
              type: 'negative',
              message: response.data.message.user
            })
          }
        }).catch((e) => {
          console.log(e)
          this.$q.notify({
            progress: true,
            type: 'negative',
            message: 'intentelo nuevamente.'
          })
          this.$q.loading.hide()
        })
    },
    getById (id) {

    },
    edit (id) {
      this._loading()
      this.$axios.get('/parametros/' + id)
        .then((response) => {
          // console.log(response)
          if (response.data.code === 0) {
            if (response.data.data.length === 1) {
              this.parametro = response.data.data[0]
              this.titleModal = 'Actualizar'
              this.modal = true
            }
          } else {
            this.$q.notify({
              progress: true,
              type: 'negative',
              message: response.data.message.user
            })
          }
        }).catch((e) => {
          console.log(e)
          this.$q.notify({
            progress: true,
            type: 'negative',
            message: 'intentelo nuevamente.'
          })
        }).then(() => { this.$q.loading.hide() })
    },
    addNew () {
      this.parametro = {}
      this.titleModal = 'Registrar'
      this.modal = true
    },
    refresh () {
      this.getAll()
    }
  }
}
</script>

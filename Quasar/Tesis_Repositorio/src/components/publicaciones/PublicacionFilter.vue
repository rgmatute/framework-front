<template>

    <q-dialog v-model="bar">
      <q-card style="width: 700px; max-width: 80vw;">
        <q-card-section>
          <div class="text-h6">Filtros de publicaciones</div>
        </q-card-section>

        <q-card-section class="q-pt-none">
            <div class="q-pa-md" style="max-width: 100%">
                <div class="q-gutter-md text-center">
                    <q-select square outlined filled clearable v-model="select.clasificacion.model"  :options="select.clasificacion.options" label="Clasificacion de DIscapacidad" />

                    <q-select square outlined filled clearable v-model="select.discapacidad.model"  :options="select.discapacidad.options" label="Discapacidad" />

                    <q-select square outlined filled clearable v-model="select.tipoPublicacion.model"  :options="select.tipoPublicacion.options" label="Tipo de Publicacion" />

                    <q-separator />

                    <q-btn
                        @click="FiltrarEvent"
                        color="negative"
                        label="Filtrar busqueda" icon="filter_alt"/>

                </div>
            </div>
        </q-card-section>

      </q-card>
    </q-dialog>
</template>

<script>
export default {
  props: {
    value: {
      type: Boolean,
      default: false
    }
  },
  data () {
    return {
      bar: this.value,
      select: {
        clasificacion: {
          model: null,
          options: ['Sensocrial', 'Discapacidad fisica', 'Discapacidad Intelectual', 'Discapacidad psicosocial']
        },
        discapacidad: {
          model: null,
          options: ['Auditiva 30%', 'intelectual basica']
        },
        tipoPublicacion: {
          model: null,
          options: ['Archivos', 'Imagens', 'Videos', 'Textos', 'Enlaces']
        }
      }
    }
  },
  methods: {
    FiltrarEvent () {
      this.$emit('filter', { clasificacionSeleccionada: this.select.clasificacion.model, discapacidadSeleccionada: this.select.discapacidad.model, tipoPublicacionSeleccionada: this.select.tipoPublicacion.model })
      this.bar = false
      this.select.clasificacion.model = null
      this.select.discapacidad.model = null
      this.select.tipoPublicacion.model = null
    }
  },
  watch: {
    bar (val) {
      this.$emit('input', val)
      this.tipoPublicacion = ''
    },
    value (val) {
      this.bar = val
    }
  }
}
</script>

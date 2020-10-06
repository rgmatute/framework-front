<template>

    <q-dialog v-model="bar">
      <q-card style="width: 700px; max-width: 80vw;">
        <q-card-section>
          <div class="text-h6">Publicar nuevo contenido</div>
        </q-card-section>

        <q-card-section class="q-pt-none">
            <div class="q-pa-md" style="max-width: 100%">
                <div class="q-gutter-md text-center">

                  <q-btn color="indigo" no-caps @click="seleccionarTipoSelect(1)">
                    Asociadas<br>a una discapacidad
                  </q-btn>
                  <q-btn color="indigo" no-caps  @click="seleccionarTipoSelect(2)">
                    No asociadas<br>a una discapacidad
                  </q-btn>
                    <q-select v-if="banderaTipo === 1" square outlined filled clearable v-model="tipoDiscapacidadSeleccionado"  :options="select.asociadasAdiscapacidad.tipo.options" label="Tipo Discapacidad" />
                    <q-select v-if="banderaTipo === 1" square outlined filled clearable v-model="clasificacionDiscapacidadSeleccionada"  :options="select.asociadasAdiscapacidad.clasificacion.options" label="Clasificacion de Discapacidad" />
                    <q-select v-if="banderaTipo === 1" square outlined filled clearable v-model="discapacidadSeleccionada"  :options="select.asociadasAdiscapacidad.discapacidad.options" label="Discapacidad" />

                    <q-select v-if="banderaTipo === 2" square outlined filled clearable v-model="tipoDiscapacidadSeleccionado"  :options="select.noAsociadasAdiscapacidad.tipo.options" label="Tipo Discapacidad" />
                    <q-select v-if="banderaTipo === 2" square outlined filled clearable v-model="clasificacionDiscapacidadSeleccionada"  :options="select.noAsociadasAdiscapacidad.clasificacion.options" label="Clasificacion de Discapacidad" />
                    <q-select v-if="banderaTipo === 2" square outlined filled clearable v-model="discapacidadSeleccionada"  :options="select.noAsociadasAdiscapacidad.discapacidad.options" label="Discapacidad" />

                    <q-input v-model="descripccion" filled type="textarea" autogrow label="Descripcion"></q-input>

                    <q-separator />

                    <q-icon @click="tipoPublicacionEvent('image')" name="add_photo_alternate" color="grey-8"  style="font-size: 3.1em;" />
                    <q-icon @click="tipoPublicacionEvent('video')" name="video_library" color="grey-8"  style="font-size: 3.1em;" />
                    <q-icon @click="tipoPublicacionEvent('file')" name="file_copy" color="grey-8"  style="font-size: 3.1em;" />
                    <!-- <q-icon name="attachment" color="grey-8"  style="font-size: 3.1em;" /> -->
                    <q-icon @click="tipoPublicacionEvent('link')" name="link" color="grey-8"  style="font-size: 3.1em;" />
                    <q-icon @click="tipoPublicacionEvent('message')" name="textsms" color="grey-8"  style="font-size: 3.1em;" />

                    <q-separator v-if="tipoPublicacion === 'message' || tipoPublicacion === 'link' || tipoPublicacion === 'image' || tipoPublicacion === 'video' || tipoPublicacion === 'file'" />

                    <q-input v-if="tipoPublicacion === 'message' || tipoPublicacion === 'link'" v-model="valorPublicacion" filled type="textarea" autogrow :label="tipoPublicacion === 'message'?'Mensaje': 'Link'"></q-input>

                    <q-uploader
                      v-if="tipoPublicacion === 'image' || tipoPublicacion === 'video' || tipoPublicacion === 'file'"
                      :accept="fileAccept"
                      class="full-width"
                      url="http://localhost:4444/upload"
                      style="max-width: 100%"
                      :label="tipoPublicacion"
                    />

                </div>
            </div>
        </q-card-section>

        <q-card-actions align="right" class="bg-white text-teal">
          <q-btn flat label="Publicar" />
          <q-btn flat label="Cerrar" v-close-popup />
        </q-card-actions>
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
        asociadasAdiscapacidad: {
          tipo: {
            options: ['sensorial', 'Discapacidad fisica', 'Discapacidad Intelectual', 'Discapacidad psicosocial']
          },
          clasificacion: {
            options: ['discapacidad visual', 'discapacidad auditiva', 'discapacidad fisica', 'discapacidad intelectual', 'discapacidad psicosocial', 'trastoros generalizados del desarrollo']
          },
          discapacidad: {
            options: ['ceguera total', 'baja vision']
          }
        },
        noAsociadasAdiscapacidad: {
          tipo: {
            options: ['transtorno de aprendizaje ', 'dotacion intelectual', 'trastornos del comportamiento', 'otros contexto diversos que dificulten el procesos de enseñanza-aprendizaje']
          },
          clasificacion: {
            options: ['lectura-escrita', 'matematica']
          },
          discapacidad: {
            options: ['dislexia', 'Distografia', 'Digrafia']
          }
        }
      },
      tipoDiscapacidadSeleccionado: null,
      clasificacionDiscapacidadSeleccionada: null,
      discapacidadSeleccionada: null,
      descripccion: '',
      tipoPublicacion: '',
      valorPublicacion: '',
      fileAccept: '.jpg, image/*',
      banderaTipo: 0
    }
  },
  created () {
    this.$emit('process', { error: false, userId: null })
  },
  methods: {
    tipoPublicacionEvent (tipo) {
      this.tipoPublicacion = tipo
      if (tipo === 'video') {
        this.fileAccept = 'video/mp4,video/x-m4v,video/*'
      } else if (tipo === 'image') {
        this.fileAccept = '.jpg, image/*'
      } else if (tipo === 'file') {
        this.fileAccept = 'application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, application/pd'
      }
    },
    seleccionarTipoSelect (tipo) {
      this.banderaTipo = tipo
      this.tipoDiscapacidadSeleccionado = null
      this.clasificacionDiscapacidadSeleccionada = null
      this.discapacidadSeleccionada = null
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

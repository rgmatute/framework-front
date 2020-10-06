<template>
<q-page class="flex flex-center" v-if="$q.cookies.get('token')">

    <Publicacion :publicaciones="publicaciones" />
    <PublicacionInsert @process="publicacionInsertProcess" v-model="insertModal"/>

    <PublicacionFilter @filter="filterProcess" v-model="filterModal"/>

    <q-item-label v-if="publicaciones.length == 0" header class="text-grey-8 text-center">
        No hay publicaciones para mostrar en este momento.
    </q-item-label>
    <q-page-sticky position="bottom-right" :offset="[18, 18]">
            <q-fab
              icon="add"
              direction="up"
              color="negative"
            >
              <q-fab-action @click="publicacionFilterEventClick" color="negative" icon="filter_alt" />
              <q-fab-action @click="publicacionInsertEventClick" color="negative" icon="add_circle_outline" />
            </q-fab>
          </q-page-sticky>
</q-page>
</template>

<script>
import Publicacion from 'components/Publicacion.vue'
import PublicacionInsert from 'components/publicaciones/PublicacionInsert.vue'
import PublicacionFilter from 'components/publicaciones/PublicacionFilter.vue'
import json from '../../assets/publicaciones.json'
export default {
  components: { Publicacion, PublicacionInsert, PublicacionFilter },
  data () {
    return {
      publicaciones: json,
      insertModal: false,
      filterModal: false
    }
  },
  methods: {
    publicacionInsertProcess (logProcess) {
      console.log(logProcess)
      // this.$emit('edit', latlng, this.popup);
    },
    publicacionInsertEventClick () {
      this.insertModal = true
    },
    publicacionFilterEventClick () {
      this.filterModal = true
    },
    filterProcess (filter) {
      console.log(filter)
    }
  }
}
</script>

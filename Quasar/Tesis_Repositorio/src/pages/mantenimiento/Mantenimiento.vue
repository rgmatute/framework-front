<template>
<q-page class="flex flex-center" v-if="$q.cookies.get('token')">
<div class="row">
    <q-card class="my-card" @click="mantenimientoUsuario()">
      <q-card-section class="bg-primary text-white">
        <div class="text-h6" icon="user">Mantenimiento de Usuarios</div>
      </q-card-section>
    </q-card>
    <q-card class="my-card" clickable>
      <q-card-section class="bg-primary text-white">
        <div class="text-h6">Mantenimiento de Filtros</div>
      </q-card-section>
    </q-card>
    <q-separator />
    <q-card class="my-card">
      <q-card-section class="bg-primary text-white"  @click="$router.replace('/mantenimiento-filtros')">
        <div class="text-h6">Mantenimiento de Parametros</div>
      </q-card-section>
    </q-card>
</div>

</q-page>
</template>

<script>

import json from '../../assets/publicaciones.json'
export default {
  // components: { Publicacion, PublicacionInsert, PublicacionFilter },
  data () {
    return {
      publicaciones: json,
      insertModal: false,
      filterModal: false
    }
  },
  methods: {
    mantenimientoUsuario () {
      alert('ok')
    }
  }
}
</script>

<style lang="sass" scoped>
    .my-card
        width: 100%
        max-width: auto
        border-top: 10px #ffff solid
        border-radius: 0%
</style>

<template>

    <div class="row">
      <q-card class="my-card" v-for="(item, index) in items" :key="index">
        <q-item>
            <q-item-section avatar>
                <q-avatar>
                <img :src="item.autor.avatar">
                </q-avatar>
            </q-item-section>

            <q-item-section>
                <q-item-label>{{ item.userName }}</q-item-label>
                <q-item-label caption>{{ item.description }}</q-item-label>
            </q-item-section>
        </q-item>

        <img v-if="item.type === 'image'" :src="item.value" />

        <q-item-label v-if="item.type === 'message'" header class="text-grey-8 text-center">
            {{ item.value }}
        </q-item-label>

        <q-item-label v-if="item.type === 'url'" header class="text-grey-8" target="_blank">
            <a :href="item.value" target="_blank">{{ item.value }}</a>
        </q-item-label>

        <q-video v-if="item.type === 'video'" :src="item.value" style="height: 200px;" />

            <q-pdfviewer v-if="item.type === 'pdf'"
                :src="item.value"
                type="html5"
                v-model="visible"
            />

        <q-separator />

        <q-card-actions align="center">
            <q-btn class="glossy" icon="visibility" rounded color="negative" label="Ver Detalle" />
        </q-card-actions>
    </q-card>

    <q-item-label v-if="items.length == 0" header class="text-grey-8 text-center">
        No hay publicaciones para mostrar en este momento.
    </q-item-label>
    </div>

</template>

<script>
export default {
  props: {
    publicaciones: {
      type: Array,
      Default: []
    }
  },
  data () {
    return {
      visible: true,
      items: this.publicaciones
    }
  },
  watch: {
    publicaciones (value) {
      this.items = value
    }
  }

}
</script>

<style lang="sass" scoped>
    .my-card
        width: 100%
        max-width: auto
        border-top: 7px #DADDE1 solid
        border-radius: 0%
    .q-page
        background-color: #DADDE1

</style>

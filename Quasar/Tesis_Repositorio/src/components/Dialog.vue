<template>
<div>
     <q-dialog
      v-model="isOpen"
    >
      <q-card style="width: 300px">
        <q-card-section>
          <div class="text-h6">{{title}}</div>
        </q-card-section>
        <q-card-section class="q-pt-none">
          {{message}}
        </q-card-section>
        <q-card-actions align="right" class="bg-white text-teal">
          <q-btn flat label="OK" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
</div>
</template>

<script>
export default {
  name: 'Dialog',
  props: { open: { type: Boolean, default: false }, title: { type: String, default: '' }, message: { type: String, default: '' } },
  data () {
    return {
      isOpen: this.open
    }
  },
  watch: {
    open (value) {
      this.isOpen = value
    }
  }

}
</script>

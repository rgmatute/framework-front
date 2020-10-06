<template>
<q-page class="flex column">
    <div class="q-pa-md column col justify-end">
        <q-chat-message v-for="(message, index) in messages"
        :key="index"
        :name="message.from"
        :text="message.text"
        :sent="message.from === 'me' ? true:false"
      />
    </div>
    <q-footer elevated>
        <q-toolbar>
          <q-form
            @submit="sendMessagee"
            class="full-width">
            <q-input
                v-model="newMessage"
                bg-color="white"
                class="full-width"
                rounded
                outlined
                label="Mensaje"
                dense>
                <template v-slot:after>
                    <q-btn
                        round
                        dense
                        flat
                        @click="sendMessagee"
                        type="submit"
                        color="white"
                        icon="send"/>
                </template>
            </q-input>
          </q-form>
        </q-toolbar>
      </q-footer>
    </q-page>
</template>

<script>
export default {
  data () {
    return {
      newMessage: '',
      messages: [
        {
          text: ['Hola'],
          from: 'me'
        },
        {
          text: ['Hola que tal'],
          from: 'angel'
        }
      ]
    }
  },
  methods: {
    sendMessagee () {
      this.messages.push({
        text: [this.newMessage],
        from: 'me'
      })
      this.newMessage = ''
    }
  }
}
</script>

<style>

</style>

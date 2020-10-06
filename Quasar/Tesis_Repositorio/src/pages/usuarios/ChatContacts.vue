<template>
  <q-page class="flex">
      <q-list
      class="full-width"
      separator
      bordered
      >
      <q-item v-for="user in users" :key="user.id" class="q-my-sm" clickable v-ripple :to="'/chat/'+user.id">
        <q-item-section avatar>
          <q-avatar color="primary" text-color="white">
            {{ user.name.charAt(0) }}
          </q-avatar>
        </q-item-section>

        <q-item-section>
          <q-item-label>{{ user.name }}</q-item-label>
        </q-item-section>

        <q-item-section side>
            <q-icon name="stop_circle" :color="user.online ? 'positive' : 'grey-5' " style="font-size: 1.1em;" />
        </q-item-section>
      </q-item>
    </q-list>
  </q-page>

</template>

<script>
import contacts from '../../assets/contactos.json'

export default {
  data () {
    return {
      users: contacts
    }
  }
}
</script>

<style>

</style>

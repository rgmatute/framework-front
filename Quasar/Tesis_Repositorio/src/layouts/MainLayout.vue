<template>
  <q-layout view="lHh Lpr lFf" v-if="this.$q.platform.is.android || this.$q.platform.is.ios">
    <q-header elevated v-if="auth">
      <q-toolbar>
        <q-btn
          v-if="!$route.fullPath.includes('/chat')"
          flat
          dense
          round
          icon="menu"
          aria-label="Menu"
          @click="leftDrawerOpen = !leftDrawerOpen"
        />

      <q-btn
        v-if="$route.fullPath.includes('/chat')"
        v-go-back.single
        icon="arrow_back"
        flat
        dense>
        </q-btn>

      <q-toolbar-title v-if="title.titleValue === 'Chat'" :class="title.titleClass">
        {{title.titleValue}}
      </q-toolbar-title>

      <q-toolbar-title v-else :class="title.titleClass">
        <q-badge color="white" text-color="primary" :label="title.titleValue" />
      </q-toolbar-title>

      <q-btn dense round flat icon="notifications">
        <q-badge color="red" floating transparent>0</q-badge>
      </q-btn>

        <!-- <div>Quasar v{{ $q.version }}</div> -->
      </q-toolbar>
    </q-header>

    <q-drawer
      v-if="auth"
      v-model="leftDrawerOpen"
      show-if-above
      content-class="bg-grey-1"
    >
    <q-scroll-area style="height: calc(100% - 150px); margin-top: 150px; border-right: 1px solid #ddd">
      <q-list>
        <q-item-label
          header
          class="text-grey-8"
        >
          Menu Principal
        </q-item-label>
        <Menu
          v-for="link in essentialLinks"
          :key="link.title"
          v-bind="link"
        />
      </q-list>
      </q-scroll-area>

      <q-img class="absolute-top" src="~assets/icon-profile.jpg" style="height: 150px">
          <div class="absolute-bottom bg-transparent">
            <!-- <q-avatar size="56px" class="q-mb-sm">
              <img src="https://cdn.quasar.dev/img/boy-avatar.png">
            </q-avatar> -->
            <div class="text-weight-bold text-black">{{ userInfo.firstname + ' ' +userInfo.lastname }}</div>
            <div class="text-black">{{ userInfo.email }}</div>
          </div>
        </q-img>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
  <Error v-else/>
</template>

<script>
import Menu from 'components/Menu.vue'
import Error from 'pages/error/NotSupport.vue'
import menu from '../assets/menu.json'
import menuAdministrador from '../assets/menuAdministrador.json'

export default {
  name: 'MainLayout',
  components: { Menu, Error },
  data () {
    return {
      leftDrawerOpen: false,
      essentialLinks: menu,
      claseDeTitulo: '',
      titleValue: {
        text: '',
        class: 'text-right'
      }
    }
  },
  created () {
    if (!this.$q.cookies.has('token')) {
      // redirigir al inicio
      this.$router.push('/accounts/auth')
    } else {
      if (this.$q.cookies.has('session')) {
        const user = this.$q.cookies.get('session')
        if (user.role_name === 'ADMINISTRADOR') {
          this.essentialLinks = menuAdministrador
        } else {
          this.essentialLinks = menu
        }
      }
    }
  },
  computed: {
    auth () {
      return (this.$q.cookies.has('token'))
    },
    title () {
      const currentPath = this.$route.fullPath
      const path = this.$route.params
      // console.log(path)
      // const titleValue = ''
      if (currentPath === '/chat/' + path.id) {
        return { titleValue: 'Chat', titleClass: 'absolute-center' }
      } else {
        const role = (this.$q.cookies.get('session').role_name) ? this.$q.cookies.get('session').role_name : ''
        return { titleValue: role, titleClass: 'text-right' }
      }
    },
    userInfo () {
      return (this.$q.cookies.has('session')) ? this.$q.cookies.get('session') : { email: '', firstname: '', lastname: '' }
    }
  }
}
</script>

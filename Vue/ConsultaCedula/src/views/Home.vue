<template>
  <div class="home">
    <img alt="Vue logo" src="../assets/logo.png">
    <router-link to="/person" class="a-person">
        <HelloWorld msg="StartRest"/>
    </router-link>
  </div>
</template>

<script>
// @ is an alias to /src
import HelloWorld from '@/components/HelloWorld.vue'

export default {
    name: 'Home',
    components: {
        HelloWorld
    }
}
</script>

<style scoped>
    a.a-person {
        color: #42b983;
        text-decoration: none !important;
    }

    a.a-person:hover{
        color: #35495E;
    }
</style>

<template>
  <div>
      <input type="text" v-model="user">
      <h1>User: {{ user }}</h1>
  </div>
</template>

<script>
export default {
    data(){
        return {
            user: 'rgmatute'
        }
    }
}
</script>

<style>

</style>
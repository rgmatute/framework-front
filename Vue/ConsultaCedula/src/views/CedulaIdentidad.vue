<template>
    <div class="header">
        <div class="bd-content pt-5" role="main">
            <div class="form-group">
                <input type="text" v-model="identificacion" v-on:keyup.enter="onEnter" placeholder="Cedula" class="form-control"/>
            </div>
        </div>
        <CedulaIdentidad :cedula="identificacion" :search="isEnter" @search="onPerson"/>
    </div>
</template>

<script>
    import CedulaIdentidad from '@/components/CedulaIdentidad.vue'
    export default {
        data(){
            return {
                cedulaFinal:'',
                identificacion:'',
                isEnter:false
            }
        },
        components:{
            CedulaIdentidad
        },
        methods:{
            onEnter(){
                this.isEnter = true;
            },
            onPerson(person,isEnter){
                this.isEnter = isEnter;
                this.identificacion = '';
            }
        }
    }
</script>

<style>

</style>
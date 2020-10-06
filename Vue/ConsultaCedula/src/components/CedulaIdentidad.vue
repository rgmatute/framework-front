<template>
    <div>
        <!-- <pre v-if="person.Identificacion !== undefined">{{ person }}</pre> -->
        <h2 v-if="message !== false" v-html="message"></h2>
        <!--<img alt="img cedula" width="100%" src="../assets/bg_cedula.png" />-->
        <Card :person="person"/>
    </div>
</template>

<script>
    import Card from '@/components/Card.vue'
    //import validaCedula from '@/assets/js/algoritmo-cedula-ec'
    export default {
        props:{
            cedula: {
                type: String,
                default: ''
            },
            search: {
                type: Boolean,
                default: false
            },
        },
        components:{
            Card
        },
        data(){
            return {
                apiUri: "https://micro-server.azurewebsites.net/api/v1/person/searchByIdentification/",
                person: [],
                message:false
            }
        },
        methods: {
            consumeService(){
                this.$http.get(this.apiUri + this.cedula).then((response) => {
                    if(response.data.code === 0){
                        this.person = response.data.data;
                    }
                }).catch((error) => {
                    console.log(error);
                }).then(()=>{
                    this.$emit('search',this.person,false);
                    this.message = false;
                });
            }
        },
        computed:{
            validaCedula(){
                return this.cedula.length === 10 /*&& validaCedula.isCedulaEcuador(this.cedula)*/;
            }
        },
        watch:{
            search: {
                handler: function (isEnter) {
                    this.person = [];
                    this.message = "Cargando...";
                    if(isEnter && this.validaCedula){
                        console.log('aaaaaaaaaaaaaaaa');
                        this.consumeService();
                    }else if(this.person.Identificacion === undefined && isEnter){
                        console.log('bbbbbbbbbbbbbbbbb');
                        this.message = '<div class="alert alert-danger" role="alert">Cedula invalida! </div>';
                        this.$emit('search',this.person,false);
                    }else{
                        console.log('cccccccccccccccccccccccc');
                        this.$emit('search',this.person,false);
                    }
                },
                deep: true,
            }
        }
    }
</script>

<style>

</style>
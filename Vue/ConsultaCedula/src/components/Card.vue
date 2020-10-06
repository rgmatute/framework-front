<template>
    <div>
        <div class="card"  v-if="person.Identificacion !== undefined">
            <img v-if="person.Sexo !== undefined && person.Sexo === 'MASCULINO'" class="card-img-top" src="../assets/avatar/man.png" alt="Card image cap" />
            <img v-if="person.Sexo !== undefined && person.Sexo !== 'MASCULINO'" class="card-img-top" src="../assets/avatar/woman.png" alt="Card image cap" />
            <div class="card-body">
                <h5 v-if="person.Identificacion !== undefined" class="card-title">{{ person.Identificacion }}</h5>
                <div class="alert alert-success" role="alert">
                    <p v-if="person.NombreCompletos !== undefined" class="card-text">{{person.NombreCompletos}}</p>
                </div>
            </div>
            <ul class="list-group list-group-flush">
                <li v-if="person.ProfesionOcupacion !== undefined" class="list-group-item">ProfesionOcupacion: {{person.ProfesionOcupacion}}</li>
                <li v-if="person.Nacionalidad !== undefined" class="list-group-item">Nacionalidad: {{person.Nacionalidad}}</li>
                <li v-if="person.EstadoCivil !== undefined" class="list-group-item">EstadoCivil: {{person.EstadoCivil}}</li>
                <li v-if="person.NombreConyuge !== undefined && person.NombreConyuge !== ' '" class="list-group-item">NombreConyuge: {{person.NombreConyuge}}</li>
                <li v-if="person.LugarDomicilio !== undefined" class="list-group-item">LugarDomicilio: {{person.LugarDomicilio}}</li>
                <li v-if="person.FechaNacimiento !== undefined" class="list-group-item">FechaNacimiento: {{person.FechaNacimiento}}</li>
                <li v-if="person.LugarNacimiento !== undefined" class="list-group-item">LugarNacimiento: {{person.LugarNacimiento}}</li>
            </ul>
            <div v-if="person.TipoCedula !== undefined && person.TipoCedula !== 'CIUDADANO'" class="card-body">
                <div class="alert alert-danger" role="alert">
                    Fallecido
                </div>
            </div>
        </div>
  </div>
</template>

<script>
    export default {
        props:{
            person:{
                type: [Object, Array],
                default(){
                    return {}
                }
            }
        },
        computed:{
            getImage(){
                return this.person.Sexo !== undefined && this.person.Sexo === 'MASCULINO' ? '../assets/avatar/man.png': '../assets/avatar/woman.png';
            }
        }
    }
</script>

<style>

</style>
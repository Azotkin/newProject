<script>
import axios from "axios";

export default {
    data: function(){
        return{
            firstname:this.userApiData.firstname,
            surname: this.userApiData.surname,
            lastname: this.userApiData.lastname,
            responseFromServer:'',
            error:''
        }
    },

    props:{
        userApiData: Object
    },
    methods:{
        changeUserData() {
            axios({
                method: 'post',
                url: 'api/change/user/data',
                data:{
                    id: this.userApiData.id,
                    firstname: this.firstname,
                    surname: this.surname,
                    lastname: this.lastname
                }
            })
                .then(response => (this.responseFromServer = response.data)).catch(function (error) {
                this.error.push(error)
            })

        },
    }
}
</script>

<template>
    <div class="col-4">
        <input class="form-control" :value="firstname">
        <input class="form-control" :value="surname">
        <input class="form-control" :value="lastname">
        <button class="btn btn-success" @click="changeUserData">Изменить</button>
        <hr>
    </div>
</template>

<style>

</style>
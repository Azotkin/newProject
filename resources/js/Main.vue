<script>
import axios from "axios";
import userCart from "./components/userCart.vue";

export default {
    data: function () {
        return {
            rangeAgeFrom: 0,
            rangeAgeTo: 65,
            userName: '',
            mostPopularName: [],
            error: [],
            countName: null,
            averageAge: '',
            countRangeUsers: null,
            allUsers: {},
            url: '',
            currentFilter: null,
            formData:{
                firstname:'',
                surname:'',
                lastname:'',
                age:'',
                gender:'',
                education:'',
                city:'',
                id:'',
            },
            formType:'new',
        }
    },
    created() {

        this.getMostPopularName();
        this.getAverageAge();
        this.getAllUsers();
        this.init();
    },
    components: {
        userCart
    },
    methods: {
        pushUrl(key, value) {

            //reset get params
            history.pushState('', "", window.location.pathname);

            //set new value
            const url = new URL(location.href);
            url.searchParams.set(key, value)
            history.pushState('', "", url.href);

        },
        init() {
            let url = new URL(location.href)
            let urlSearchParams = url.searchParams

            let userName = urlSearchParams.get('userName')
            if (userName){
                this.setCurrentFilter('userName')
                this.userName = userName

            }

            let rangeAge = urlSearchParams.get('rangeAge')

            if (rangeAge){
                let chunk = rangeAge.split('-');
                if (chunk.length === 2){
                    this.setCurrentFilter('rangeAge')
                    this.rangeAgeFrom = chunk[0]
                    this.rangeAgeTo = chunk[1]
                }


            }


        },
        setCurrentFilter(value) {
           this.currentFilter = value
        },
        getMostPopularName() {
            axios({
                method: 'post',
                url: '/api/get/popular/name'
            })
                .then(response => (this.mostPopularName = response.data)).catch(function (error) {
                this.error.push(error)
            })
        },
        getCountName() {
            axios({
                method: 'post',
                url: '/api/get/count/popular/name',
                data: {
                    firstname: this.userName
                }
            })
                .then(response => (this.countName = response.data)).catch(function (error) {
                this.error.push(error)
            })

        },
        getAverageAge() {
            axios({
                method: 'post',
                url: '/api/get/average/age',
            })
                .then(response => (this.averageAge = Math.round(response.data['0']['AVG(age)']))).catch(function (error) {
                this.error.push(error)
            })
        },
        getRangeUsers() {
            axios({
                method: 'post',
                url: '/api/get/range/users',
                data: {
                    from: this.rangeAgeFrom,
                    to: this.rangeAgeTo
                }
            })
                .then(response => (this.countRangeUsers = response.data)).catch(function (error) {
                this.error.push(error)
            })
        },
        getAllUsers() {
            axios({
                method: 'post',
                url: '/api/get/all/users',
            })
                .then(response => (this.allUsers = response.data)).catch(function (error) {
                this.error.push(error)
            })
        },
        openForm(type, data){
            if (!data){
                this.resetForm()
            }
            if (data){
                this.formType = type
                this.formData.firstname = data.firstname
                this.formData.surname = data.surname
                this.formData.lastname = data.lastname
                this.formData.age = data.age
                this.formData.city = data.city
                this.formData.education = data.education
                this.formData.gender = data.gender
                this.formData.id = data.id
            }


            // new bootstrap.Modal(document.getElementById('exampleModal'))
            $('#exampleModal').modal('show');
        },
        resetForm(){
            this.formData.firstname = ''
            this.formData.id = ''
            this.formData.surname = ''
            this.formData.lastname = ''
            this.formData.age = ''
            this.formData.city = ''
            this.formData.education = ''
            this.formData.gender = ''
        },


        saveForm(type){
            axios({
                method: 'post',
                url: 'api/'+type+'/user/data',
                data: this.formData
            })
            this.getAllUsers()
            location.reload()

        }

    },
    watch: {
        currentFilter: function (e){

            if (e === 'userName'){
                this.getCountName()
                this.pushUrl(e,this.userName)
            }
            if (e === 'rangeAge'){
                this.getRangeUsers()
                let url = this.rangeAgeFrom + '-' + this.rangeAgeTo
                this.pushUrl(e,url)
            }
        },
        userName() {
           this.countName = null
           this.currentFilter= null
        },
        rangeAgeFrom() {
            this.countRangeUsers = null
            this.currentFilter= null
        },
        rangeAgeTo() {
            this.countRangeUsers = null
            this.currentFilter= null
        }
    },
    computed:{
        isCheckedForm: function () {
            let isCheck = true
            for (let [key, value] of Object.entries(this.formData)) {
               if (!value && key !== 'id'){
                   isCheck = false
               }
            }
            return isCheck
        }
    }
}
</script>

<template>
    <div class="container">
        <div class="row">


            <div class="col">
                <h3>Статистика</h3>
                    <div class="card p-4 mb-5">
                        <div v-if="countName !== null">
                            <div   class="d-flex align-items-center justify-content-between text-xs">
                                <div  class="fw-500">Колличество пользователей с именем  <span class="badge-success">{{userName}}</span> </div>
                                <div>{{countName}}</div>

                            </div>
                            <hr >
                        </div>

                        <div v-if="countRangeUsers !== null">
                            <div  class="d-flex align-items-center justify-content-between text-xs">
                                <div  class="fw-500">Пользователей от {{rangeAgeFrom + ' ' + 'до ' + rangeAgeTo + ' лет'}}</div>
                                <div>{{countRangeUsers}}</div>
                            </div>
                            <hr >
                        </div>
                        <div  class="d-flex align-items-center justify-content-between text-xs">
                            <div  class="fw-500">Средний возраст пользователей</div>
                            <div>{{averageAge}}</div>
                        </div>

                        <hr >
                        <div  class="d-flex align-items-center justify-content-between text-xs">
                            <div  class="fw-500">Самые популярные имена</div>
                            <div>
                                 <span v-for="item in mostPopularName" class="badge text-bg-light">
                                 {{item['firstname']}} <span class="badge bg-secondary">{{item['ModelsCount']}}</span>
                            </span>
                            </div>

                        </div>


                    </div>



            </div>
            <div class="col-4">

                <div class="card ml-4 border-light ">
                    <h3>Фильтр статистики</h3>

                    <h5 class="mt-2">По имени</h5>
                    <input type="text" class="form-control" placeholder="Колличество пользователей с именем"
                           v-model="userName">
                    <button :class="{'disable':!userName}" class="btn btn-success mt-3" @click="setCurrentFilter('userName')">Получить</button>

                    <h5 class="mt-4">По диапазону возраста</h5>

                        <label for="customRange3" class="form-label">От {{rangeAgeFrom}}</label>
                        <input type="range" class="form-range" min="0" :max="rangeAgeTo" step="1" id="customRange3"
                               v-model="rangeAgeFrom">
                        <label for="customRange3" class="form-label">до {{rangeAgeTo}}</label>
                        <input type="range" class="form-range " :min="rangeAgeFrom" max="65" step="1" id="customRange3"
                               v-model="rangeAgeTo">
                        <button class="btn btn-success mt-3" @click="setCurrentFilter('rangeAge')">Получить</button>

                </div>


            </div>


        </div>
        <div class="row users mt-5">
            <button class="btn btn-primary col-2 mb-3"  @click="openForm('new',null)">Добавить пользователя</button>

            <div class="bd-example">
                <table class="table">
                    <thead class="table-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Имя</th>
                        <th scope="col">Фамилия</th>
                        <th scope="col">Отчество</th>
                        <th scope="col">Возраст</th>
                        <th scope="col">Пол</th>
                        <th scope="col">Образование</th>
                        <th scope="col">Город проживания</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr   v-for="item in allUsers">
                        <th scope="row">{{item.id}}</th>
                        <td>{{item.firstname}}</td>
                        <td>{{item.surname}}</td>
                        <td>{{item.lastname}}</td>
                        <td>{{item.age}}</td>
                        <td>{{item.gender}}</td>
                        <td>{{item.education}}</td>

                        <td>{{item.city}}</td>
                        <td>    <button class="btn btn-success "  @click="openForm('update',item)">Изменить</button></td>
                    </tr>


                    </tbody>

                </table>
            </div>


        </div>
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 v-if="formType==='update'" class="modal-title fs-5" id="exampleModalLabel">Изменить пользователя</h1>
                        <h1 v-if="formType==='new'" class="modal-title fs-5" id="exampleModalLabel">Добавить пользователя</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Имя</label>
                            <input type="text" v-model="formData.firstname" class="form-control" id="exampleFormControlInput1" placeholder="Лаврентий">
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Фамилия</label>
                            <input type="text" v-model="formData.surname" class="form-control" id="exampleFormControlInput1" placeholder="Орлов">
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Отчество</label>
                            <input type="text" v-model="formData.lastname" class="form-control"  placeholder="Соколов">
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Возраст</label>
                            <input type="number" v-model="formData.age" class="form-control" >
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Пол</label>
                            <select class="form-select" v-model="formData.gender" aria-label="Default select example">
                                <option selected>мужской</option>
                                <option >женский</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlInput1"  class="form-label">Образование</label>
                            <input type="text" v-model="formData.education" class="form-control"  >
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Город проживания	</label>
                            <input type="text" v-model="formData.city" class="form-control"  >
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                        <button :class="{'disable':!isCheckedForm}"  type="button" @click="saveForm(formType)" class="btn btn-primary">Сохранить</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


</template>

<style>
    .disable{
        opacity: 0.4;
        pointer-events: none;
    }

    .badge-success{
        background-color: #02BC77;
        color: #fff;
    }
</style>
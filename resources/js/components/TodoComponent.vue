<template>
    <div>
        <div v-if="warning"
            class="alert alert-danger alert-hide" role="alert"
        >
            {{ msg }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div v-if="success"
            class="alert alert-success alert-hide" role="alert"
        >
            {{ msg }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form @submit.prevent="saveTodo">
            <div class="input-group mb-3">
                <input v-model="title"
                    type="text" class="form-control input-custom" placeholder="Enter title here..."
                    aria-label="Recipient's username" aria-describedby="basic-addon2"
                >
                <div class="input-group-append">
                    <button class="btn btn-success btn-add" type="submit">Add</button>
                </div>
            </div>
        </form>
        <table class="table bg-white">
            <thead>
                <tr>
                    <th scope="col">Status</th>
                    <th scope="col">Title</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="todo in todos" :key="todo.id">
                    <th scope="row">
                        <span class="mr-2">
                            <svg v-if="!todo.completed" @click="toggleTodo(todo)"
                                xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle"
                                width="36" height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="#FFC107"
                                fill="none" stroke-linecap="round" stroke-linejoin="round"
                            >
                                <title>[ Todo is not completed todo yet :) ]</title>
                                <path stroke="none" d="M0 0h24v24H0z"/>
                                <circle cx="12" cy="12" r="9" />
                            </svg>
                            <svg v-if="todo.completed" @click="toggleTodo(todo)"
                                xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-check"
                                width="36" height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="#4CAF50" fill="none"
                                stroke-linecap="round" stroke-linejoin="round"
                            >
                                <title>[ Todo is completed todo :) ]</title>
                                <path stroke="none" d="M0 0h24v24H0z"/>
                                <circle cx="12" cy="12" r="9" />
                                <path d="M9 12l2 2l4 -4" />
                            </svg>
                        </span>
                        <!-- Modal -->
                        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalCenterTitle" aria-hidden="true"
                            v-if="description_id == todo.id"
                        >
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">Description</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <textarea cols="50" rows="10" placeholder="Enter your description here..."
                                            v-model="temp_description"
                                        ></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary"
                                            @click="addDescription(todo)"
                                        >
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End of Modal -->
                    </th>
                    <td v-if="!edit || edit != todo.id" class="custom-title"
                        v-bind:class="[{ 'text-muted ' : todo.completed }, { 'text-del' : todo.completed } ]"
                    >
                        {{ todo.title }}
                    </td>
                    <!-- if user wanna edit todo -->
                    <td v-if="edit == todo.id">
                        <input class="edit-input" type="text" v-model="temp_title">
                    </td>
                    <td>
                        <!-- Show icon -->
                        <span class="mr-2">
                            <svg class="svg-custom" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                aria-hidden="true" focusable="false" width="1.4em" height="1em"
                                style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);"
                                preserveAspectRatio="xMidYMid meet" viewBox="0 0 1792 1280"
                                data-toggle="modal" data-target="#exampleModal" @click="show_id = todo.id"
                            >
                            <path d="M1664 704q-152-236-381-353q61 104 61 225q0 185-131.5 316.5T896 1024T579.5 892.5T448 576q0-121
                                61-225q-229 117-381 353q133 205 333.5 326.5T896 1152t434.5-121.5T1664 704zM944 320q0-20-14-34t-34-14q-125
                                0-214.5 89.5T592 576q0 20 14 34t34 14t34-14t14-34q0-86 61-147t147-61q20 0 34-14t14-34zm848 384q0 34-20
                                69q-140 230-376.5 368.5T896 1280t-499.5-139T20 773Q0 738 0 704t20-69q140-229 376.5-368T896 128t499.5
                                139T1772 635q20 35 20 69z" fill="#626262"/>
                            </svg>
                            <!-- Modal SHOW -->
                            <div class="modal fade" id="exampleModal" tabindex="-1"
                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
                                v-if="show_id == todo.id"
                            >
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">About Todo</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <h3>Title: {{ todo.title }}</h3>
                                            <p v-if="todo.description" >
                                                Description: {{ todo.description }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End of modal -->
                        </span>
                        <!-- End of show icon -->
                        <!-- Edit icon -->
                        <span class="mr-2" v-if="!todo.completed">
                            <!-- default icon -->
                            <svg v-on:click="edit = todo.id; temp_title = todo.title"  v-if="edit != todo.id"
                                xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit"
                                width="36" height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="#FFC107"
                                fill="none" stroke-linecap="round" stroke-linejoin="round"
                            >
                                <title>[ Edit todo ]</title>
                                <path stroke="none" d="M0 0h24v24H0z"/>
                                <path d="M9 7 h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3" />
                                <path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3" />
                                <line x1="16" y1="5" x2="19" y2="8" />
                            </svg>
                            <!-- to submit -->
                            <svg v-if="edit == todo.id" v-on:click="updateTodo(todo)"
                                xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-checkbox"
                                width="36" height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="#4CAF50"
                                fill="none" stroke-linecap="round" stroke-linejoin="round"
                            >
                                <path stroke="none" d="M0 0h24v24H0z"/>
                                <polyline points="9 11 12 14 20 6" />
                                <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9" />
                            </svg>
                        </span>
                        <!-- Modal icon -->
                        <span class="mr-2">
                            <svg v-if="!todo.completed" @click="description_id = todo.id; temp_description = todo.description"
                                data-toggle="modal" data-target="#exampleModalCenter"
                                stroke="#00CC00" width="36" height="36" class="svg-circleplus" viewBox="0 0 100 100"
                            >
                                <circle cx="50" cy="50" r="45" fill="none" stroke-width="7.5"></circle>
                                <line x1="32.5" y1="50" x2="67.5" y2="50" stroke-width="5"></line>
                                <line x1="50" y1="32.5" x2="50" y2="67.5" stroke-width="5"></line>
                            </svg>
                        </span>

                        <!-- End of Modal icon -->
                        <!-- Delete icon -->
                        <span class="mr-2">
                            <svg @click="deleteTodo(todo)"
                                xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash ml-1"
                                width="36" height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="#FF5722"
                                fill="none" stroke-linecap="round" stroke-linejoin="round"
                            >
                                <title>[ Delete todo ]</title>
                                <path stroke="none" d="M0 0h24v24H0z"/>
                                <line x1="4" y1="7" x2="20" y2="7" />
                                <line x1="10" y1="11" x2="10" y2="17" />
                                <line x1="14" y1="11" x2="14" y2="17" />
                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                            </svg>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li :class="{ disabled: !pagination.prev_page_url }"
                    @click.prevent="getTodos(pagination.prev_page_url)"
                    class="page-item">
                    <a class="page-link" href="#" aria-label="Previous">
                        Previous
                    </a>
                </li>
                <li class="page-item disabled">
                    <a class="page-link" href="#" >
                       Page {{ pagination.current_page }} from {{ pagination.last_page }}
                    </a>
                </li>
                <li :class="{ disabled: !pagination.next_page_url }"
                    @click.prevent="getTodos(pagination.next_page_url)"
                    class="page-item"
                >
                    <a class="page-link" href="#" aria-label="Next">
                        Next
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                title: '',
                temp_title: '',
                warning: false,
                success: false,
                temp_description: '',
                msg: '',
                todos: '',
                edit: false,
                show_id: false,
                description_id: false,
                pagination: {},
            }
        },
        methods:{
            getTodos(page_url){
                page_url = page_url || '/api/todo'
                axios
                    .get(page_url)
                    .then( res => { this.todos = res.data.data; this.makePagination(res.data) })
                    .catch( err => { console.log(err.response.data); })
            },
            saveTodo(){
                let data = new FormData;
                if(this.title.length <= 3 || this.title.length >= 255){
                    this.msg = 'Hey dude title must be more then 3 letters and less than 255 letters';
                    console.log(this.msg);
                    this.warning = true;
                    setTimeout( () => { this.warning = false; }, 3000 );
                }
                else{
                    data.append('title', this.title);
                    axios
                        .post('/api/todo', data)
                        .then( res => {
                            if(res.data.status == 'error'){
                                this.msg = res.data.msg;
                                console.log(this.msg);
                                this.warning = true;
                                setTimeout( () => { this.warning = false; }, 3000 );
                            }
                            else{
                                this.success = true;
                                this.msg = `${this.title} successfully saved`;
                                setTimeout( () => { this.success = false; }, 3000 );
                                this.title = '';
                                this.getTodos();
                            }
                        })
                        .catch( err => {
                            this.msg = err.response.data.errors.title[0];
                            this.warning = true;
                        })
                }
            },
            toggleTodo(e){
                let data = new FormData;
                this.edit = false;
                e.completed = !e.completed;
                data.append('_method', 'PATCH');
                data.append('completed', Number(+e.completed));
                data.append('title', e.title);
                axios
                    .post('/api/todo/' + e.id, data)
                    .catch(err => {
                        console.log(err.response.data.data);
                    })
            },
            deleteTodo(e){
                let answer = confirm("Are sure for deleting: " + e.title);
                if (!answer){
                    return false;
                }
                else{
                    axios
                        .delete('/api/todo/' + e.id)
                        .then( res => {
                            this.success = true;
                            this.msg = `${e.title} successfully deleted`;
                            setTimeout( () => { this.success = false; }, 3000 );
                            this.getTodos();
                        })
                        .catch( err => {
                            console.log(err.response.data);
                        });
                }

            },
            updateTodo(e){
                let data = new FormData;
                this.edit = false;
                if(this.temp_title.length <= 3){
                    this.msg = 'Hey dude title must be more then 3 letters';
                    console.log(this.msg);
                    this.warning = true;
                    setTimeout( () => { this.warning = false; }, 3000 );
                }

                else if(this.temp_title === e.title){
                    return false;
                }
                else{
                    data.append('_method', 'PATCH');
                    data.append('title', this.temp_title);
                    data.append('completed', e.completed);
                    axios
                        .post('/api/todo/' + e.id, data)
                        .then(res => {
                            if(res.data.status == 'error'){
                                this.msg = res.data.msg;
                                console.log(this.msg);
                                this.warning = true;
                                setTimeout( () => { this.warning = false; }, 3000 );
                            }
                            else{
                                this.success = true;
                                this.msg = `${e.title} successfully updated to ${this.temp_title}`;
                                setTimeout( () => { this.success = false; }, 3000 );
                                this.getTodos();
                            }
                        })
                        .catch( err => {
                            console.log(err.response.data);
                        })
                }

            },
            makePagination(response){
                this.pagination = {
                    current_page: response.current_page,
                    last_page: response.last_page,
                    prev_page_url: response.prev_page_url,
                    next_page_url: response.next_page_url
                }
            },
            addDescription(e){
                let data = new FormData;
                if(this.temp_description == null || this.temp_description.length <= 3){
                    let error_msg = 'Hey dude title must be more then 3 letters';
                    alert(error_msg);
                }
                else if(e.description === this.temp_description){
                    let error_msg = 'Hey dude description is the same with previous one';
                    alert(error_msg);
                }
                else{
                    data.append('_method', 'PATCH');
                    data.append('title', e.title);
                    data.append('completed', e.completed);
                    data.append('description', this.temp_description);
                    axios
                        .post('/api/todo/' + e.id, data)
                        .then(res => {
                            if(res.data.status == 'error'){
                                this.msg = res.data.msg;
                                console.log(this.msg);
                                this.warning = true;
                                setTimeout( () => { this.warning = false; }, 3000 );
                            }
                            else{
                                let modal = document.getElementById('exampleModalCenter');
                                modal.classList.remove('show');
                                modal.setAttribute('aria-hidden', 'true');
                                modal.setAttribute('style', 'display: none');
                                let modalBackdrops = document.getElementsByClassName('modal-backdrop');
                                document.body.removeChild(modalBackdrops[0]);
                                this.success = true;
                                this.msg = `Your "${this.temp_description}" description successfully saved`;
                                setTimeout( () => { this.success = false; }, 3000 );
                                this.getTodos();
                            }
                        })
                        .catch(err => {
                            console.log(err.response.data);
                        })
                }

            }
        },
        mounted() {
            this.getTodos();
        }
    }
</script>

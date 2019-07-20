<template>
    <div class="container">
        
        <div class="row pt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Users</h3>
                    <div class="card-tools">
                        <button class="btn btn-success" @click="newModal">Add New&nbsp;<i class="fa fa-user-plus fa-fw"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Role</th>
                      <th>Registered At</th>                      
                      <th>Modify</th>
                    </tr>

                    <tr v-for="user in users" :key="user.id">
                        <td>{{user.id}} </td>
                        <td>{{user.name}}</td>        
                        <td>{{user.email}}</td>
                        <td><span class="tag tag-success">{{'role here' | upText}}</span></td>
                        <td>{{user.created_at | myDate}}</td>
                        <td>
                            <a href="#" @click="editModal(user)">
                                <i class="fa fa-edit blue"></i>
                            </a>
                            &nbsp;
                            <a href="#" @click="deleteUser(user.id)">
                                <i class="fa fa-trash red"></i>
                            </a>
                        </td>                                                                              
                    </tr>

                  </thead>
                  <tbody>



                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->

 
        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 v-show="!editmode" class="modal-title" id="exampleModalLongTitle">Add New User</h5>
                <h5 v-show="editmode" class="modal-title" id="exampleModalLongTitle">Update User Details</h5>                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <form @submit.prevent="editmode ? updateUser() : createUser()">

              <div class="modal-body"> 
                


                <div class="form-group">
                  <input v-model="form.name" type="text" name="name"
                    placeholder = "Full Name"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                  <has-error :form="form" field="name"></has-error>
                </div>



                <div class="form-group">
                  <input v-model="form.email" type="email" name="email" id="email"
                    placeholder = "Email Address"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                  <has-error :form="form" field="email"></has-error>
                </div>


                <div class="form-group">
                  <select name="role" v-model="form.role" id="role" class="form-control" :class="{ 'is-invalid': form.errors.has('role') }">
                    <option value="">Select User's Role</option>                    
                    <option value="user">Normal User</option>
                    <option value="admin">Admin</option> 
                    <option value="superadmin">SuperAdmin</option>                                       
                  </select>
                  <has-error :form="form" field="role"></has-error>
                </div>


                <div class="form-group">
                  <input v-model="form.password" type="password" name="password" id="password"
                    placeholder = "Password"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                  <has-error :form="form" field="password"></has-error>
                </div>


              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button v-show="editmode" type="submit" class="btn btn-success">Update User</button>
                <button v-show="!editmode" type="submit" class="btn btn-primary">Create User</button>                
              </div>

          </form>

            </div>
          </div>
        </div>

    </div>




</template>

<script>
    export default {
        data() {
            return {
                editmode: false,
                users : {},
                form: new Form({
                    name : '',
                    email : '',
                    role : '',                    
                    password : ''
                })

            }
        },
        methods: {
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },             
            editModal(user){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(user);
            },                  
            deleteUser(id) {
                toast.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
                  type: 'warning',
                  showCancelButton: true,
                  showConfirmButton: true,                  
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, delete it!',
                  timer: 20000
                }).then((result) => {


                  // Send request to server  
                  if (result.value) {
                          this.form.delete('api/user/'+id).then(()=>{

                                toast.fire(
                                  'Deleted!',
                                  'Your file has been deleted.',
                                  'success'
                                )
                            

                                    Fire.$emit('CreateUser'); // custom event emitted for refresh table



                          }).catch(()=> {
                            toast.fire({
                              title: 'Are you sure?',
                              text: "You won't be able to revert this!",
                              type: 'warning',
                            });
                          });
                    }


                })
            },
            updateUser() {
                Console.log('Editing Data');
            },  

            loadUsers() {
                axios.get("api/user").then(({ data }) => (this.users = data.data));
            },

            createUser(){
                this.$Progress.start(); // start progress bar
                
                this.form.post('api/user').then(() => {
                    Fire.$emit('CreateUser'); // custom event emitted for refresh table
                });

                $('#addNew').modal('hide');

                toast.fire({
                  type: 'success',
                  title: 'User Created Successfully'
                }) 

                this.$Progress.finish(); // end progress bar

            }
        },
        created() {
            this.loadUsers(); // default function

            Fire.$on('CreateUser',() => { // listen for AfterCreate and update table
                this.loadUsers();
            });
            // setInterval(() => this.loadUsers(), 3000); // this blindly updates every 3 secs
        }
    } 
</script>

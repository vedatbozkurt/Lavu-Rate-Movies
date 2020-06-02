<template>
  <div class="container">
   <!-- /.row -->
   <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <b>Users</b>
          <div class="card-tools" v-if="$gate.isAdmin()">
            <button class="btn btn-outline-success btn-sm btn-flat" @click="createModal">
              <i class="fas fa-plus"></i> New
            </button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th style="width:110px;" v-if="$gate.isAdmin()">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="user in users.data" :key="user.id">
                <td>{{ user.id }}</td>
                <td>{{ user.name }}</td>
                <td>{{ user.email }}</td>
                <td v-if="$gate.isAdmin()">
                  <button class="btn btn-outline-info btn-sm btn-flat" @click="editModal(user)">
                    <i class="fas fa-edit"></i>
                  </button>
                  <button class="btn btn-outline-danger btn-sm btn-flat"  @click.prevent="deleteUser(user.id)">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          <pagination class="float-right" :data="users" @pagination-change-page="getResults"></pagination>
        </div>
      </div>
      <!-- /.card -->
    </div>
  </div>
  <!-- /.row -->


  <div id="bs-modal">
    <div class="modal fade" id="createEditModal"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button @click.prevent="getResults(page)" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New User</h5>
            <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update User</h5>
          </div>
          <form @submit.prevent="editmode ? updateUser() : createUser()">
            <div class="modal-body">
              <div class="row">
                <div v-if="errors_exist" class="col-md-12 alert alert-danger" role="alert">
                  Whoops! Something didn't work.
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Name:</label><span class="text-danger" v-if="errors_exist && errors.name"> {{ errors.name[0] }}</span>
                    <input type="text" v-bind:class="{ 'is-invalid': errors_exist && errors.name }" class="form-control" v-model="user.name">

                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Email:</label><span class="text-danger" v-if="errors_exist && errors.email"> {{ errors.email[0] }}</span>
                    <input type="text" v-bind:class="{ 'is-invalid': errors_exist && errors.email }" class="form-control" v-model="user.email">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Password:</label><span class="text-danger" v-if="errors_exist && errors.password"> {{ errors.password[0] }}</span>
                    <input type="password" v-bind:class="{ 'is-invalid': errors_exist && errors.password }" class="form-control" v-model="user.password">
                  </div>
                </div>
              </div>
            <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Status:</label><span class="text-danger" v-if="errors_exist && errors.status"> {{ errors.status[0] }}</span>
                    <select class="form-control" v-bind:class="{ 'is-invalid': errors_exist && errors.status }" v-model="user.status">
                      <option value="0">Inactive</option>
                      <option value="1">Active</option>
                    </select>
                  </div>
                </div>
              </div>
            <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Role:</label><span class="text-danger" v-if="errors_exist && errors.role"> {{ errors.status[0] }}</span>
                    <select class="form-control" v-bind:class="{ 'is-invalid': errors_exist && errors.role }" v-model="user.role">
                      <option v-for="role in roles" :value="role.id">{{role.name}}</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" @click.prevent="getResults(page)" class="btn btn-default" data-dismiss="modal">Close</button>
              <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
              <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

</div>
</template>

<script>
  export default {
    data() {
      return {
        errors_exist : false,
        errors: {},
        editmode: false,
        user:{},
        page:'',
        users: {},
        roles: {},
      }
    },
    mounted() {
        // Fetch initial results
        this.getResults();

      },
      methods: {
        editModal(user){
          this.editmode = true;
          this.user = user;
          this.errors = {};
          this.errors_exist = false;
          $('#createEditModal').modal('show');
        },
        createModal(){
          this.editmode = false;
          this.user = {};
          this.errors = {};
          this.errors_exist = false;
          $('#createEditModal').modal('show');
        },
        getResults(page = 1) {
          axios.get('api/v1/users?page=' + page)
          .then(response => {
            this.users = response.data.user;
            this.page = response.data.current_page;
            this.roles = response.data.roles;
          });
        },
        deleteUser(id){
          Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
          }).then((result) => {
            if (result.value) {
              this.deleteConfirm(id)
            }
          });
        },
        deleteConfirm(id)
        {
          let uri = `api/v1/users/${id}`;
          this.axios.delete(uri).then(response => {
            this.getResults(this.page);
            this.afterDeleteAlert('success','User has been deleted.');
          });
        },
        createUser(){
          let uri = 'api/v1/users';
          this.axios.post(uri, this.user)
          .then((response) => {
            this.getResults(this.page);
            $('#createEditModal').modal('hide');
            this.myToast('success','User has been created.');
          })
          .catch(error => {
            this.errors = {};
            this.errors_exist = true;
            if(error.response.status === 422) {
              this.errors = error.response.data.errors || {};
            }
          });
        },
        updateUser() {
          let uri = `api/v1/users/${this.user.id}`;
          this.axios.put(uri, this.user).then((response) => {
            $('#createEditModal').modal('hide');
            this.myToast('success','User has been updated.');
          })
          .catch(error => {
            this.getResults(this.page);
            this.errors = {};
            this.errors_exist = true;
            if(error.response.status === 422) {
              this.errors = error.response.data.errors || {};
            }
          });
        }
      },
    }
  </script>

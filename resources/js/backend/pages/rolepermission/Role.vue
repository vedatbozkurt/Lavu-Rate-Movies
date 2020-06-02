<template>
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <b>Roles</b>
            <div class="card-tools">
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
                  <th>Name</th>
                  <th style="width:150px;">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="role in roles.data" :key="role.id">
                  <td>{{ role.name }}</td>
                  <td>
                    <button class="btn btn-outline-primary btn-sm btn-flat" @click="">
                      <i class="fas fa-info-circle"></i>
                    </button>
                    <button class="btn btn-outline-info btn-sm btn-flat" @click="editModal(role)">
                      <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-outline-danger btn-sm btn-flat"  @click.prevent="deleteRole(role.id)">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <pagination class="float-right" :data="roles" @pagination-change-page="getResults"></pagination>
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
              <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New Role</h5>
              <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update Role</h5>
            </div>
            <form @submit.prevent="editmode ? updateRole() : createRole()">
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
                      <input type="text" v-bind:class="{ 'is-invalid': errors_exist && errors.name }" class="form-control" v-model="role.name">
                    </div>
                  </div>
                </div>


                <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Permissions:</label>
                    <select multiple class="form-control" multiple="true" v-model="newpermissions">
                      <option v-for="permission in permissions" v-bind:key="permission.id" :value="permission.id">{{permission.name}}</option>
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
        role:{},
        page:'',
        roles: {},
        permissions: {},
        newpermissions: []
      }
    },
    mounted() {
        // Fetch initial results
        this.getResults();
      },
      methods: {
        editModal(role){
          this.editmode = true;
          this.role = role;
          this.errors = {};
          this.errors_exist = false;
          $('#createEditModal').modal('show');
          axios.get('api/v1/roles/create')
          .then(response => {
            this.permissions = response.data;
          });
          let uri = `api/v1/roles/${role.id}/edit`;
          this.axios.get(uri).then((response) => {
            this.newpermissions = response.data;
          });
        },
        createModal(){
          this.editmode = false;
          this.role = {};
          this.errors = {};
          this.errors_exist = false;
          $('#createEditModal').modal('show');
          axios.get('api/v1/roles/create')
          .then(response => {
            this.permissions = response.data;
          });
        },
        getResults(page = 1) {
          axios.get('api/v1/roles?page=' + page)
          .then(response => {
            this.roles = response.data;
            this.newpermissions = [];
            this.permissions = {};
            this.page = response.data.current_page;
          });
        },
        deleteRole(id){
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
          let uri = `api/v1/roles/${id}`;
          this.axios.delete(uri).then(response => {
            this.getResults(this.page);
            this.myToast('success','Role has been deleted.');
          });
        },
        createRole(){
          this.role.permission = this.newpermissions;
          let uri = 'api/v1/roles';
          this.axios.post(uri, this.role)
          .then((response) => {
            this.getResults(this.page);
            $('#createEditModal').modal('hide');
            this.newpermissions = [];
            this.myToast('success','Role has been created.');
          })
          .catch(error => {
            this.errors = {};
            this.errors_exist = true;
            if(error.response.status === 422) {
              this.errors = error.response.data.errors || {};
            }
          });
        },
        updateRole() {
          this.role.permission = this.newpermissions;
          let uri = `api/v1/roles/${this.role.id}`;
          this.axios.put(uri, this.role).then((response) => {
            $('#createEditModal').modal('hide');
            this.myToast('success','Role has been updated.');
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

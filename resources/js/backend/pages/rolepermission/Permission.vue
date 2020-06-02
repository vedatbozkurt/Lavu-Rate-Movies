<template>
  <div class="container">
   <!-- /.row -->
   <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <b>Permissions</b><small> *You have to add middlewares on Controller. Check documentation.</small>
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
                <th>Guard</th>
                <th style="width:110px;">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="permission in permissions.data" :key="permission.id">
                <td>{{ permission.name }}</td>
                <td>{{ permission.guard_name }}</td>
                <td>
                  <button class="btn btn-outline-info btn-sm btn-flat" @click="editModal(permission)">
                    <i class="fas fa-edit"></i>
                  </button>
                  <button class="btn btn-outline-danger btn-sm btn-flat"  @click.prevent="deletePermission(permission.id)">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          <pagination class="float-right" :data="permissions" @pagination-change-page="getResults"></pagination>
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
            <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New Permission</h5>
            <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update Permission</h5>
          </div>
          <form @submit.prevent="editmode ? updatePermission() : createPermission()">
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
                    <input type="text" v-bind:class="{ 'is-invalid': errors_exist && errors.name }" class="form-control" v-model="permission.name">

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
        permission:{},
        page:'',
        permissions: {},
      }
    },
    mounted() {
        // Fetch initial results
        this.getResults();

      },
      methods: {
        editModal(permission){
          this.editmode = true;
          this.permission = permission;
          this.errors = {};
          this.errors_exist = false;
          $('#createEditModal').modal('show');
        },
        createModal(){
          this.editmode = false;
          this.permission = {};
          this.errors = {};
          this.errors_exist = false;
          $('#createEditModal').modal('show');
        },
        getResults(page = 1) {
          axios.get('api/v1/permissions?page=' + page)
          .then(response => {
            this.permissions = response.data;
            this.page = response.data.current_page;
          });
        },
        deletePermission(id){
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
          let uri = `api/v1/permissions/${id}`;
          this.axios.delete(uri).then(response => {
            this.getResults(this.page);
            this.afterDeleteAlert('success','Permission has been deleted.');
          });
        },
        createPermission(){
          let uri = 'api/v1/permissions';
          this.axios.post(uri, this.permission)
          .then((response) => {
            this.getResults(this.page);
            $('#createEditModal').modal('hide');
            this.myToast('success','Permission has been created.');
          })
          .catch(error => {
            this.errors = {};
            this.errors_exist = true;
            if(error.response.status === 422) {
              this.errors = error.response.data.errors || {};
            }
          });
        },
        updatePermission() {
          let uri = `api/v1/permissions/${this.permission.id}`;
          this.axios.put(uri, this.permission).then((response) => {
            $('#createEditModal').modal('hide');
            this.myToast('success','Permission has been updated.');
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

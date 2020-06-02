<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <b>Backups</b>
            <div class="card-tools">
              <button class="btn btn-outline-success btn-sm btn-flat" @click="newBackup">
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
                  <th style="width:110px;">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="backup in backups">
                  <td>{{ backup }}</td>
                  <td>
                    <button class="btn btn-outline-info btn-sm btn-flat" @click.prevent="downloadBackup(backup)">
                      <i class="fas fa-download"></i>
                    </button>
                    <button class="btn btn-outline-danger btn-sm btn-flat" @click.prevent="deleteBackup(backup)">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
          </div>
        </div>
        <!-- /.card -->
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        errors_exist : false,
        backups: {},
        file: {},
      }
    },
    mounted() {
      this.getBackups();
    },
    created() {

    },
    methods: {
      getBackups() {
        let uri = `api/v1/getbackups`;
        this.axios.get(uri).then((response) => {
          this.backups = response.data;
        });
      },
      downloadBackup(backup) {
        this.file.name = backup;
        let uri = `api/v1/downloadbackup/`;
        this.axios.post(uri, this.file).then((response) => {
          this.myToast('success','Backup has been downloaded.');
        })
        .catch(error => {
            // toast basarisiz indirme
          });
      },
      newBackup() {
        let uri = `api/v1/createbackup`;
        this.axios.post(uri).then((response) => {
          this.myToast('success','Backup has been created.');
          this.getBackups();
        });
      },
      deleteBackup(backup){
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
              this.deleteBackupConfirm(backup)
            }
          });
        },
        deleteBackupConfirm(backup)
        {
          this.file.name = backup;
          let uri = `api/v1/deletebackup`;
          this.axios.post(uri, this.file).then((response) => {
            this.getBackups();
            this.afterDeleteAlert('success','Backup has been deleted.');
          });
        },
    }
  }
</script>

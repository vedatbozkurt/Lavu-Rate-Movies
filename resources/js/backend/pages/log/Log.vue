<template>
  <div class="container">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Logs</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <div class="btn-group">
                <button type="button" class="btn btn-outline-danger btn-sm btn-flat dropdown-toggle dropdown-hover" data-toggle="dropdown"><i class="fas fa-trash-alt"></i> Clear Cache</button>
                <span class="sr-only">Toggle Dropdown</span>
                <div class="dropdown-menu" role="menu">
                  <a class="dropdown-item" @click="cacheClear" href="#">Clear Cache</a>
                  <a class="dropdown-item" @click="cacheView" href="#">Clear View Caches</a>
                  <a class="dropdown-item" @click="cacheRoute" href="#">Clear Route Caches</a>
                  <a class="dropdown-item" @click="cacheConfig" href="#">Clear Config Caches</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" @click="cacheClearAll" href="#">Clear All Cache</a>
                </div>
              </button>
            </div> &nbsp;
            <button class="btn btn-outline-danger btn-sm btn-flat" @click="deleteAllLogs">
              <i class="fas fa-trash-alt"></i> Delete All Logs
            </button>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <div class="row justify-content-center">
    <div class="col-md-12">
      <!-- The time line -->
      <div class="timeline">
        <!-- timeline item -->
        <div v-for="log in logs.data" v-bind:key="log.id">
          <i v-if="log.properties.status === 'success'" class="fas fa-info bg-green"></i>
          <i v-else-if="log.properties.status === 'error'" class="fas fa-info bg-red"></i>
          <i v-else-if="log.properties.status === 'info'" class="fas fa-info bg-blue"></i>
          <div class="timeline-item">
            <span class="time"><i class="fas fa-clock"></i> {{ log.created_at | moment("from", "now") }} <button class="btn btn-outline-danger btn-xs btn-flat" @click="deleteLog(log.id)">
              <i class="fas fa-minus-circle"></i>
            </button></span>
            <h3 class="timeline-header">{{ log.properties.user }} {{ log.description }}</h3>
          </div>
        </div>
        <!-- END timeline item -->
      </div>
      <div class="d-flex justify-content-center"><pagination class="float-right" :data="logs" @pagination-change-page="getLogs"></pagination></div>

    </div>
    <!-- /.col -->
  </div>
</div>
<!-- /.timeline -->
</div>
</div>
</div>
</template>

<script>
  export default {
    data() {
      return {
        errors_exist : false,
        logs: {},
        page:'',
      }
    },
    created() {
      this.getLogs();
    },
    mounted() {
        // console.log('Component mounted.')
      },
      methods:{
        getLogs(page = 1) {
          let uri = `api/v1/logs?page=` + page;
          this.axios.get(uri).then((response) => {
            this.logs = response.data;
            this.page = response.data.current_page;
          });
        },
        deleteLog(id){
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
              this.deleteLogConfirm(id)
            }
          });
        },
        deleteLogConfirm(id)
        {
          let uri = `api/v1/deletelog/${id}`;
          this.axios.delete(uri).then(response => {
            this.getLogs(this.page);
            this.myToast('success','Log has been deleted.');
          });
        },
        deleteAllLogs(){
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
              this.deleteAllLogsConfirm()
            }
          });
        },
        deleteAllLogsConfirm()
        {
          let uri = `api/v1/deletealllogs`;
          this.axios.delete(uri).then(response => {
            this.getLogs();
            this.myToast('success','All Logs has been deleted.');
          });
        },
        cacheClearAll() {
          this.axios.get('api/v1/cacheclearall').then((response) => {
            this.myToast('success','All Caches has been deleted.');
          });
        },
        cacheClear() {
          this.axios.get('api/v1/cacheclear').then((response) => {
            this.myToast('success','Caches has been deleted.');
          });
        },
        cacheView() {
          this.axios.get('api/v1/cacheview').then((response) => {
            this.myToast('success','View Caches has been deleted.');
          });
        },
        cacheRoute() {
          this.axios.get('api/v1/cacheroute').then((response) => {
            this.myToast('success','Route Caches has been deleted.');
          });
        },
        cacheConfig() {
          this.axios.get('api/v1/cacheconfig').then((response) => {
            this.myToast('success','Config Caches has been deleted.');
          });
        },
     }
   }
 </script>

<template>
    <div class="container">
      <br>
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{ user }}</h3>

                <p>User Registrations</p>
              </div>
              <div class="icon">
                <i class="fas fa-user-plus"></i>
              </div>
              <router-link :to="{ name: 'users' }" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </router-link>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{ role }}</h3>

                <p>Roles</p>
              </div>
              <div class="icon">
                <i class="fas fa-unlock-alt"></i>
              </div>
              <router-link :to="{ name: 'role' }" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </router-link>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box bg-primary">
              <div class="inner">
                <h3>{{ permission }}</h3>

                <p>Permissions</p>
              </div>
              <div class="icon">
                <i class="fas fa-ban"></i>
              </div>
              <router-link :to="{ name: 'permission' }" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </router-link>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>{{ activity }}</h3>

                <p>Activity Logs</p>
              </div>
              <div class="icon">
                <i class="fas fa-list-ul"></i>
              </div>
              <router-link to="/log" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </router-link>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->

        <div class="card card-default">
            <div class="card-header">Dashboard</div>
            <div class="card-body">
               {{ $t("message.hello") }}
            </div>
        </div>
    </div>
</template>
<script>
  export default {
    data() {
      return {
        role:'',
        permission:'',
        activity:'',
        user:'',
      }
    },
    components: {
      //
    },
    created() {
      this.getDashboard();
    },
    methods : {
      getDashboard() {
          let uri = `api/v1/dashboard`;
          this.axios.get(uri).then((response) => {
            this.role = response.data.role;
            this.permission = response.data.permission;
            this.activity = response.data.activity;
            this.user = response.data.user;
          });
        }
    }
  }
</script>

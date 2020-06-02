<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info">
                  <div class="card-header">
                    <h3 class="card-title">Edit Profile</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form class="form-horizontal" @submit.prevent="updateThisUser()">
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" v-model="user.name" v-bind:class="{ 'is-invalid': errors_exist && errors.name }">
                              <span class="text-danger" v-if="errors_exist && errors.name"> {{ errors.name[0] }}</span>
                          </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                          <input type="email" class="form-control" v-model="user.email" v-bind:class="{ 'is-invalid': errors_exist && errors.email }">
                          <span class="text-danger" v-if="errors_exist && errors.email"> {{ errors.email[0] }}</span>
                      </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-10">
                      <input type="password" class="form-control" v-model="user.password" v-bind:class="{ 'is-invalid': errors_exist && errors.password }">
                      <span class="text-danger" v-if="errors_exist && errors.password"> {{ errors.password[0] }}</span>
                  </div>
              </div>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
              <button type="submit" class="btn btn-info">Save</button>
              <router-link to="/" class="btn btn-default float-right">
                  Cancel
              </router-link>
          </div>
          <!-- /.card-footer -->
      </form>
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
            user: {}
        }
    },
    created() {
      let uri = `api/v1/user`;
      this.axios.get(uri).then((response) => {
        this.user = response.data.data;
    });
  },
  mounted() {
        // console.log('Component mounted.')
    },
    methods:{
        updateThisUser() {
          let uri = `api/v1/user`;
          this.axios.post(uri, this.user).then((response) => {
            console.log(this.user);
            this.myToast('success','User has been updated.');
        })
          .catch(error => {
            this.errors = {};
            this.errors_exist = true;
            if(error.response.status === 422) {
              this.errors = error.response.data.errors || {};
          }
            this.myToast('error',`Whoops! Something didn't work.`);

      });
      }
  }

}
</script>

<template>
  <div class="container">
   <!-- /.row -->
   <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <b>Movies</b>
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
                <th>Year</th>
                <th style="width:110px;" v-if="$gate.isAdmin()">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="movie in movies.data" :key="movie.id">
                <td>{{ movie.id }}</td>
                <td>{{ movie.name }}</td>
                <td>{{ movie.year }}</td>
                <td v-if="$gate.isAdmin()">
                  <button class="btn btn-outline-info btn-sm btn-flat" @click="editModal(movie)">
                    <i class="fas fa-edit"></i>
                  </button>
                  <button class="btn btn-outline-danger btn-sm btn-flat"  @click.prevent="deleteMovie(movie.id)">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          <pagination class="float-right" :data="movies" @pagination-change-page="getResults"></pagination>
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
            <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New Movie</h5>
            <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update Movie</h5>
          </div>
          <form @submit.prevent="editmode ? updateMovie() : createMovie()" enctype="multipart/form-data">
            <div class="modal-body">
              <div class="row">
                <div v-if="errors_exist" class="col-md-12 alert alert-danger" role="alert">
                  Whoops! Something didn't work.
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Banner:</label><span class="text-danger" v-if="errors_exist && errors.banner"> {{ errors.banner[0] }}</span>
                    <img v-if="previous_banner" width="100" :src="getBanner()">
                    <input type="file" v-bind:class="{ 'is-invalid': errors_exist && errors.banner }" class="form-control" v-on:change="onBannerChange">
                    <input type="hidden" class="form-control" v-model="previous_banner">
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label>Cover:</label><span class="text-danger" v-if="errors_exist && errors.cover"> {{ errors.cover[0] }}</span>
                    <img v-if="previous_cover" width="100" :src="getCover()">
                    <input type="file" v-bind:class="{ 'is-invalid': errors_exist && errors.cover }" class="form-control" v-on:change="onCoverChange">
                    <input type="hidden" class="form-control" v-model="previous_cover">

                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Name:</label><span class="text-danger" v-if="errors_exist && errors.name"> {{ errors.name[0] }}</span>
                    <input type="text" v-bind:class="{ 'is-invalid': errors_exist && errors.name }" class="form-control" v-model="movie.name">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Year:</label><span class="text-danger" v-if="errors_exist && errors.year"> {{ errors.year[0] }}</span>
                    <input type="text" v-bind:class="{ 'is-invalid': errors_exist && errors.year }" class="form-control" v-model="movie.year">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Vote:</label><span class="text-danger" v-if="errors_exist && errors.vote"> {{ errors.vote[0] }}</span>
                    <input type="text" v-bind:class="{ 'is-invalid': errors_exist && errors.vote }" class="form-control" v-model="movie.vote">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>View:</label><span class="text-danger" v-if="errors_exist && errors.view"> {{ errors.view[0] }}</span>
                    <input type="text" v-bind:class="{ 'is-invalid': errors_exist && errors.view }" class="form-control" v-model="movie.view">
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
        movie:{},
        page:'',
        movies: {},
        previous_banner: '',
        previous_cover: '',
      }
    },
    mounted() {
        // Fetch initial results
        this.getResults();

      },
      methods: {
        editModal(movie){
          this.editmode = true;
          this.movie = movie;
          this.previous_banner = this.movie.banner;
          this.previous_cover = this.movie.cover;
          this.errors = {};
          this.errors_exist = false;
          $('#createEditModal').modal('show');
        },
        createModal(){
          this.editmode = false;
          this.previous_banner = '';
          this.previous_cover = '';
          this.movie = {};
          this.movie.vote = 1;
          this.movie.view = 1;
          this.errors = {};
          this.errors_exist = false;
          $('#createEditModal').modal('show');
        },
        getResults(page = 1) {
          axios.get('api/v1/movies?page=' + page)
          .then(response => {
            this.movies = response.data;
            this.page = response.data.current_page;
          });
        },
        deleteMovie(id){
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
          let uri = `api/v1/movies/${id}`;
          this.axios.delete(uri).then(response => {
            this.getResults(this.page);
            this.afterDeleteAlert('success','Movie has been deleted.');
          });
        },
        createMovie(){
          let formData = new FormData();
          formData.append('banner', this.movie.banner);
          formData.append('cover', this.movie.cover);
          formData.append('name', this.movie.name);
          formData.append('year', this.movie.year);
          formData.append('vote', this.movie.vote);
          formData.append('view', this.movie.view);
          let uri = 'api/v1/movies';
          this.axios.post(uri, formData)
          .then((response) => {
            this.getResults(this.page);
            $('#createEditModal').modal('hide');
            this.myToast('success','Movie has been created.');
          })
          .catch(error => {
            this.errors = {};
            this.errors_exist = true;
            if(error.response.status === 422) {
              this.errors = error.response.data.errors || {};
            }
          });
        },
        updateMovie() {
          let formDatas = new FormData();
          formDatas.append('banner', this.movie.banner);
          formDatas.append('previous_banner', this.previous_banner);
          formDatas.append('cover', this.movie.cover);
          formDatas.append('previous_cover', this.previous_cover);
          formDatas.append('name', this.movie.name);
          formDatas.append('year', this.movie.year);
          formDatas.append('vote', this.movie.vote);
          formDatas.append('view', this.movie.view);
          formDatas.append("_method", "put");
          let uri = `api/v1/movies/${this.movie.id}`;
          this.axios.post(uri, formDatas).then((response) => {
            this.getResults(this.page);
            $('#createEditModal').modal('hide');
            this.myToast('success','Movie has been updated.');
          })
          .catch(error => {
            this.getResults(this.page);
            this.errors = {};
            this.errors_exist = true;
            if(error.response.status === 422) {
              this.errors = error.response.data.errors || {};
            }
          });
        },
        onBannerChange(e) {
          this.movie.banner = e.target.files[0];
        },
        onCoverChange(e) {
          this.movie.cover = e.target.files[0];
        },
        getBanner(){
          let banner = (this.movie.banner) ? "/images/banner/"+ this.previous_banner : "/images/banner/"+ this.movie.banner;
          return banner;
        },
        getCover(){
          let cover = (this.movie.cover) ? "/images/cover/"+ this.previous_cover : "/images/cover/"+ this.movie.cover;
          return cover;
        },
      },
    }
  </script>

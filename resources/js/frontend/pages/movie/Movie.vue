<template>
  <div class="container-fluid">
    <div class="row" v-if="!loading">
      <div class="col-12 col-md-6 col-sm-6 col-lg-6 col-xs-12">
       <div class="card card-default movie_card" id="bright" @click="rateMovie(movie1.id,movie2.id)">
        <img class="card-img-top h-100" :src="'/images/cover/' + movie1.cover" alt="Card image">
        <div class="card-img-overlay">
          <div class="card-body pt-0">
            <div class="row">
              <div class="col-7 text-center">
                <div style="background: rgba(0, 0, 0, 0.3);color: white;border: .3px solid black;  border-radius: 5px;">
                  <h2 class="lead"><b>{{movie1.name}}</b></h2>
                  <p>
                    <div class="container">
                      <div class="progress">
                        <div class="progress-bar progress-bar-striped" :class = "[barColor1]" role="progressbar" v-bind:style = "{ width: movie1rating +'%' }" v-bind:aria-valuenow = "movie1rating" aria-valuemin="0" aria-valuemax="100">{{movie1rating}} %</div>
                      </div>
                    </div>
                  </p>
                </div>
              </div>
              <div class="col-5 text-center">
                <img  :src="'/images/banner/' + movie1.banner" alt="" class="img-fluid rounded mx-auto d-block">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6 col-sm-6 col-lg-6 col-xs-12">
     <div class="card card-default movie_card" id="bright" @click="rateMovie(movie2.id,movie1.id)">
      <img class="card-img-top h-100" :src="'/images/cover/' + movie2.cover" alt="Card image">
      <div class="card-img-overlay">
        <div class="card-body pt-0">
          <div class="row">
            <div class="col-5 text-center">
              <img  :src="'/images/banner/' + movie2.banner" alt="" class="img-fluid rounded mx-auto d-block">
            </div>

            <div class="col-7 text-center">
              <div style="background: rgba(0, 0, 0, 0.3);color: white;border: .3px solid black;  border-radius: 5px;">
                <h2 class="lead"><b>{{movie2.name}}</b></h2>
                <p>
                  <div class="container">
                    <div class="progress">
                      <div class="progress-bar progress-bar-striped" :class = "[barColor2]" role="progressbar" v-bind:style = "{ width: movie2rating +'%' }" v-bind:aria-valuenow = "movie2rating" aria-valuemin="0" aria-valuemax="100">{{movie2rating}} %</div>
                    </div>
                  </div>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<button type="button" class="btn btn-outline-info btn-flat btn-lg btn-block" @click="skipMovies">SKIP</button>


</div>
</template>

<script>
  export default {
    data() {
      return {
        loading: true,
        movie1: {},
        movie2: {},
        rated: {},
        movie1rating:'',
        movie2rating:'',
        barColor1:'',
        barColor2:'',
      }
    },
    created() {
      this.getMovies();
    },
    mounted() {

    },
    methods:{
      getMovies() {
        axios.get('api/v1/ratemovies')
        .then(response => {
         this.movie1 = response.data[0];
         this.movie2 = response.data[1];
         this.loading = false
         this.movie1Rating();
         this.movie2Rating();
       });
      },
      rateMovie(rated,viewed) {
        this.rated.rated = rated;
        this.rated.viewed = viewed;
        let uri = `api/v1/ratemovies`;
        this.axios.post(uri, this.rated).then((response) => {
          this.skipMovies();
          this.myToast('success','Movie has been rated.');
        })
        .catch(error => {
          this.errors = {};
          this.errors_exist = true;
          if(error.response.status === 422) {
            this.errors = error.response.data.errors || {};
          }
          this.myToast('error',`Whoops! Something didn't work.`);
        });
      },
      skipMovies() {
        this.$Progress.start();
        this.getMovies();
        this.$Progress.finish();
      },
      movie1Rating () {
        let rating1 = (this.movie1.vote)/(this.movie1.view);
        this.movie1rating = Math.round(rating1*100);
        if (this.movie1rating <= 25) { this.barColor1 = 'bg-danger'; }
        if (this.movie1rating >= 25 && this.movie1rating < 50) { this.barColor1 = 'bg-warning'; }
        if (this.movie1rating >= 50 && this.movie1rating < 70 ) { this.barColor1 = 'bg-info'; }
        if (this.movie1rating >= 70 && this.movie1rating < 85 ) { this.barColor1 = 'bg-primary'; }
        if (this.movie1rating >= 85 && this.movie1rating <= 100 ) { this.barColor1 = 'bg-success'; }
      },
      movie2Rating () {
        let rating2 = (this.movie2.vote)/(this.movie2.view);
        this.movie2rating = Math.round(rating2*100);
        if (this.movie2rating <= 25) { this.barColor2 = 'bg-danger'; }
        if (this.movie2rating >= 25 && this.movie2rating < 50) { this.barColor2 = 'bg-warning'; }
        if (this.movie2rating >= 50 && this.movie2rating < 70 ) { this.barColor2 = 'bg-info'; }
        if (this.movie2rating >= 70 && this.movie2rating < 85 ) { this.barColor2 = 'bg-primary'; }
        if (this.movie2rating >= 85 && this.movie2rating <= 100 ) { this.barColor2 = 'bg-success'; }
      },
    },
    computed: {

    }
  }
</script>
<style scoped>

.movie_card {
  position: relative;
  display: block;
  overflow: hidden;
  border-radius: 10px;
  -webkit-transition: all 0.4s;
  transition: all 0.4s;
  height: 500px
}
.movie_card:hover {
  -webkit-transform: scale(1.02);
  transform: scale(1.02);
  -webkit-transition: all 0.4s;
  transition: all 0.4s;
}

.movie_card .blur_back {
  position: absolute;
  top: 0;
  z-index: 1;
  right: 0;
  background-size: cover;
  border-radius: 11px;
}

@media (max-width: 560.98px) {
  .movie_card {
    height: 260px
  }
}

/*// Small devices (landscape phones, 576px and up)*/
@media (min-width: 576px) {

}

/*// Medium devices (tablets, 768px and up)*/
@media (min-width: 768px) {
  .blur_back {
    background-position: -100% 10% !important;
  }
}

/*Large devices (desktops, 992px and up)*/
@media (min-width: 992px) {  }

/*Extra large devices (large desktops, 1200px and up)*/
@media (min-width: 1200px) {  }


#bright {
  box-shadow: 0px 0px 150px -45px rgba(255, 51, 0, 0.5);
}
#bright:hover {
  box-shadow: 0px 0px 120px -55px rgba(255, 51, 0, 0.5);
}


</style>

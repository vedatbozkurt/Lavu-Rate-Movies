/*
* @Author: @vedatbozkurt
* @Date:   2020-03-26 19:57:07
* @Last Modified by:   @vedatbozkurt
* @Last Modified time: 2020-04-18 17:22:12
*/
import VueRouter from 'vue-router'

// Pages
import NotFound from './pages/NotFound'
import Movie from './pages/movie/Movie.vue'

// Routes
const routes = [
  {
    path: '/',
    name: 'movie',
    component: Movie,
  },
  {
    path: '*',
    name: 'notfound',
    component: NotFound,
  },
  ]
  const router = new VueRouter({
    history: true,
    mode: 'hash',
    routes,
  })
  export default router

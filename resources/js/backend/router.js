/*
* @Author: @vedatbozkurt
* @Date:   2020-03-26 19:57:07
* @Last Modified by:   @vedatbozkurt
* @Last Modified time: 2020-04-18 16:11:22
*/
import VueRouter from 'vue-router'

// Pages
import NotFound from './pages/NotFound'
import Dashboard from './pages/admin/Dashboard'
import Developer from './pages/developer/Developer.vue'
import Users from './pages/user/User.vue'
import Profile from './pages/user/Profile.vue'
import Backup from './pages/backup/Backup.vue'
import Log from './pages/log/Log.vue'
import Role from './pages/rolepermission/Role.vue'
import Permission from './pages/rolepermission/Permission.vue'
import Movie from './pages/movie/Movie.vue'

// Routes
const routes = [
  {
    path: '/',
    name: 'dashboard',
    component: Dashboard,
  },
  {
    path: '/users',
    name: 'users',
    component: Users,
  },
  {
    path: '/profile',
    name: 'profile',
    component: Profile,
  },
  {
    path: '*',
    name: 'notfound',
    component: NotFound,
  },
  {
    path: '/developer',
    name: 'developer',
    component: Developer,
  },
  {
    path: '/backup',
    name: 'backup',
    component: Backup,
  },
  {
    path: '/log',
    name: 'log',
    component: Log,
  },
  {
    path: '/role',
    name: 'role',
    component: Role,
  },
  {
    path: '/permission',
    name: 'permission',
    component: Permission,
  },
  {
    path: '/movie',
    name: 'movie',
    component: Movie,
  },
  ]
  const router = new VueRouter({
    history: true,
    mode: 'hash',
    routes,
  })
  export default router

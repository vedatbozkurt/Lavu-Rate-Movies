     <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="profile.png" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <a href="#" class="d-block">{{Auth::user()->name}}</a>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column nav-compact nav-legacy nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
           with font-awesome or any other icon font library -->



           <li class="nav-item">
            <router-link to="/" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt blue"></i>
              <p>
                Dashboard
              </p>
            </router-link>
          </li>

          <li class="nav-item">
            <router-link :to="{ name: 'users' }" class="nav-link">
              <i class="fas fa-users nav-icon teal"></i>
              <p>
                Users
              </p>
            </router-link>
          </li>


          <li class="nav-item">
            <router-link :to="{ name: 'movie' }" class="nav-link">
              <i class="fas fa-video nav-icon purple"></i>
              <p>
                Movies
              </p>
            </router-link>
          </li>

          <li class="nav-item">
            <router-link :to="{ name: 'profile' }"  class="nav-link">
              <i class="nav-icon fas fa-user orange"></i>
              <p>
                Profile
              </p>
            </router-link>
          </li>


          <li class="nav-item">
            <a class="nav-link" href="{{ route('logout') }}"
            onclick="event.preventDefault();
            document.getElementById('logout-form').submit();">
            <i class="nav-icon fa fa-power-off red"></i>
            <p>
              {{ __('Logout') }}
            </p>
          </a>

          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
           @csrf
         </form>
       </li>
       @role('Admin')
       <li class="nav-header">MISCELLANEOUS</li>
       <li class="nav-item">
        <a href="https://wedat.org" target="_blank" class="nav-link">
          <i class="nav-icon fas fa-file"></i>
          <p>Documentation</p>
        </a>
      </li>
      <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-edit yellow"></i>
          <p>
            Roles & Permmissions
            <i class="fas fa-angle-left right"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <router-link :to="{ name: 'role' }" class="nav-link">
              <i class="fas fa-chevron-right nav-icon"></i>
              <p>Roles</p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link :to="{ name: 'permission' }" class="nav-link">
              <i class="fas fa-chevron-right nav-icon"></i>
              <p>Permissions</p>
            </router-link>
          </li>
        </ul>
      </li>
      <li class="nav-item">
        <router-link to="/developer" class="nav-link">
          <i class="nav-icon fas fa-cogs blue"></i>
          <p>
            Developer
          </p>
        </router-link>
      </li>

      <li class="nav-item">
        <router-link to="/backup" class="nav-link">
          <i class="nav-icon fas fa-database cyan"></i>
          <p>
            Backup
          </p>
        </router-link>
      </li>

      <li class="nav-item">
        <router-link to="/log" class="nav-link">
          <i class="nav-icon fas fa-list-ul orange"></i>
          <p>
            Logs
          </p>
        </router-link>
      </li>
      @endrole
    </ul>
  </nav>
  <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
</aside>

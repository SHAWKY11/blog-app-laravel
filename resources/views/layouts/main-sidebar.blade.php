  <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->


      <!-- Sidebar -->
      <div class="sidebar">
          <!-- Sidebar user panel (optional) -->
          <div class="user-panel mt-3 pb-3 mb-3 d-flex">
              <div class="image">
                  <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
              </div>
              <div class="info">
                  <a href="#" class="d-block">{{ Auth::user()->name }}</a>
              </div>

          </div>
          <div class="form-inline mt-3 pb-3 mb-2 d-flex">
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
              </form>
              <button type="button" class="btn btn-block btn-outline-danger"
                  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log Out</button>
          </div>

          <!-- SidebarSearch Form -->
          <div class="form-inline">
              <div class="input-group" data-widget="sidebar-search">
                  <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                      aria-label="Search">
                  <div class="input-group-append">
                      <button class="btn btn-sidebar">
                          <i class="fas fa-search fa-fw"></i>
                      </button>
                  </div>
              </div>
          </div>

          <!-- Sidebar Menu -->
          <nav class="mt-2">
              <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                  data-accordion="false">
                  <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                  <li class="nav-item">
                      <a href="{{ route('dashboard') }}" class="nav-link">
                          <i class="nav-icon fas fa-tachometer-alt"></i>
                          <p>
                              Dashboard
                          </p>
                      </a>

                  </li>
                  <li class="nav-item">
                      <a href="{{ route('categories.index') }}" class="nav-link">
                          <i class="nav-icon fas fa-list"></i>
                          <p>
                              Categories
                          </p>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="{{ route('comments.index') }}" class="nav-link">
                          <i class="nav-icon fas fa-comments"></i>
                          <p>
                              Comments
                          </p>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="{{ route('tags.index') }}" class="nav-link">
                          <i class="nav-icon fas fa-tag"></i>
                          <p>
                              Tags
                          </p>
                      </a>

                  </li>
                  <li class="nav-item">
                      <a href="{{ route('posts.index') }}" class="nav-link">
                          <i class="nav-icon fas fa-wifi"></i>
                          <p>
                              Posts
                          </p>
                      </a>

                  </li>
                  <li class="nav-item">
                      <a href="{{ route('media.index') }}" class="nav-link">
                          <i class="nav-icon fas fa-film"></i>
                          <p>
                              Media
                          </p>
                      </a>
                  </li>
                  @permission('users-read')
                      <li class="nav-item">
                          <a href="{{ route('admins.index') }}" class="nav-link">
                              <i class="nav-icon fas fa-users"></i>
                              <p>
                                  Admins
                              </p>
                          </a>
                      </li>
                  @endpermission
              </ul>
          </nav>
          <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
  </aside>

  <!-- begin app-nabar -->
  <aside class="app-navbar">
      <!-- begin sidebar-nav -->
      <div class="sidebar-nav scrollbar scroll_light">
          <ul class="metismenu " id="sidebarNav">

              <li><a class="@if(request()->is('admin/dashboard') || request()->is('admin/dashboard/*')) active @endif" href="{{route('admin.dashboard')}}" aria-expanded="false"><i class="nav-icon ti ti-comment"></i><span class="nav-title">Dashboard</span></a> </li>
              <li><a class="@if(request()->is('admin/doctors/index') || request()->is('admin/doctors/*')) active @endif" href="{{route('admin.doctors.index')}}" aria-expanded="false"><i class="nav-icon ti ti-comment"></i><span class="nav-title">Mantors</span></a> </li>

              <li><a class="@if(request()->is('admin/doctors/mentor_request') || request()->is('admin/doctors/*')) active @endif" href="{{route('admin.doctors.mentor_request')}}" aria-expanded="false"><i class="nav-icon ti ti-comment"></i><span class="nav-title">Mantors Request</span></a> </li>


              <li><a class="@if(request()->is('admin/patients/index') || request()->is('admin/patients/*')) active @endif" href="{{route('admin.patients.index')}}" aria-expanded="false"><i class="nav-icon ti ti-comment"></i><span class="nav-title">Particitants</span></a> </li>

              <li><a class="has-arrow active" href="javascript:void(0)" aria-expanded="false"><i class="nav-icon ti ti-calendar"></i><span class="nav-title">Faqs</span></a>
                  <ul aria-expanded="false">
                      <li> <a href='{{route("admin.faqs.create")}}'>Add</a> </li>
                      <li> <a href='{{route("admin.faqs.index")}}'>List</a> </li>
                  </ul>
              </li>
              <!-- <li><a href="mail-inbox.html" aria-expanded="false"><i class="nav-icon ti ti-email"></i><span class="nav-title">Mail</span></a> </li> -->


              <!-- <li>
                  <a class="has-arrow" href="javascript:void(0)" aria-expanded="false"><i class="nav-icon ti ti-layers"></i><span class="nav-title">Pages</span><span class="nav-label label label-primary">12</span></a>
                  <ul aria-expanded="false">
                      <li> <a href="page-account-settings.html">Account Settings</a> </li>
                      <li> <a href="page-clients.html">Clients</a> </li>
                      <li> <a href="page-contacts.html">Contacts</a> </li>
                      <li> <a href="page-employees.html">Employees</a> </li>
                      <li> <a href="page-faq.html">FAQ</a> </li>
                      <li> <a href="page-file-manager.html">File Manager</a> </li>
                      <li> <a href="page-gallery.html">Gallery</a> </li>

                      <li> <a href="page-pricing.html">Pricing</a> </li>
                      <li> <a href="page-task-list.html">Task List</a> </li>
                      <li> <a href="page-404.html">404</a> </li>
                      <li> <a href="page-500.html">500</a> </li>
                      <li> <a href="page-coming-soon.html">Coming Soon</a> </li>
                  </ul>
              </li> -->
              <!-- <li>
                  <a class="has-arrow" href="javascript:void(0)" aria-expanded="false"><i class="nav-icon ti ti-key"></i><span class="nav-title">Auth</span></a>
                  <ul aria-expanded="false">
                      <li> <a href="auth-login.html">Login</a> </li>
                      <li> <a href="auth-register.html">Register</a> </li>
                      <li> <a href="auth-lockscreen.html">Lock Screen</a> </li>
                  </ul>
              </li> -->
              <!-- <li>
                  <a class="has-arrow" href="javascript:void(0)" aria-expanded="false"><i class="nav-icon ti ti-list"></i><span class="nav-title">Multi Level</span></a>
                  <ul aria-expanded="false">
                      <li> <a href="javascript: void(0);">Level 1.1</a> </li>
                      <li class="scoop-hasmenu">
                          <a class="has-arrow" href="javascript: void(0);">Level 1.2</a>
                          <ul aria-expanded="false">
                              <li> <a href="javascript: void(0);">Level 2.1</a> </li>
                              <li> <a href="javascript: void(0);">Level 2.2</a> </li>
                          </ul>
                      </li>
                  </ul>
              </li> -->

          </ul>
      </div>
      <!-- end sidebar-nav -->
  </aside>
  <!-- end app-navbar -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
      <a href="{{url('/remedial')}}" class="app-brand-link">
        <span class="app-brand-logo demo"></span>
        <span class="app-brand-text demo menu-text fw-bolder ms-2">Remedial</span>
      </a>
      <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
        <i class="bx bx-chevron-left bx-sm align-middle"></i>
      </a>
    </div>
    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">
      <!-- Dashboard -->
      <li class="menu-item active">
        <a href="{{route('attendance.index')}}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-home-circle"></i>
          <div data-i18n="Analytics">Dashboard</div>
        </a>
      </li>

     @can('admin')
     <li class="menu-item ">
      <a href="{{route('attendance.create')}}" class="menu-link">
        {{-- <i class="menu-icon tf-icons bx bx-home-circle"></i> --}}
        <i class='menu-icon tf-icons bx bx-plus-medical'></i>
        <div data-i18n="Analytics">New Attendance</div>
      </a>
    </li>
     @endcan

     <li class="menu-item ">
      <a href="{{route('user.attendance')}}" class="menu-link">
        {{-- <i class="menu-icon tf-icons bx bx-home-circle"></i> --}}
        <i class='menu-icon tf-icons bx bxs-user-detail'></i>
        <div data-i18n="Analytics">My Lessons</div>
      </a>
    </li>

    @can('admin')
      <li class="menu-item ">
        <a href="{{route('attendance.index')}}" class="menu-link">
          {{-- <i class="menu-icon tf-icons bx bx-home-circle"></i> --}}
          <i class='menu-icon tf-icons bx bx-label'></i>
          <div data-i18n="Analytics">View All</div>
        </a>
      </li>

     

    
     <li class="menu-item ">
      <a href="{{route('users.index')}}" class="menu-link">
        {{-- <i class="menu-icon tf-icons bx bx-home-circle"></i> --}}
        <i class='menu-icon tf-icons bx bx-group'></i>
        <div data-i18n="Analytics">Teachers</div>
      </a>
    </li>
     @endcan

     
      <li class="menu-item ">
        <a href="{{route('comment.index')}}" class="menu-link">
          {{-- <i class="menu-icon tf-icons bx bx-home-circle"></i> --}}
          <i class='menu-icon tf-icons bx bxs-book-open'></i>
          <div data-i18n="Analytics">Notices</div>
        </a>
      </li>

    

     
     
      
    </ul>
  </aside>
  
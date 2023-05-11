@include('remedial.partials.head')

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->
    @include('remedial.partials.sidebar')

      
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->
          @include('remedial.partials.navigation')


          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">


              <div class="row">
                
              @yield('content')
              
              </div>
             
            </div>

          </div>
            <!-- / Content -->

    @include('remedial.partials.footer')
           
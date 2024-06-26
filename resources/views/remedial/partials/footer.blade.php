<!-- Footer -->
<footer class="content-footer footer bg-footer-theme">
  <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
    <div class="mb-2 mb-md-0">
      ©
      <script>
        document.write(new Date().getFullYear());
      </script>

      <a href="#" target="_blank" class="footer-link fw-bolder">Jamadata </a>
    </div>

  </div>
</footer>
<!-- / Footer -->

<div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->
</div>
<!-- / Layout page -->
</div>

<!-- Overlay -->
<div class="layout-overlay layout-menu-toggle"></div>
</div>
<!-- / Layout wrapper -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->
<script src="{{ asset('remedialsystem/assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('remedialsystem/assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('remedialsystem/assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('remedialsystem/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

<script src="{{ asset('remedialsystem/assets/vendor/js/menu.js') }}"></script>
<!-- endbuild -->

<!-- Vendors JS -->
<script src="{{ asset('remedialsystem/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

<!-- Main JS -->
<script src="{{ asset('remedialsystem/assets/js/main.js') }}"></script>

<!-- Page JS -->
<script src="{{ asset('remedialsystem/assets/js/dashboards-analytics.js') }}"></script>



<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
@stack('scripts')

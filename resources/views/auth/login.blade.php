

<!DOCTYPE html>


<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Login </title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{asset('remedialsystem/assets/vendor/fonts/boxicons.css')}}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{asset('remedialsystem/assets/vendor/css/core.css')}}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{asset('remedialsystem/assets/vendor/css/theme-default.css')}}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{asset('remedialsystem/assets/css/demo.css')}}" />

   <!-- Vendors CSS -->
<link rel="stylesheet" href="{{ asset('remedialsystem/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

<!-- Page CSS -->
<!-- Page -->
<link rel="stylesheet" href="{{ asset('remedialsystem/assets/vendor/css/pages/page-auth.css') }}" />
<!-- Helpers -->
<script src="{{ asset('remedialsystem/assets/vendor/js/helpers.js') }}"></script>


<script src="{{ asset('remedialsystem/assets/js/config.js') }}"></script>
  </head>

  <body>
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register -->
          <div class="card">
            <div class="card-body">



                <form id="formAuthentication" class="mb-3" action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="login" class="form-label"> Phone Number</label>
                        <input type="text" class="form-control" id="login" value="{{ old('login') }}" name="login" placeholder="Enter your phone number" autofocus />

                        @if ($errors->has('login'))
                            <div class="alert alert-danger mt-2" role="alert">
                                {{ $errors->first('login') }}
                            </div>
                        @endif
                    </div>

                    <div class="mb-3 form-password-toggle">
                        <div class="d-flex justify-content-between">
                            <label class="form-label" for="password">Password<span style="color: green; margin-left: 5px;">*</span></label>
                        </div>

                        <div class="input-group input-group-merge">
                            <input type="password" id="password" value="{{ old('password') }}" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />

                            <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                        </div>

                        <small style="color: green;">Use your phone number as the password</small>

                        @if ($errors->has('password'))
                            <div class="alert alert-danger mt-2" role="alert">
                                {{ $errors->first('password') }}
                            </div>
                        @endif
                    </div>


                    <div class="mb-3">
                        <div class="form-check">
                            @php
                                $rememberChecked = isset($_COOKIE['remember_me']) ? 'checked' : '';
                            @endphp
                            <input class="form-check-input" type="checkbox" value="1" name="remember" id="remember" {{ $rememberChecked }} />
                            <label class="form-check-label" for="remember"> Remember Me </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <button class="btn btn-sm btn-primary d-grid w-100" type="submit">Log in</button>
                    </div>
                </form>
                <!-- Bukua -->
                <div class="text-center">
                  <p>Or</p>
                  @if (Route::has('bukua-auth.authorize'))
                    <form action="{{ route('bukua-auth.authorize') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-secondary d-grid w-100">Login with Bukua</button>
                    </form>
                  @endif
                </div>
              <!-- /Bukua -->
            </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>

    <!-- / Content -->



    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('remedialsystem/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('remedialsystem/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('remedialsystem/assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('remedialsystem/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('remedialsystem/assets/vendor/js/menu.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('remedialsystem/assets/js/main.js') }}"></script>


    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>


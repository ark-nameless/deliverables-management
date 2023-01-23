<?php

  include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/guard.core.php';

  Guard::intercept(false);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="assets/images/favicon.svg" type="image/x-icon" />
  <title>Faculty Deliverables</title>
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- ========== All CSS files linkup ========= -->
  <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="assets/css/lineicons.css" />
  <link rel="stylesheet" href="assets/css/materialdesignicons.min.css" />
  <link rel="stylesheet" href="assets/css/fullcalendar.css" />
  <link rel="stylesheet" href="assets/css/main.css" />


  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.13.1/b-2.3.3/b-colvis-2.3.3/b-print-2.3.3/cr-1.6.1/fc-4.2.1/r-2.4.0/rr-1.3.1/datatables.min.css" />
  <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.13.1/b-2.3.3/b-colvis-2.3.3/b-print-2.3.3/cr-1.6.1/fc-4.2.1/r-2.4.0/rr-1.3.1/datatables.min.css"/> -->
  <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.13.1/datatables.min.css"/> -->
</head>

<body>

  <!-- ======== main-wrapper start =========== -->
  <main class="flex items-center justify-center w-screen h-screen">
    <!-- ========== signin-section start ========== -->
    <section class="signin-section">
      <div class="container-fluid">
        <div class="row g-0 auth-row">
          <div class="hidden lg:flex col-lg-6">
            <div class="auth-cover-wrapper bg-primary-100">
              <div class="auth-cover">
                <div class="title text-center">
                  <h1 class="text-primary mb-10">Welcome Back</h1>
                  <p class="text-medium">
                    Sign in to your Existing account to continue
                  </p>
                </div>
                <div class="cover-image">
                  <img src="assets/images/auth/signin-image.svg" alt="" />
                </div>
                <div class="shape-image">
                  <img src="assets/images/auth/shape.svg" alt="" />
                </div>
              </div>
            </div>
          </div>
          <!-- end col -->
          <div class="col-lg-6">
            <div class="signin-wrapper">
              <div class="form-wrapper">
                <h6 class="mb-15 text-5xl font-semibold text-red-900">Login</h6>
                <p class="text-sm mb-25">
                </p>
                <form action="scripts/routes/login.route.php" method='post'>
                  <div class="row">
                    <div class="col-12">
                      <div class="input-style-1">
                        <label>Email</label>
                        <input type="email" placeholder="Email" name="email" required/>
                      </div>
                    </div>
                    <!-- end col -->
                    <div class="col-12">
                      <div class="input-style-1">
                        <label>Password</label>
                        <input type="password" placeholder="Password" name="password" required/>
                      </div>
                    </div>
                    <div class="">
                      <div class=" text-start text-md-end text-lg-start text-xxl-end mb-30">
                        <!-- <a href="#0" class="hover-underline hover:font-medium hover:text-red-600 text-red-900">Forgot Password?</a> -->
                      </div>
                    </div>
                    <!-- end col -->
                    <div class="col-12">
                      <div class=" button-group d-flex justify-content-center flex-wrap">
                        <button class=" main-btn bg-red-900  btn-hover w-100 text-center text-white">
                          Login
                        </button>
                      </div>
                    </div>
                  </div>
                  <!-- end row -->
                </form>
              </div>
            </div>
          </div>
          <!-- end col -->
        </div>
        <!-- end row -->
      </div>
    </section>
    <!-- ========== signin-section end ========== -->

    <!-- ========== footer start =========== -->
    <footer class="footer">
    </footer>
    <!-- ========== footer end =========== -->
  </main>
  <!-- ======== main-wrapper end =========== -->

  <!-- ========= All Javascript files linkup ======== -->
  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/Chart.min.js"></script>
  <script src="assets/js/dynamic-pie-chart.js"></script>
  <script src="assets/js/moment.min.js"></script>
  <script src="assets/js/fullcalendar.js"></script>
  <script src="assets/js/jvectormap.min.js"></script>
  <script src="assets/js/world-merc.js"></script>
  <script src="assets/js/polyfill.js"></script>
  <script src="assets/js/main.js"></script>

  <!-- <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.13.1/b-2.3.3/b-colvis-2.3.3/b-print-2.3.3/cr-1.6.1/fc-4.2.1/r-2.4.0/datatables.min.js"></script> -->

  <!-- <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.13.1/b-2.3.3/b-colvis-2.3.3/b-print-2.3.3/cr-1.6.1/fc-4.2.1/r-2.4.0/rr-1.3.1/datatables.min.js"></script> -->
  <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.13.1/b-2.3.3/b-colvis-2.3.3/b-print-2.3.3/cr-1.6.1/fc-4.2.1/r-2.4.0/rr-1.3.1/datatables.min.js"></script>

  <script src="global/tables.config.js"></script>
</body>

</html>
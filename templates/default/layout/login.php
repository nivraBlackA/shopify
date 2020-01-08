
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="BAExpress Admin">
  <meta name="author" content="Cedrick Dayangco">
  <title>BAExpress | Login</title>
  <!-- Favicon -->
  <link href="<?=base_url()?>assets/images/ico/ms-icon-144x144.png" rel="icon" type="image/png">
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Quicksand:300,400,500,700" rel="stylesheet">
  <!-- Icons -->
  <link href="<?=$theme_dir?>vendor/nucleo/css/nucleo.css" rel="stylesheet">
  <link href="<?=$theme_dir?>vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
  <!-- Argon CSS -->
  <link type="text/css" href="<?=$theme_dir?>css/argon.css?v=1.0.0" rel="stylesheet">
  <style>
    .animate {
    animation: bounce 1s infinite alternate;
    -webkit-animation: bounce 1s infinite alternate;
    }
    @keyframes bounce {
      from {
        transform: translateY(0px);
      }
      to {
        transform: translateY(-15px);
      }
    }
    @-webkit-keyframes bounce {
      from {
        transform: translateY(0px);
      }
      to {
        transform: translateY(-15px);
      }
    }
  </style>
</head>

<body class="bg-default">
  <div class="main-content">
    <!-- Header -->
    <div class="header bg-gradient-danger py-5 py-lg-5">
      <div class="container">
        <div class="header-body text-center mb-2">
          <div class="row justify-content-center">
            <div class="col-lg-5 col-md-6">
              <img src="<?=base_url()?>assets/images/rider.png" class="img img-fluid mt--7 animate">
              <!-- <p class="text-lead text-light">Use these awesome forms to login or create new account in your project for free.</p> -->
            </div>
          </div>
        </div>
      </div>
      <div class="separator separator-bottom separator-skew zindex-100">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
          <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
        </svg>
      </div>
    </div>
    <!-- Page content -->
    <div class="container mt--8 pb-5">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
          <div class="card bg-secondary shadow border-0">
            <div class="card-body px-lg-5 py-lg-5">
              <h4 class="text-center">Sign In</h4>
              <?php
                $attr   = array('autocomplete'=>'off',"id" => "login_form");
                echo form_open('login/verify',$attr); 
              ?>
                <div class="form-group mb-3">
                  <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                    </div>
                    <input class="form-control" placeholder="Email" type="email" id="username" name="username">
                  </div>
                </div>
                <div class="form-group">
                  <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                    </div>
                    <input class="form-control" placeholder="Password" type="password" id="password" name="password">
                  </div>
                </div>
                <div class="custom-control custom-control-alternative custom-checkbox">
                  <input class="custom-control-input" id="customCheckLogin" type="checkbox">
                  <label class="custom-control-label" for="customCheckLogin">
                    <span class="text-muted">Remember me</span>
                  </label>
                </div>
                <div class="text-center">
                  <button type="submit" class="btn btn-danger my-4">Sign in</button>
                </div>
              </form>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-6">
              <a href="#" class="text-light"><small>Forgot password?</small></a>
            </div>
            <div class="col-6 text-right">
              <!-- <a href="#" class="text-light"><small>Create new account</small></a> -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Footer -->
  <!-- <footer class="py-5">
    <div class="container">
      <div class="row align-items-center justify-content-xl-between">
        <div class="col-xl-6">
          <div class="copyright text-center text-xl-left text-muted">
            &copy; 2018 <a href="https://www.creative-tim.com" class="font-weight-bold ml-1" target="_blank">Creative Tim</a>
          </div>
        </div>
        <div class="col-xl-6">
          <ul class="nav nav-footer justify-content-center justify-content-xl-end">
            <li class="nav-item">
              <a href="https://www.creative-tim.com" class="nav-link" target="_blank">Creative Tim</a>
            </li>
            <li class="nav-item">
              <a href="https://www.creative-tim.com/presentation" class="nav-link" target="_blank">About Us</a>
            </li>
            <li class="nav-item">
              <a href="http://blog.creative-tim.com" class="nav-link" target="_blank">Blog</a>
            </li>
            <li class="nav-item">
              <a href="https://github.com/creativetimofficial/argon-dashboard/blob/master/LICENSE.md" class="nav-link" target="_blank">MIT License</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </footer> -->
  <!-- Argon Scripts -->
  <!-- Core -->
  <script src="<?=$theme_dir?>/vendor/jquery/dist/jquery.min.js"></script>
  <script src="<?=$theme_dir?>/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Argon JS -->
  <script src="<?=$theme_dir?>/js/argon.js?v=1.0.0"></script>
  <script>
    $(function() {
      checkSave();
      $("#login_form").on("submit",function(){
        if ($('#customCheckLogin').is(':checked')) {
            // save username and password
            localStorage.userName = $('#username').val();
            localStorage.password = $('#password').val();
            localStorage.checkBoxValidation = $('#customCheckLogin').val();
        } else {
            localStorage.userName = '';
            localStorage.password = '';
            localStorage.checkBoxValidation = '';
        }

      });

      function checkSave(){
        $("#username").val(localStorage.userName);
        $("#password").val(localStorage.password);
        $("#customCheckLogin").attr("checked",localStorage.password);
      }
    })
  </script>
</body>

</html>
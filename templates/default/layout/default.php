<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="BAExpress Admin">
  <meta name="author" content="Cedrick Dayangco">
  <title>BAE | Shopify</title>

  <!-- Favicons -->
  <!-- <link href="img/favicon.png" rel="icon"> -->
  <link href="<?=base_url()?>assets/images/ico/ms-icon-144x144.png" rel="icon" type="image/png">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Quicksand:300,400,500,700" rel="stylesheet">

  <!-- Bootstrap CSS File -->

  <!-- Icons -->
  <link href="<?=$theme_dir?>vendor/nucleo/css/nucleo.css" rel="stylesheet">
  <link href="<?=$theme_dir?>/vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="<?=$theme_dir?>/vendor/jquery.scrollbar/jquery.scrollbar.css" rel="stylesheet">
  <link href="<?=$theme_dir?>/vendor/datatables/DataTable/css/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="<?=$theme_dir?>/vendor/dropzone/dropzone.css" rel="stylesheet">
  <link href="<?=$theme_dir?>vendor/leaflet/leaflet.css" rel="stylesheet">
  
  <!-- Argon CSS -->
  <link type="text/css" href="<?=$theme_dir?>css/argon.css?<?=filemtime($theme_path."css/argon.css")?>" rel="stylesheet">
  <link type="text/css" href="<?=$theme_dir?>css/baexpress.css?<?=filemtime($theme_path."css/baexpress.css")?>" rel="stylesheet">
  <link type="text/css" href="<?=$theme_dir?>css/animate.css?<?=filemtime($theme_path."css/animate.css")?>" rel="stylesheet">
  <!-- Core -->
  <?=(isset($google_js)) ? $google_js : ""?>
  <script src="<?=$theme_dir?>vendor/jquery/dist/jquery.min.js"></script>
  <script src="<?=$theme_dir?>vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?=$theme_dir?>vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
  <script src="<?=$theme_dir?>vendor/datatables/datatables.min.js"></script>
  <script src="<?=$theme_dir?>vendor/datatables/DataTable/js/dataTables.bootstrap4.min.js"></script>
  <script src="<?=$theme_dir?>vendor/dropzone/dropzone.js"></script>
  <script src="<?=$theme_dir?>vendor/moment/moment.min.js"></script>
  <script src="<?=$theme_dir?>vendor/leaflet/leaflet.js"></script>
  
  <script src="<?=$theme_dir?>js/all.js?<?=filemtime($theme_path."js/all.js")?>"></script>
  <!-- Plugins CSS -->
  <?php

    /*LOAD INCLUDE JS*/
    if(isset($ci_css)) {
      foreach ($ci_css as  $filename) {
        echo '<link href="'.$theme_dir.$filename.'?'.filemtime($theme_path.$filename) .'" type="text/css" rel="stylesheet">';
      }
    }
  ?>
  <script src="<?=$theme_dir?>js/bodymovin.js"></script>
  <script src="<?=$theme_dir?>js/bindWithDelay.js"></script>
</head>
<body>

    <?=isset($sidebar)?$sidebar:""; ?>
    
    <div class="main-content">
      <?=isset($header)?$header:""; ?>
      
      <?=isset($content)?$content:""; ?>

      <?=isset($footer)?$footer:""; ?>
    </div>


  <!-- Argon Scripts -->

  <!-- Optional JS -->
  <script src="<?=$theme_dir?>vendor/chart.js/dist/Chart.min.js"></script>
  <!-- Rounded Bar Graph -->
  <!-- <script src="<?=$theme_dir?>vendor/chart.js/dist/Chart.extension.js"></script> -->
  <!-- Argon JS -->
  <script src="<?=$theme_dir?>js/argon.js?v=1.0.0"></script>
  <script src="<?=$theme_dir?>js/bodymovin.js"></script>
  <script src="<?=$theme_dir?>js/pages/main.js?<?=filemtime($theme_path."js/pages/main.js")?>"></script>
  <input type="hidden" id="base_url" value="<?=base_url()?>"/>
    <script>
        base_url = "<?=base_url()?>";
        Dropzone.autoDiscover = false;
        $("#menu-toggle").click(function (e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });
    </script>

    <?php

    /*LOAD INCLUDE JS*/
    if(isset($ci_js)) {
      foreach ($ci_js as  $filename) {
        echo '<script src="'.$theme_dir.$filename.'?'.filemtime($theme_path.$filename) .'" type="text/javascript"></script>';
      }
    }
    if(isset($common_js)) {
      foreach ($common_js as  $filename) {
        echo '<script src="'.$theme_dir.$filename.'?'. filemtime($theme_path.$filename) .'" type="text/javascript"></script>';
      }
    }
    
    ?>
    <?=(isset($extra_js) ? $extra_js : "")?>
    
    
</body>

</html>

<!-- Sidenav -->
<nav class="navbar navbar-vertical fixed-left navbar-expand-lg navbar-light bg-white" id="sidenav-main">
  <!-- <div> -->
  <!-- <div class="scrollbar-inner p-1"> -->
    
    <div class="container-fluid">
        <a class="navbar-brand pt-0" href="./index.html">
        <img src="<?=base_url()?>assets/images/bae_logo_long.png" class="navbar-brand-img" alt="...">
      </a>
  
      <!-- Toggler -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <!-- Brand -->

      <!-- Collapse -->
      <div class="collapse navbar-collapse" id="sidenav-collapse-main">
        <!-- Collapse header -->
        <div class="navbar-collapse-header d-md-none">
          <div class="row">
            <div class="col-6 collapse-brand">
              <a href="./index.html">
                <img src="<?=base_url()?>assets/images/appicon.png">
              </a>
            </div>
            <div class="col-6 collapse-close">
              <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                <span></span>
                <span></span>
              </button>
            </div>
          </div>
        </div>
        <!-- Form -->
        <form class="mt-4 mb-3 d-md-none">
          <div class="input-group input-group-rounded input-group-merge">
            <input type="search" class="form-control form-control-rounded form-control-prepended" placeholder="Search" aria-label="Search">
            <div class="input-group-prepend">
              <div class="input-group-text">
                <span class="fa fa-search"></span>
              </div>
            </div>
          </div>
        </form>
        <!-- Navigation -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="<?=base_url()?>">
              <i class="fas fa-tachometer-alt text-primary"></i> Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?=base_url("orders")?>">
              <i class="fas fa-shopping-cart text-primary"></i> Shopify Orders
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?=base_url("users")?>">
              <i class="fas fa-users text-yellow"></i> Users
            </a>
          </li>
          
        </ul>
        </ul>
      </div>
    
    </div>

    
  </nav>



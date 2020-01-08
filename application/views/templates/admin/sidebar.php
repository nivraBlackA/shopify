<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-tgreen sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?=base_url("admin")?>">
        <div class="sidebar-brand-icon">
            <!-- <i class="fas fa-laugh-wink"></i> -->
            <img class="img img-fluid" src="<?=base_url($site_setting->website_logo)?>" />
        </div>
        <div class="sidebar-brand-text mx-3"></div>
    </a>
    <!-- <p class="text-center text-light pr-2 pl-2">INSUREX</p> -->

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="<?=base_url("admin")?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Interface
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true"
            aria-controls="collapseTwo">
            <i class="fas fa-fw fa-cogs"></i>
            <span>Maintenance</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!-- <h6 class="collapse-header">Custom Components:</h6> -->
                <!-- <a class="collapse-item" href="<?=base_url("admin/maintenance/aog")?>">Act of God Rates</a> -->
                <a class="collapse-item" href="<?=base_url("admin/maintenance/vehicle_masterlist")?>">Vehicle Masterlist</a>
                <a class="collapse-item" href="<?=base_url("admin/maintenance/bodily_injury")?>">Bodily Injury Rates</a>
                <a class="collapse-item" href="<?=base_url("admin/maintenance/property_damage")?>">Property Damage Rates</a>
                <a class="collapse-item" href="<?=base_url("admin/maintenance/taxes")?>">Taxes Rates</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#partners" aria-expanded="true"
            aria-controls="partners">
            <i class="fas fa-fw fa-briefcase"></i>
            <span>Partners</span>
        </a>
        <div id="partners" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!-- <h6 class="collapse-header">Custom Components:</h6> -->
                <a class="collapse-item" href="<?=base_url("admin/provider_insurance")?>">Car Insurance</a>
                <a class="collapse-item" href="">Car Loan</a>
            </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?=base_url("admin/users/")?>">
            <i class="fas fa-fw fa-users"></i>
            <span>Users</span>
        </a>
    </li>
    <hr class="sidebar-divider mb-4">
    <div class="sidebar-heading">
        Interface
    </div>
    <li class="nav-item">
        <a class="nav-link" href="<?=base_url("admin/settings")?>">
            <i class="fas fa-fw fa-cogs"></i>
            <span>Website Settings</span>
        </a>
    </li>
    <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#WebPages" aria-expanded="true"
            aria-controls="WebPages">
            <i class="fas fa-fw fa-cogs"></i>
            <span>Pages</span>
        </a>
        <div id="WebPages" class="collapse" aria-labelledby="WebPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="<?=base_url("admin/services")?>"><i class="fa fa-fw fa-tools"></i> Services</a>
                <a class="collapse-item" href="<?=base_url("admin/testimonials")?>"><i class="fa fa-fw fa-comments"></i> Testimonials</a>
                <a class="collapse-item" href="<?=base_url("admin/partners")?>"><i class="fa fa-fw fa-user-friends"></i> Partners</a>
                <a class="collapse-item" href="<?=base_url("admin/aboutus")?>"><i class="fa fa-fw fa-address-card"></i> About Us</a>
            </div>
        </div>
    </li>
    
    <li class="nav-item">
        <a class="nav-link" href="<?=base_url()?>">
            <i class="fas fa-fw fa-globe"></i>
            <span>Visit Website</span>
        </a>
    </li>
    

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
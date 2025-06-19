<?php 
$page = isset($data['page']) ? $data['page'] : '';
$pages = isset($data['pages']) ? $data['pages'] : '';
 $level = isset($_SESSION['level']) ? $_SESSION['level'] : '';
//$level ="superadmin";
?>

<div id="app">
    <div id="sidebar" class="active">
        <div class="sidebar-wrapper active">
            <div class="sidebar-header position-relative">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="logo">
                        <h5><a href="<?= base_url ?>/home"><?= $data['username'] ?></a></h5>
                    </div>
                    <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                        <div class="form-check form-switch fs-6">
                            <input class="me-0" type="hidden" id="toggle-dark">
                        </div>
                    </div>
                    <div class="sidebar-toggler x">
                        <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                    </div>
                </div>
            </div>
            <div class="sidebar-menu">
                <ul class="menu">
         
                    <li class="sidebar-title">Menu</li>
                    <li class="sidebar-item <?= ($pages == 'home') ? 'active' : '' ?>">
                        <a href="<?= base_url; ?>/home" class='sidebar-link'>
                            <i class="bi bi-grid-fill"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                

              
                        <li class="sidebar-item <?= ($pages == 'kamp') ? 'active' : '' ?>">
                            <a href="<?= base_url; ?>/kampanye" class='sidebar-link'>
                                <i class="fa-solid fa-truck-fast"></i>
                                <span>Input Kampanye</span>
                            </a>
                        </li>
                        <li class="sidebar-item <?= ($pages == 'mont') ? 'active' : '' ?>">
                            <a href="<?= base_url; ?>/monitoring" class='sidebar-link'>
                                 <i class="fa-brands fa-discord"></i>
                                <span>Input Kampanye Manual</span>
                            </a>
                        </li>
                            <li class="sidebar-item <?= ($pages == 'listmont') ? 'active' : '' ?>">
                            <a href="<?= base_url; ?>/monitoring/listkampanye" class='sidebar-link'>
                                <i class="fa-solid fa-person-circle-exclamation"></i>
                                <span>List Kampanye Manual </span>
                            </a>
                        </li>
                 
                          </li>
                            <li class="sidebar-item <?= ($pages == 'dgtl') ? 'active' : '' ?>">
                            <a href="<?= base_url; ?>/monitoring/digital" class='sidebar-link'>
                               <i class="fa-solid fa-gas-pump"></i>
                                <span>input Kampanye Digital</span>
                            </a>
                        </li>
                          <li class="sidebar-item <?= ($pages == 'listdgtl') ? 'active' : '' ?>">
                            <a href="<?= base_url; ?>/monitoring/listdigital" class='sidebar-link'>
                                <i class="fa-solid fa-globe"></i>
                                <span>List Kampanye Digital </span>
                            </a>
                        </li>
                    
                 
                     
               

                </ul>
                
                <ul class="menu">
                    <li class="sidebar-item">
                        <a href="<?= base_url; ?>/logout" class='sidebar-link'>
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <span>Sign Out</span>
                        </a>      
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

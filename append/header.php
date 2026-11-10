<?php require_once __DIR__ . '/config.php'; ?>
 <!-- HEADER START -->
 <header class="site-header header-style-1 mobile-sider-drawer-menu">
            <div class="sticky-header main-bar-wraper">
                <div  style="border-bottom: 5px solid #074461;" class="main-bar bg-white p-0">
                    <div class="container">
                        <div class="logo-header">
                            <div class="logo-header-inner logo-header-one">
                                <a href="index.php">
                                    <div class="m-0 p-0" style="overflow: hidden;">
                                        <!-- Cropped, right-sized logo (21 KB) in the same 160x90 box; no scale() hack -->
                                        <picture>
                                            <source type="image/webp" srcset="images/logo/vk-header-logo.webp">
                                            <img src="images/logo/vk-header-logo.png" alt="Veeru Kannammal Construction"
                                                width="160" height="90" fetchpriority="high" decoding="async"
                                                style="height: 90px; width: 160px; max-width: 100%; object-fit: contain;">
                                        </picture>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <!-- NAV Toggle Button -->
                        <button id="mobile-side-drawer" data-target=".header-nav" data-toggle="collapse" type="button" class="navbar-toggler collapsed" aria-label="Toggle navigation menu">
                            <!-- <span class="sr-only">Toggle navigation</span> -->
                            <span class="icon-bar icon-bar-first"></span>
                            <span class="icon-bar icon-bar-two"></span>
                            <span class="icon-bar icon-bar-three"></span>
                        </button> 
                        <!-- MAIN Vav -->
                        <div class="header-nav mt-1">
                            <?php 
                                $current_page = basename($_SERVER['PHP_SELF']); 
                            ?>
                            <ul class="nav navbar-nav p-0">
                                <li class="<?= ($current_page == 'index.php') ? 'active' : ''; ?>">
                                    <a href="index.php">Home</a>
                                </li>
                                <li class="<?= ($current_page == 'about.php') ? 'active' : ''; ?>">
                                    <a href="about.php">About us</a>
                                </li> 
                                <li class="<?= ($current_page == 'services.php') ? 'active' : ''; ?>">
                                    <a href="services.php">Services</a>
                                </li> 
                                <li class="<?= ($current_page == 'projects.php') ? 'active' : ''; ?>">
                                    <a href="projects.php">Projects</a>
                                </li> 
                                <li class="<?= ($current_page == 'joint-venture.php') ? 'active' : ''; ?>">
                                    <a href="joint-venture.php">Joint Venture</a>
                                </li>
                                <li class="<?= ($current_page == 'social-media.php') ? 'active' : ''; ?>">
                                    <a href="social-media.php">Social media</a>
                                </li>
                                <li class="<?= ($current_page == 'contact.php') ? 'active' : ''; ?>">
                                    <a href="contact.php">Contact us</a>
                                </li>
                            </ul>
                        </div>         
                    </div>
                </div>
            </div>
        </header>
<?php

/**
 * Includes header and side navigation bar.
 *
 */
?>

<nav class="sidebar">

    <img src="src/images/logos/jpia.png" alt="" class="org-logo">

    <h6>JUNIOR PHILIPPINE INSTITUTE OF ACCOUNTANTS</h6>

    <div class="menu-content">
        <ul class="menu-items">
            <div class="menu-title">Your menu title</div>

            <li class="item">
                <a href="#">Your first link</a>
            </li>

            <li class="item">
                <div class="submenu-item" data-bs-toggle="collapse" href="#firstSubmenu">
                    <span>First submenu</span>
                    <i class="fas fa-chevron-right"></i>
                </div>

                <ul class="menu-items submenu collapse" id="firstSubmenu">
                    <div class="menu-title">
                        <i class="fas fa-chevron-left"></i>
                        Your submenu title
                    </div>
                    <li class="item">
                        <a href="#">First sublink</a>
                    </li>
                    <!-- Add more sublinks as needed -->
                </ul>
            </li>

            <li class="item">
                <a href="#">Your second link</a>
            </li>

            <li class="item">
                <a href="#">Your third link</a>
            </li>
        </ul>
    </div>
</nav>

<nav class="navbar">
    <div class="container-fluid">
        <i class="fas fa-bars" id="sidebar-close"></i>
        <span class="navbar-text mx-auto fw-bold">ELECTION COMMITTEE PORTAL</span>
    </div>
</nav>
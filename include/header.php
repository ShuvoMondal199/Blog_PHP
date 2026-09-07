<?php
session_start();

$username = $_SESSION['username'] ?? 'Guest';
$email = $_SESSION['email'] ?? 'Not available';

// Get first letter for avatar
$avatar = strtoupper(substr($username, 0, 1));
?>

<div class="container-xxl">

    <nav class="navbar navbar-expand-lg navbar-dark  custom-navbar px-3 py-2">

        <div class="container-fluid">

            <!-- LOGO -->
            <a class="navbar-brand" href="/index.php">
                <span class="text-primary">BLOG</span>SHUVO
            </a>


            <!-- MOBILE TOGGLE -->
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainNavbar"
                aria-controls="mainNavbar"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>


            <!-- NAVBAR CONTENT -->
            <div class="collapse navbar-collapse" id="mainNavbar">


                <!-- MENU -->
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">

                    <li class="nav-item">
                        <a class="nav-link active" href="/index.php">
                            Home
                        </a>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link" href="pages/about.php">
                            About
                        </a>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link" href="pages/service.php">
                            Services
                        </a>
                    </li>


                    <!-- BLOG DROPDOWN -->
                    <li class="nav-item dropdown">

                        <a
                            class="nav-link dropdown-toggle"
                            href="#"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >
                            Blogs
                        </a>


                        <ul class="dropdown-menu">

                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-grid me-2"></i>
                                    All Blogs
                                </a>
                            </li>


                            <li>
                                <a class="dropdown-item" href="Post/add_post.php">
                                    <i class="bi bi-plus-circle me-2"></i>
                                    Add Blog
                                </a>
                            </li>


                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-tags me-2"></i>
                                    Categories
                                </a>
                            </li>


                            <li>
                                <hr class="dropdown-divider">
                            </li>


                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-gear me-2"></i>
                                    Blog Action
                                </a>
                            </li>

                        </ul>

                    </li>


                    <!-- CONTACT -->
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            Contact
                        </a>
                    </li>

                </ul>


                <!-- ACCOUNT -->
                <div class="account-wrapper">

                    <!-- ACCOUNT BUTTON -->
                    <button
                        type="button"
                        class="account-btn"
                    >

                        <span class="account-icon">
                            <i class="bi bi-person-fill"></i>
                        </span>

                        <span class="account-text">
                            Account
                        </span>

                        <i class="bi bi-chevron-down account-arrow"></i>

                    </button>


                    <!-- ACCOUNT CARD -->
                    <div class="account-card">

                        <!-- PROFILE HEADER -->
                        <div class="profile-header">

                            <div class="profile-avatar">
                                <?php echo htmlspecialchars($avatar); ?>
                            </div>


                            <div class="profile-details">

                                <div class="profile-name">
                                    <?php echo htmlspecialchars($username); ?>
                                </div>

                                <div class="profile-status">
                                    <span class="online-dot"></span>
                                    Online
                                </div>

                            </div>

                        </div>


                        <!-- ACCOUNT INFORMATION -->
                        <div class="account-info">

                            <div class="info-item">
                                <i class="bi bi-person"></i>

                                <span>
                                    <?php echo htmlspecialchars($username); ?>
                                </span>
                            </div>


                            <div class="info-item">
                                <i class="bi bi-envelope"></i>

                                <span>
                                    <?php echo htmlspecialchars($email); ?>
                                </span>
                            </div>

                        </div>


                        <!-- PROFILE -->
                        <a
                            href="/profile.php"
                            class="btn btn-primary account-action"
                        >
                            <i class="bi bi-person-circle me-2"></i>
                            My Profile
                        </a>


                        <!-- LOGOUT -->
                        <a
                            href="/logout.php"
                            class="btn btn-outline-danger account-action"
                        >
                            <i class="bi bi-box-arrow-right me-2"></i>
                            Logout
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </nav>


    

</div>

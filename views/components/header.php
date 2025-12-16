<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title'] ?? "Skillspire" ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/fontawesome.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/global.css">
  </head>
  <body>
    <!-- Header -->
    <header class="header">
      <div class="container">
        <img src="<?php echo BASE_URL; ?>/public/images/logo.png" alt="Skillspire Logo" width="100" />
        <nav class="nav">
          <a href="<?php echo BASE_URL; ?>" class="nav-link">Home</a>
          <a href="<?php echo BASE_URL; ?>/courses" class="nav-link">Courses</a>
          <!-- If logged in -->
          <?php if (isset($_SESSION['user_id'])): ?>
            <?php if ($_SESSION['user_role'] == 'instructor'): ?>
              <a href="<?php echo BASE_URL; ?>/dashboard" class="nav-link">Dashboard</a>
            <?php endif; ?>
            <?php if ($_SESSION['user_role'] == 'student'): ?>
              <a href="<?php echo BASE_URL; ?>/my-courses" class="nav-link">My Courses</a>
            <?php endif; ?>
            <a href="<?php echo BASE_URL; ?>/login/logout" class="nav-link">Logout</a>
          <?php else: ?>
            <a href="<?php echo BASE_URL; ?>/login" class="btn-login">Login</a>
            <a href="<?php echo BASE_URL; ?>/register" class="btn-signup">Sign Up</a>
          <?php endif; ?>
        </nav>
        <button class="hamburger" id="hamburger">
          <i class="fa-solid fa-bars fa-2xl"></i>
        </button>
      </div>
      <!-- Mobile Menu -->
      <nav class="mobile-menu" id="mobileMenu">
        <a href="<?php echo BASE_URL; ?>" class="mobile-nav-link">Home</a>
        <a href="<?php echo BASE_URL; ?>/courses" class="mobile-nav-link">Courses</a>
        <!-- If logged in -->
        <?php if (isset($_SESSION['user_id'])): ?>
          <?php if ($_SESSION['user_role'] == 'instructor'): ?>
            <a href="<?php echo BASE_URL; ?>/dashboard" class="mobile-nav-link">Dashboard</a>
          <?php endif; ?>
          <?php if ($_SESSION['user_role'] == 'student'): ?>
            <a href="<?php echo BASE_URL; ?>/my-courses" class="mobile-nav-link">My Courses</a>
          <?php endif; ?>
          <a href="<?php echo BASE_URL; ?>/login/logout" class="mobile-nav-link">Logout</a>
        <?php else: ?>
          <a href="<?php echo BASE_URL; ?>/login" class="mobile-nav-link">Login</a>
          <a href="<?php echo BASE_URL; ?>/register" class="mobile-nav-link">Sign Up</a>
        <?php endif; ?>
      </nav>
    </header>
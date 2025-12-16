<?php require APPROOT . '/views/components/header.php'; ?>

<!-- Inject Login Specific CSS -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/views/login/login.css">

<div class="login-container">
  <div class="login-card">
    <div class="login-header">
      <h2>Welcome Back</h2>
      <p>Login to continue your journey</p>
    </div>
    
    <form action="<?php echo BASE_URL; ?>/login/login" method="post">
      <div class="form-group">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" name="email" id="email" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" placeholder="Enter your email" value="<?php echo $data['email']; ?>">
        <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
      </div>
      
      <div class="form-group">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" placeholder="Enter your password">
        <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
      </div>

      <button type="submit" class="btn-submit">Login</button>
    </form>
    
    <div class="login-footer">
      <p>Don't have an account? <a href="<?php echo BASE_URL; ?>/register">Sign Up</a></p>
    </div>
  </div>
</div>

<?php require APPROOT . '/views/components/footer.php'; ?>

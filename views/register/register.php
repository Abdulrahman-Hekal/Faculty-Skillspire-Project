<?php require APPROOT . '/views/components/header.php'; ?>

<!-- Inject Register Specific CSS -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/views/register/register.css">

<div class="register-container">
  <div class="register-card">
    <div class="register-header">
      <h2>Join Skillspire</h2>
      <p>Start your learning journey today</p>
    </div>
    
    <form action="<?php echo BASE_URL; ?>/register/register" method="post">
      <div class="form-group">
        <label for="name" class="form-label">Full Name</label>
        <input type="text" name="name" id="name" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" placeholder="Enter your full name" value="<?php echo $data['name']; ?>">
        <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
      </div>

      <div class="form-group">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" name="email" id="email" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" placeholder="Enter your email" value="<?php echo $data['email']; ?>">
        <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
      </div>
      
      <div class="form-group">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" placeholder="Create a password" value="<?php echo $data['password']; ?>">
        <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
      </div>

      <div class="form-group">
        <label for="confirm_password" class="form-label">Confirm Password</label>
        <input type="password" name="confirm_password" id="confirm_password" class="form-control <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" placeholder="Confirm your password" value="<?php echo $data['confirm_password']; ?>">
        <span class="invalid-feedback"><?php echo $data['confirm_password_err']; ?></span>
      </div>

      <div class="form-group">
        <label class="form-label">I want to be a:</label>
        <div class="radio-group">
            <label class="radio-label">
                <input type="radio" name="role" value="student" checked> Student
            </label>
            <label class="radio-label">
                <input type="radio" name="role" value="instructor"> Instructor
            </label>
        </div>
      </div>

      <button type="submit" class="btn-submit">Register</button>
    </form>
    
    <div class="register-footer">
      <p>Already have an account? <a href="<?php echo BASE_URL; ?>/login">Login</a></p>
    </div>
  </div>
</div>

<?php require APPROOT . '/views/components/footer.php'; ?>

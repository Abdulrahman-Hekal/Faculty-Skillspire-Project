<?php require APPROOT . '/views/components/header.php'; ?>

<!-- Inject Profile CSS -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/views/profile/profile.css">

<div class="container">
    <div class="row py-5">
        <div class="col-12 Box my-5">
            <div class="profile-header py-5">
                <div class="avatar-container">
                    <div class="avatar">
                        <i class="fa-regular fa-user fa-2xl"></i>
                    </div>
                </div>

                <div class="profile-info">
                    <h1><?php echo $data['user']['name']; ?> <span class="badge"><?php echo $data['user']['role']; ?></span></h1>
                    <div class="meta">
                        <span><i class="fa-regular fa-calendar"></i> Joined <?php echo date('M d, Y', strtotime($data['user']['created_at'])); ?></span>
                    </div>
                </div>

                <button class="edit-profile-btn" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    <i class="fa-solid fa-pen"></i> Edit Profile
                </button>
            </div>
        </div>
        <?php if ($data['user']['role'] == 'student'): ?>
            <div class="col-12 Box info">
                <div class="infobox">
                    <i class="fa-solid fa-book-open iconsize" style="color: #1a73e8;"></i>
                    <h4><?php echo $data['stats']['total_enrolled']; ?></h4>
                    <p>Enrolled Courses</p>
                </div>
                <div class="infobox">
                    <i class="fa-solid fa-award iconsize" style="color: #2ecc71;"></i>
                    <h4><?php echo $data['stats']['total_completed']; ?></h4>
                    <p>Completed</p>
                </div>
            </div>
        <?php endif; ?>
        <div class="col-12 Box">
            <div id="profile" class="tab-pane active">
                <div class="profile-form">
                    <h4>Personal Information</h4>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" value="<?php echo $data['user']['name']; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" value="<?php echo $data['user']['email']; ?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

<!-- Background Band -->
<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="<?php echo BASE_URL; ?>/profile/update" method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['user']['name']; ?>" required>
                <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" value="<?php echo $data['user']['email']; ?>" disabled style="cursor: not-allowed;">
            </div>

            <hr class="my-3">
            <h6 class="mb-3">Change Password <small class="text-muted fw-normal">(Leave blank to keep current)</small></h6>

            <div class="mb-3">
                <label for="current_password" class="form-label">Current Password</label>
                <input type="password" name="current_password" class="form-control <?php echo (!empty($data['current_password_err'])) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $data['current_password_err']; ?></span>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">New Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
            </div>

            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm New Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $data['confirm_password_err']; ?></span>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php require APPROOT . '/views/components/footer.php'; ?>

<!-- Auto-open modal if there are errors -->
<?php if (!empty($data['name_err']) || !empty($data['email_err']) || !empty($data['current_password_err']) || !empty($data['password_err']) || !empty($data['confirm_password_err'])): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var editProfileModal = new bootstrap.Modal(document.getElementById('editProfileModal'));
        editProfileModal.show();
    });
</script>
<?php endif; ?>

<?php require APPROOT . '/views/components/header.php'; ?>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>/views/dashboard/dashboard.css">

<div class="dashboard-container">
    <div class="container">
        <div class="dashboard-header">
            <div>
                <a href="<?php echo BASE_URL; ?>/dashboard" class="text-muted text-decoration-none mb-2 d-inline-block">
                    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                </a>
                <h1 class="dashboard-title">Create New Course</h1>
            </div>
        </div>

        <div class="form-card">
            <form action="<?php echo BASE_URL; ?>/dashboard/store" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title" class="form-label">Course Title</label>
                    <input type="text" name="title" id="title" class="form-control <?php echo (!empty($data['title_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['title'] ?? ''; ?>" placeholder="e.g. Complete Web Development Bootcamp">
                    <span class="invalid-feedback"><?php echo $data['title_err'] ?? ''; ?></span>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="category" class="form-label">Category</label>
                        <select name="category" id="category" class="form-control <?php echo (!empty($data['category_err'])) ? 'is-invalid' : ''; ?>">
                            <option value="">Select Category</option>
                            <option value="Development" <?php echo (isset($data['category']) && $data['category'] == 'Development') ? 'selected' : ''; ?>>Development</option>
                            <option value="Business" <?php echo (isset($data['category']) && $data['category'] == 'Business') ? 'selected' : ''; ?>>Business</option>
                            <option value="Design" <?php echo (isset($data['category']) && $data['category'] == 'Design') ? 'selected' : ''; ?>>Design</option>
                            <option value="Marketing" <?php echo (isset($data['category']) && $data['category'] == 'Marketing') ? 'selected' : ''; ?>>Marketing</option>
                        </select>
                        <span class="invalid-feedback"><?php echo $data['category_err'] ?? ''; ?></span>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="price" class="form-label">Price ($)</label>
                        <input type="number" name="price" id="price" class="form-control <?php echo (!empty($data['price_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['price'] ?? ''; ?>" placeholder="e.g. 49.99" step="0.01">
                        <span class="invalid-feedback"><?php echo $data['price_err'] ?? ''; ?></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control <?php echo (!empty($data['description_err'])) ? 'is-invalid' : ''; ?>" placeholder="Describe your course content..."><?php echo $data['description'] ?? ''; ?></textarea>
                    <span class="invalid-feedback"><?php echo $data['description_err'] ?? ''; ?></span>
                </div>

                <div class="form-group">
                    <label class="form-label">Course Thumbnail</label>
                    <div class="file-upload" onclick="document.getElementById('thumbnail').click()">
                        <i class="fas fa-cloud-upload-alt fa-2x mb-2 text-muted"></i>
                        <p class="mb-0 text-muted">Click to upload image</p>
                        <input type="file" name="thumbnail" id="thumbnail" style="display: none;" onchange="previewImage(this)">
                    </div>
                    <img id="imagePreview" class="preview-img" alt="Thumbnail Preview">
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn-add-course text-center">Create Course</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var preview = document.getElementById('imagePreview');
            preview.src = e.target.result;
            preview.classList.add('active');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require APPROOT . '/views/components/footer.php'; ?>

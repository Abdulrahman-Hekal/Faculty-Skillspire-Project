<?php require APPROOT . '/views/components/header.php'; ?>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>/views/dashboard/dashboard.css">

<div class="dashboard-container">
    <div class="container">
        <div class="dashboard-header">
            <div>
                <a href="<?php echo BASE_URL; ?>/dashboard" class="text-muted text-decoration-none mb-2 d-inline-block">
                    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                </a>
                <h1 class="dashboard-title">Edit Course</h1>
            </div>
        </div>

        <div class="form-card">
            <form action="<?php echo BASE_URL; ?>/dashboard/update/<?php echo $data['course']['id']; ?>" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title" class="form-label">Course Title</label>
                    <input type="text" name="title" id="title" class="form-control <?php echo (!empty($data['title_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['course']['title']; ?>" placeholder="e.g. Complete Web Development Bootcamp">
                    <span class="invalid-feedback"><?php echo $data['title_err'] ?? ''; ?></span>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="category" class="form-label">Category</label>
                        <select name="category" id="category" class="form-control <?php echo (!empty($data['category_err'])) ? 'is-invalid' : ''; ?>">
                            <option value="">Select Category</option>
                            <option value="Development" <?php echo ($data['course']['category'] == 'Development') ? 'selected' : ''; ?>>Development</option>
                            <option value="Business" <?php echo ($data['course']['category'] == 'Business') ? 'selected' : ''; ?>>Business</option>
                            <option value="Design" <?php echo ($data['course']['category'] == 'Design') ? 'selected' : ''; ?>>Design</option>
                            <option value="Marketing" <?php echo ($data['course']['category'] == 'Marketing') ? 'selected' : ''; ?>>Marketing</option>
                        </select>
                        <span class="invalid-feedback"><?php echo $data['category_err'] ?? ''; ?></span>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="price" class="form-label">Price ($)</label>
                        <input type="number" name="price" id="price" class="form-control <?php echo (!empty($data['price_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['course']['price']; ?>" placeholder="e.g. 49.99" step="0.01">
                        <span class="invalid-feedback"><?php echo $data['price_err'] ?? ''; ?></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control <?php echo (!empty($data['description_err'])) ? 'is-invalid' : ''; ?>" placeholder="Describe your course content..."><?php echo $data['course']['description']; ?></textarea>
                    <span class="invalid-feedback"><?php echo $data['description_err'] ?? ''; ?></span>
                </div>

                <div class="form-group">
                    <label class="form-label">Current Thumbnail</label>
                    <?php if($data['course']['thumbnail']): ?>
                        <div class="mb-3">
                            <img src="<?php echo $data['course']['thumbnail']; ?>" alt="Current Thumbnail" style="height: 100px; border-radius: 8px;">
                        </div>
                    <?php endif; ?>
                    
                    <label class="form-label">Update Thumbnail</label>
                    <div class="file-upload" onclick="document.getElementById('thumbnail').click()">
                        <i class="fas fa-cloud-upload-alt fa-2x mb-2 text-muted"></i>
                        <p class="mb-0 text-muted">Click to upload new image</p>
                        <input type="file" name="thumbnail" id="thumbnail" style="display: none;" onchange="previewImage(this)">
                    </div>
                    <img id="imagePreview" class="preview-img" alt="Thumbnail Preview">
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn-add-course text-center">Update Course</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container mt-5">
        <div class="dashboard-header">
            <div>
                <h2 class="dashboard-title">Course Lessons</h2>
                <p class="dashboard-subtitle">Manage lessons for this course</p>
            </div>
            <a href="<?php echo BASE_URL; ?>/lesson/create/<?php echo $data['course']['id']; ?>" class="btn-add-course">
                <i class="fas fa-plus me-2"></i> Add Lesson
            </a>
        </div>

        <?php if (empty($data['lessons'])): ?>
            <div class="text-center py-4 bg-white rounded-3 shadow-sm">
                <p class="text-muted mb-0">No lessons added yet.</p>
            </div>
        <?php else: ?>
            <div class="card shadow-sm border-0">
                <div class="list-group list-group-flush">
                    <?php foreach($data['lessons'] as $lesson): ?>
                        <div class="list-group-item p-4 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-secondary me-3 rounded-pill"><?php echo $lesson['order']; ?></span>
                                <div>
                                    <h5 class="mb-1 fw-bold"><?php echo $lesson['title']; ?></h5>
                                    <small class="text-muted"><i class="fas fa-video me-1"></i> Video Lesson</small>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="<?php echo BASE_URL; ?>/lesson/edit/<?php echo $lesson['id']; ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="<?php echo BASE_URL; ?>/lesson/delete/<?php echo $lesson['id']; ?>" method="post" onsubmit="return confirm('Are you sure you want to delete this lesson?');">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
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

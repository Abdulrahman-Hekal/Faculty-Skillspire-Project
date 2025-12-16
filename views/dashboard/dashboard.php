<?php require APPROOT . '/views/components/header.php'; ?>

<!-- Inject Dashboard CSS -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/views/dashboard/dashboard.css">

<div class="dashboard-container">
    <div class="container">
        <div class="dashboard-header">
            <div>
                <h1 class="dashboard-title">Instructor Dashboard</h1>
                <p class="dashboard-subtitle">Manage your courses and content</p>
            </div>
            <a href="<?php echo BASE_URL; ?>/dashboard/create" class="btn-add-course">
                <i class="fas fa-plus me-2"></i> New Course
            </a>
        </div>

        <?php if (empty($data['courses'])): ?>
            <div class="text-center py-5">
                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                <h3 class="text-muted">No courses yet</h3>
                <p class="text-muted mb-4">Start by creating your first course to share your knowledge.</p>
                <a href="<?php echo BASE_URL; ?>/dashboard/create" class="btn-add-course">Create Course</a>
            </div>
        <?php else: ?>
            <div class="course-grid">
                <?php foreach($data['courses'] as $course): ?>
                    <div class="dashboard-card">
                        <span class="card-badge <?php echo $course['is_published'] ? 'badge-published' : 'badge-draft'; ?>">
                            <?php echo $course['is_published'] ? 'Published' : 'Draft'; ?>
                        </span>
                        
                        <?php if($course['thumbnail']): ?>
                            <img src="<?php echo $course['thumbnail']; ?>" alt="<?php echo $course['title']; ?>" class="card-img">
                        <?php else: ?>
                            <div class="card-img d-flex align-items-center justify-content-center bg-light text-muted">
                                <i class="fas fa-image fa-2x"></i>
                            </div>
                        <?php endif; ?>

                        <div class="card-content">
                            <h3 class="card-title"><?php echo $course['title']; ?></h3>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-light text-dark"><?php echo $course['category']; ?></span>
                                <span class="card-price">$<?php echo $course['price']; ?></span>
                            </div>

                            <div class="card-actions">
                                <a href="<?php echo BASE_URL; ?>/dashboard/edit/<?php echo $course['id']; ?>" class="btn-action btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="<?php echo BASE_URL; ?>/dashboard/togglePublish/<?php echo $course['id']; ?>" class="btn-action <?php echo $course['is_published'] ? 'btn-unpublish' : 'btn-publish'; ?>">
                                    <i class="fas <?php echo $course['is_published'] ? 'fa-eye-slash' : 'fa-eye'; ?>"></i> 
                                    <?php echo $course['is_published'] ? 'Unpublish' : 'Publish'; ?>
                                </a>
                                <form action="<?php echo BASE_URL; ?>/dashboard/delete/<?php echo $course['id']; ?>" method="post" style="flex: 1;" onsubmit="return confirm('Are you sure you want to delete this course?');">
                                    <button type="submit" class="btn-action btn-delete" style="width: 100%;">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require APPROOT . '/views/components/footer.php'; ?>

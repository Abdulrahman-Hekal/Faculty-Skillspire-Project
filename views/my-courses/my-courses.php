<?php require APPROOT . '/views/components/header.php'; ?>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>/views/my-courses/my-courses.css">

<div class="my-courses-container">
    <div class="container">
        <div class="my-courses-header">
            <div>
                <h1 class="display-5 fw-bold mb-0" style="font-size: 2rem; color: #333;">My Learning</h1>
                <p class="text-muted mb-0 mt-2">Track your progress and continue learning.</p>
            </div>
        </div>
    <?php if (empty($data['courses'])): ?>
        <div class="empty-state">
            <i class="fas fa-book-open empty-icon"></i>
            <h3>You haven't enrolled in any courses yet.</h3>
            <p class="text-muted mb-4">Explore our catalog and find the perfect course for you.</p>
            <a href="<?php echo BASE_URL; ?>/courses" class="btn btn-primary btn-lg">Browse Courses</a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach($data['courses'] as $course): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="course-progress-card">
                        <?php if($course['thumbnail']): ?>
                            <img src="<?php echo $course['thumbnail']; ?>" alt="<?php echo $course['title']; ?>" class="course-progress-img">
                        <?php else: ?>
                            <div class="course-progress-img d-flex align-items-center justify-content-center bg-light text-muted">
                                <i class="fas fa-image fa-2x"></i>
                            </div>
                        <?php endif; ?>

                        <div class="course-progress-body">
                            <h3 class="course-progress-title" title="<?php echo $course['title']; ?>"><?php echo $course['title']; ?></h3>
                            <p class="text-muted small mb-2"><i class="fas fa-user-circle me-1"></i> Instructor</p>
                            
                            <div class="d-flex justify-content-between text-muted small mb-1">
                                <span>Progress</span>
                                <span>0%</span> 
                            </div>
                            <div class="progress-bar-container">
                                <div class="progress-bar-fill" style="width: 0%"></div>
                            </div>
                            
                            <a href="<?php echo BASE_URL; ?>/lesson/<?php echo $course['id']; ?>" class="btn-continue">
                                Continue Learning
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
</div>

<?php require APPROOT . '/views/components/footer.php'; ?>

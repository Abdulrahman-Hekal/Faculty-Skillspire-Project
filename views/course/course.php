<?php require APPROOT . '/views/components/header.php'; ?>

<!-- Inject Course CSS -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/views/course/course.css">

<!-- Background Band -->
<div class="course-header-background"></div>

<div class="container course-details-container">
    <div class="pop-out-grid">
        
        <!-- Left Column: Preview Card -->
        <div class="course-preview-card">
            <?php if ($data['course']['thumbnail']): ?>
                <img src="<?php echo $data['course']['thumbnail']; ?>" alt="Course Preview" class="preview-image">
            <?php else: ?>
                <div class="d-flex align-items-center justify-content-center bg-secondary text-white" style="height: 230px;">
                    <i class="fas fa-image fa-4x"></i>
                </div>
            <?php endif; ?>
            
            <div class="preview-content">
                <div class="course-price-large">
                    <?php echo $data['course']['price'] > 0 ? '$' . $data['course']['price'] : 'Free'; ?>
                </div>
                
                <button class="enroll-btn">Enrol Now</button>
                
                <div class="preview-features">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-certificate text-warning"></i>
                        <span>Certificate of Completion</span>
                    </div>
                </div>

                <div class="divider-or">or</div>
                
                <div class="guarantee-text text-center">
                    <i class="fas fa-shield-alt me-1"></i> 30-Day Money-Back Guarantee<br>
                    <i class="fas fa-infinity me-1"></i> Full Lifetime Access
                </div>
            </div>
        </div>

        <!-- Right Column: Course Info -->
        <div class="course-info-column">
            
            <!-- Header Info -->
            <div class="course-header-info">
                <div class="course-categories">
                    <span class="category-tag"><?php echo $data['course']['category']; ?></span>
                    <!-- Example secondary tags if available -->
                    <span class="category-tag"><i class="fas fa-signal me-1"></i> All Levels</span>
                </div>

                <h1 class="course-title-large"><?php echo $data['course']['title']; ?></h1>
                
                <div class="course-meta">
                    <div class="meta-item">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($data['course']['instructor_name']); ?>&background=random" alt="Instructor" class="instructor-avatar">
                        <span>Created by <strong><?php echo $data['course']['instructor_name']; ?></strong></span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-star text-warning"></i>
                        <span><?php echo number_format($data['course']['avg_rating'], 1); ?> (<?php echo $data['course']['reviews_count']; ?> ratings)</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-user-friends"></i>
                        <span><?php echo $data['course']['students_count']; ?> students</span>
                    </div>
                </div>

                <div class="course-description">
                    <?php echo nl2br(htmlspecialchars($data['course']['description'])); ?>
                </div>
            </div>

            <!-- Curriculum Section -->
            <div class="white-section content-section">
                <h2 class="section-title">Course Content</h2>
                
                <?php if (!empty($data['lessons'])): ?>
                    <div class="curriculum-list">
                        <?php foreach ($data['lessons'] as $index => $lesson): ?>
                            <div class="lesson-item">
                                <span class="lesson-number"><?php echo $index + 1; ?></span>
                                <i class="fas fa-play-circle lesson-icon"></i>
                                <span class="lesson-title"><?php echo $lesson['title']; ?></span>
                                <?php if (!$index): // First lesson preview? ?> 
                                    <span class="badge bg-success bg-opacity-10 text-success">Preview</span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No lessons available yet.</p>
                <?php endif; ?>
            </div>

            <!-- Reviews Section -->
            <div class="white-section reviews-section">
                <h2 class="section-title">Student Reviews</h2>
                
                <div class="review-stats">
                    <div class="d-flex flex-column align-items-center">
                        <span class="big-rating"><?php echo number_format($data['course']['avg_rating'], 1); ?></span>
                        <div class="review-stars-large">
                            <?php 
                            $rating = round($data['course']['avg_rating']);
                            for($i=1; $i<=5; $i++) {
                                if($i <= $rating) echo '<i class="fas fa-star"></i>';
                                else echo '<i class="far fa-star"></i>';
                            }
                            ?>
                        </div>
                        <span class="text-muted mt-2">Course Rating</span>
                    </div>
                </div>

                <div class="reviews-list">
                    <?php if (!empty($data['reviews'])): ?>
                        <?php foreach ($data['reviews'] as $review): ?>
                            <div class="review-card">
                                <div class="review-header">
                                    <div class="reviewer-name"><?php echo $review['user_name']; ?></div>
                                    <div class="review-date"><?php echo date('M d, Y', strtotime($review['created_at'])); ?></div>
                                </div>
                                <div class="review-stars mb-2">
                                    <?php 
                                    for($i=1; $i<=5; $i++) {
                                        echo ($i <= $review['rating']) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                                    }
                                    ?>
                                </div>
                                <div class="review-content">
                                    <?php echo htmlspecialchars($review['comment']); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">No reviews yet for this course.</p>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require APPROOT . '/views/components/footer.php'; ?>
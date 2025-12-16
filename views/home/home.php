<?php require APPROOT . '/views/components/header.php'; ?>

<!-- Inject Home Specific CSS -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/views/home/home.css">

<div class="home-wrapper">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1 class="hero-title">Unlock Your Potential with <span class="text-gradient">Skillspire</span></h1>
                    <p class="hero-description">
                        Master new skills from industry experts. Access thousands of high-quality courses and take your career to the next level.
                    </p>
                    <div class="hero-buttons">
                        <a href="<?php echo BASE_URL; ?>/courses" class="btn-primary">Browse Courses</a>
                        <a href="<?php echo BASE_URL; ?>/login" class="btn-outline">Get Started</a>
                    </div>
                </div>
                <div class="hero-image">
                    <img src="<?php echo BASE_URL; ?>/public/images/logo.png" alt="Learning Illustration">
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-container">
                <div class="stat-item">
                    <h3>10k+</h3>
                    <p>Active Students</p>
                </div>
                <div class="stat-item">
                    <h3>500+</h3>
                    <p>Quality Courses</p>
                </div>
                <div class="stat-item">
                    <h3>100+</h3>
                    <p>Expert Instructors</p>
                </div>
                <div class="stat-item">
                    <h3>4.9</h3>
                    <p>Average Rating</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Why Choose <span class="text-gradient">Skillspire</span>?</h2>
                <p class="section-subtitle">We provide the best learning experience with features designed for your success.</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-video"></i>
                    </div>
                    <h3 class="feature-title">High Quality Video</h3>
                    <p class="feature-desc">Learn from high-definition video lessons that are easy to understand and follow.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <h3 class="feature-title">Earn Certificates</h3>
                    <p class="feature-desc">Get recognized for your achievements with verifiable certificates upon completion.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-infinity"></i>
                    </div>
                    <h3 class="feature-title">Lifetime Access</h3>
                    <p class="feature-desc">Pay once and get lifetime access to your courses. Learn at your own pace.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h3 class="feature-title">Expert Instructors</h3>
                    <p class="feature-desc">Learn from industry professionals who are passionate about teaching.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Top Rated Courses Section -->
    <section class="top-courses-section" style="padding: 80px 0; background: #f8f9fa;">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Top Rated <span class="text-gradient">Courses</span></h2>
                <p class="section-subtitle">Discover the highest-rated courses loved by our students.</p>
            </div>

            <?php if (isset($data['topCourses']) && !empty($data['topCourses'])): ?>
            <div id="topCoursesCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php 
                    $chunks = array_chunk($data['topCourses'], 3);
                    foreach($chunks as $index => $chunk): 
                    ?>
                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                       <div class="row justify-content-center">
                           <?php foreach($chunk as $course): ?>
                             <div class="col-md-4 mb-4">
                                <div class="card h-100 course-card" style="border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: transform 0.3s ease;">
                                    <?php if($course['thumbnail']): ?>
                                        <img src="<?php echo $course['thumbnail']; ?>" class="card-img-top" alt="<?php echo $course['title']; ?>" style="border-radius: 15px 15px 0 0; height: 200px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 200px; border-radius: 15px 15px 0 0; color: #ccc;">
                                            <i class="fas fa-image fa-3x"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="badge bg-primary rounded-pill"><?php echo $course['category']; ?></span>
                                            <div class="text-warning">
                                                <i class="fas fa-star"></i>
                                                <span><?php echo number_format($course['avg_rating'], 1); ?></span>
                                            </div>
                                        </div>
                                        <h5 class="card-title fw-bold text-dark"><?php echo $course['title']; ?></h5>
                                        <p class="card-text text-muted text-truncate"><?php echo $course['description']; ?></p>
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <span class="h5 mb-0 text-primary">$<?php echo $course['price']; ?></span>
                                            <a href="<?php echo BASE_URL; ?>/course/<?php echo $course['id']; ?>" class="btn btn-outline-primary btn-sm rounded-pill">View Details</a>
                                        </div>
                                    </div>
                                </div>
                             </div>
                           <?php endforeach; ?>
                       </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if(count($chunks) > 1): ?>
                <button class="carousel-control-prev" type="button" data-bs-target="#topCoursesCarousel" data-bs-slide="prev" style="width: 5%; filter: invert(100%);">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#topCoursesCarousel" data-bs-slide="next" style="width: 5%; filter: invert(100%);">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
                <?php endif; ?>
            </div>
            <?php else: ?>
                <p class="text-center text-muted">No courses available at the moment.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-box">
                <div class="cta-content">
                    <h2 class="cta-title">Ready to Start Learning?</h2>
                    <p style="margin-bottom: 2rem; color: #cbd5e0; font-size: 1.1rem;">Join thousands of students and start your journey today.</p>
                    <a href="<?php echo BASE_URL; ?>/courses" class="btn-primary" style="background: white; color: var(--primary-color);">Browse All Courses</a>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require APPROOT . '/views/components/footer.php'; ?>
<?php require APPROOT . '/views/components/header.php'; ?>

<!-- Inject Courses CSS -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/views/courses/courses.css">

<div class="courses-container">
    <div class="container">
        <!-- Search Bar and Filter Toggle -->
        <div class="row mb-4 g-2">
            <div class="col-12 col-md-auto">
                <button class="btn btn-primary w-100" type="button" data-bs-toggle="offcanvas" data-bs-target="#filterSidebar" aria-controls="filterSidebar">
                    <i class="fas fa-filter me-2"></i> Filters
                </button>
            </div>
            <div class="col">
                <form action="" method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control form-control-lg" placeholder="Search for courses..." value="<?php echo htmlspecialchars($data['filters']['search']); ?>">
                    <button type="submit" class="btn btn-primary px-4"><i class="fas fa-search"></i></button>
                    <!-- Keep other filters when searching -->
                    <?php foreach ($data['filters'] as $key => $value): ?>
                        <?php if ($key != 'search' && !empty($value)): ?>
                            <input type="hidden" name="<?php echo $key; ?>" value="<?php echo htmlspecialchars($value); ?>">
                        <?php endif; ?>
                    <?php endforeach; ?>
                </form>
            </div>
        </div>

        <!-- Offcanvas Sidebar (Always Hidden by Default) -->
        <div class="offcanvas offcanvas-start" tabindex="-1" id="filterSidebar" aria-labelledby="filterSidebarLabel">
            <div class="offcanvas-header bg-light border-bottom">
                <h5 class="offcanvas-title fw-bold" id="filterSidebarLabel">Filter Courses</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            
            <div class="offcanvas-body">
                <div class="filters-sidebar w-100 border-0 shadow-none p-0">
                    <form action="" method="GET" id="filterForm">
                        <?php if (!empty($data['filters']['search'])): ?>
                            <input type="hidden" name="search" value="<?php echo htmlspecialchars($data['filters']['search']); ?>">
                        <?php endif; ?>

                        <!-- Category Filter -->
                        <div class="filter-group">
                            <h4 class="filter-title">Category</h4>
                            <select name="category" class="form-select" onchange="this.form.submit()">
                                <option value="">All Categories</option>
                                <option value="Development" <?php echo $data['filters']['category'] == 'Development' ? 'selected' : ''; ?>>Development</option>
                                <option value="Design" <?php echo $data['filters']['category'] == 'Design' ? 'selected' : ''; ?>>Design</option>
                                <option value="Business" <?php echo $data['filters']['category'] == 'Business' ? 'selected' : ''; ?>>Business</option>
                                <option value="Marketing" <?php echo $data['filters']['category'] == 'Marketing' ? 'selected' : ''; ?>>Marketing</option>
                            </select>
                        </div>

                        <!-- Price Filter -->
                        <div class="filter-group">
                            <h4 class="filter-title">Price Range</h4>
                            <div class="price-range-inputs">
                                <input type="number" name="min_price" class="form-control" placeholder="Min" value="<?php echo htmlspecialchars($data['filters']['min_price']); ?>">
                                <span>-</span>
                                <input type="number" name="max_price" class="form-control" placeholder="Max" value="<?php echo htmlspecialchars($data['filters']['max_price']); ?>">
                            </div>
                        </div>

                        <!-- Rating Filter -->
                        <div class="filter-group">
                            <h4 class="filter-title">Rating</h4>
                            <?php 
                            $ratings = [4 => '4.0 & up', 3 => '3.0 & up', 2 => '2.0 & up', 1 => '1.0 & up'];
                            foreach($ratings as $val => $label): 
                            ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="min_rating" value="<?php echo $val; ?>" id="rating<?php echo $val; ?>" <?php echo $data['filters']['min_rating'] == $val ? 'checked' : ''; ?> onchange="this.form.submit()">
                                <label class="form-check-label" for="rating<?php echo $val; ?>">
                                    <span class="text-warning">
                                        <?php for($i=0;$i<$val;$i++) echo '<i class="fas fa-star"></i>'; ?>
                                        <?php for($i=$val;$i<5;$i++) echo '<i class="far fa-star"></i>'; ?>
                                    </span>
                                    <span class="small text-muted ms-1"><?php echo $label; ?></span>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Apply Filters</button>
                            <a href="<?php echo BASE_URL; ?>/courses" class="btn btn-outline-secondary btn-sm">Clear All</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main Content (Full Width) -->
            <div class="col-12">
                <!-- Sorting & Results Count -->
                <div class="courses-header">
                    <div class="total-results">
                        Showing <strong><?php echo count($data['courses']); ?></strong> of <strong><?php echo $data['pagination']['total_items']; ?></strong> results
                    </div>
                    <div class="sorting-controls">
                        <select class="form-select form-select-sm" name="sort" form="filterForm" onchange="this.form.sort_by.value=this.value.split('|')[0]; this.form.sort_dir.value=this.value.split('|')[1]; this.form.submit()">
                            <option value="created_at|DESC" <?php echo ($data['filters']['sort_by'] == 'created_at' && $data['filters']['sort_dir'] == 'DESC') ? 'selected' : ''; ?>>Newest First</option>
                            <option value="price|ASC" <?php echo ($data['filters']['sort_by'] == 'price' && $data['filters']['sort_dir'] == 'ASC') ? 'selected' : ''; ?>>Price: Low to High</option>
                            <option value="price|DESC" <?php echo ($data['filters']['sort_by'] == 'price' && $data['filters']['sort_dir'] == 'DESC') ? 'selected' : ''; ?>>Price: High to Low</option>
                            <option value="avg_rating|DESC" <?php echo ($data['filters']['sort_by'] == 'avg_rating' && $data['filters']['sort_dir'] == 'DESC') ? 'selected' : ''; ?>>Highest Rated</option>
                        </select>
                        <!-- Hidden inputs for sorting to work with the main form -->
                        <input type="hidden" name="sort_by" value="<?php echo htmlspecialchars($data['filters']['sort_by']); ?>" form="filterForm">
                        <input type="hidden" name="sort_dir" value="<?php echo htmlspecialchars($data['filters']['sort_dir']); ?>" form="filterForm">
                    </div>
                </div>

                <!-- Course Grid -->
                <?php if (!empty($data['courses'])): ?>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
                        <?php foreach ($data['courses'] as $course): ?>
                            <div class="col">
                                <div class="course-card h-100">
                                    <a href="<?php echo BASE_URL; ?>/course/<?php echo $course['id']; ?>" class="text-decoration-none">
                                        <div class="card-img-wrapper">
                                            <?php if ($course['thumbnail']): ?>
                                                <img src="<?php echo $course['thumbnail']; ?>" class="card-img-top" alt="<?php echo $course['title']; ?>">
                                            <?php else: ?>
                                                <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                                    <i class="fas fa-image fa-3x"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="card-body">
                                            <div class="course-category"><?php echo $course['category']; ?></div>
                                            <h3 class="course-title"><?php echo $course['title']; ?></h3>
                                            <div class="course-instructor">
                                                <i class="fas fa-chalkboard-teacher me-1"></i> <?php echo $course['instructor_name']; ?>
                                            </div>
                                            <div class="course-rating">
                                                <strong><?php echo number_format($course['avg_rating'], 1); ?></strong>
                                                <?php for($i=1; $i<=5; $i++): ?>
                                                    <?php if($i <= round($course['avg_rating'])): ?>
                                                        <i class="fas fa-star"></i>
                                                    <?php else: ?>
                                                        <i class="far fa-star"></i>
                                                    <?php endif; ?>
                                                <?php endfor; ?>
                                                <span class="reviews-count">(<?php echo $course['reviews_count']; ?> ratings)</span>
                                            </div>
                                            <div class="course-footer">
                                                <div class="course-price">$<?php echo number_format($course['price'], 2); ?></div>
                                                <span class="btn btn-sm btn-outline-primary rounded-pill">View Details</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($data['pagination']['total_pages'] > 1): ?>
                        <nav aria-label="Page navigation" class="mt-5">
                            <ul class="pagination">
                                <?php for ($i = 1; $i <= $data['pagination']['total_pages']; $i++): ?>
                                    <?php
                                    // Build query string for pagination links
                                    $queryParams = $data['filters'];
                                    $queryParams['page'] = $i;
                                    $queryString = http_build_query($queryParams);
                                    ?>
                                    <li class="page-item <?php echo $i == $data['pagination']['current_page'] ? 'active' : ''; ?>">
                                        <a class="page-link" href="?<?php echo $queryString; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-search fa-4x text-muted mb-3"></i>
                        <h3>No courses found</h3>
                        <p class="text-muted">Try adjusting your filters or search terms.</p>
                        <a href="<?php echo BASE_URL; ?>/courses" class="btn btn-primary mt-2">Clear All Filters</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/components/footer.php'; ?>
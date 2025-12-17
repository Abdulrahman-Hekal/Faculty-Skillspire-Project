<?php require APPROOT . '/views/components/header.php'; ?>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>/views/lesson/lesson.css">

<div class="lesson-container">
    <!-- Sidebar: Lesson List -->
    <div class="lesson-sidebar">
        <div class="sidebar-header">
            <h5 class="mb-2 text-truncate" title="<?php echo $data['course']['title']; ?>"><?php echo $data['course']['title']; ?></h5>
            <div class="course-progress">
                <div class="d-flex justify-content-between mb-1">
                    <span>Course Progress</span>
                    <span><?php echo $data['progress_percent']; ?>%</span>
                </div>
                <!-- Progress bar static for now -->
                <div class="progress">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $data['progress_percent']; ?>%" aria-valuenow="<?php echo $data['progress_percent']; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
        
        <ul class="lesson-list">
            <?php foreach ($data['lessons'] as $index => $lesson): ?>
                <li>
                    <a href="<?php echo BASE_URL; ?>/lesson/index/<?php echo $data['course']['id']; ?>/<?php echo $lesson['id']; ?>" 
                       class="lesson-item-link <?php echo ($data['current_lesson']['id'] == $lesson['id']) ? 'active' : ''; ?>">
                        <i class="far fa-circle lesson-status-icon"></i>
                        <div class="flex-grow-1">
                            <div class="small text-muted">Lesson <?php echo $index + 1; ?></div>
                            <div class="fw-bold"><?php echo $lesson['title']; ?></div>
                        </div>
                        <?php if($data['current_lesson']['id'] == $lesson['id']): ?>
                            <i class="fas fa-play text-primary ms-2"></i>
                        <?php endif; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Main Content: Video/Content -->
    <div class="lesson-content-area">
        <div class="container-fluid" style="max-width: 1000px;">
            <!-- Video Player -->
            <div class="video-player-container">
                <?php 
                $videoUrl = $data['current_lesson']['video_url'];
                if (filter_var($videoUrl, FILTER_VALIDATE_URL) && (strpos($videoUrl, 'youtube.com') !== false || strpos($videoUrl, 'youtu.be') !== false)): 
                    // Convert YouTube URL to Embed URL
                    $videoId = '';
                    if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $videoUrl, $match)) {
                        $videoId = $match[1];
                    }
                ?>
                    <iframe src="https://www.youtube.com/embed/<?php echo $videoId; ?>" allowfullscreen></iframe>
                <?php elseif (filter_var($videoUrl, FILTER_VALIDATE_URL) && strpos($videoUrl, BASE_URL) !== false):
                    // Internal Video URL (Uploaded)
                ?>
                    <video controls controlsList="nodownload">
                        <source src="<?php echo $videoUrl; ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                <?php else: ?>
                    <div class="d-flex align-items-center justify-content-center h-100 bg-dark text-white">
                        <div class="text-center">
                            <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
                            <p>Video format not supported or URL invalid.</p>
                            <a href="<?php echo $videoUrl; ?>" target="_blank" class="btn btn-sm btn-outline-light mt-2">Open Video Link</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="lesson-title-area">
                <div class="d-flex justify-content-between align-items-start">
                    <h1><?php echo $data['current_lesson']['title']; ?></h1>
                    
                    <!-- Navigation Buttons -->
                    <div class="btn-group">
                        <!-- Find previous Lesson -->
                         <?php 
                            $prevLessonId = null;
                            $nextLessonId = null;
                            $found = false;
                            foreach($data['lessons'] as $l) {
                                if($l['id'] == $data['current_lesson']['id']) {
                                    $found = true;
                                    continue;
                                }
                                if (!$found) $prevLessonId = $l['id'];
                                if ($found && !$nextLessonId) {
                                    $nextLessonId = $l['id'];
                                    break;
                                }
                            }
                         ?>

                        <?php if($prevLessonId): ?>
                        <a href="<?php echo BASE_URL; ?>/lesson/index/<?php echo $data['course']['id']; ?>/<?php echo $prevLessonId; ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-chevron-left me-1"></i> Previous
                        </a>
                        <?php else: ?>
                            <button class="btn btn-outline-secondary" disabled>Previous</button>
                        <?php endif; ?>

                        <?php if($nextLessonId): ?>
                        <a href="<?php echo BASE_URL; ?>/lesson/index/<?php echo $data['course']['id']; ?>/<?php echo $nextLessonId; ?>" class="btn btn-primary">
                            Next <i class="fas fa-chevron-right ms-1"></i>
                        </a>
                        <?php else: ?>
                            <button class="btn btn-primary" disabled>Next</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if (!empty($data['current_lesson']['content'])): ?>
            <div class="lesson-notes">
                <h3 class="mb-3">Lesson Notes</h3>
                <div class="text-muted">
                    <?php echo nl2br(htmlspecialchars($data['current_lesson']['content'])); ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/components/footer.php'; ?>

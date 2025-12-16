<?php require APPROOT . '/views/components/header.php'; ?>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>/views/dashboard/dashboard.css">

<div class="dashboard-container">
    <div class="container">
        <div class="dashboard-header">
            <div>
                <a href="<?php echo BASE_URL; ?>/dashboard/edit/<?php echo $data['course']['id']; ?>" class="text-muted text-decoration-none mb-2 d-inline-block">
                    <i class="fas fa-arrow-left me-1"></i> Back to Course
                </a>
                <h1 class="dashboard-title">Edit Lesson</h1>
                <p class="dashboard-subtitle"><?php echo $data['course']['title']; ?></p>
            </div>
        </div>

        <div class="form-card">
            <form action="<?php echo BASE_URL; ?>/lesson/update/<?php echo $data['lesson']['id']; ?>" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title" class="form-label">Lesson Title</label>
                    <input type="text" name="title" id="title" class="form-control <?php echo (!empty($data['title_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['lesson']['title']; ?>" placeholder="e.g. Introduction to HTML">
                    <span class="invalid-feedback"><?php echo $data['title_err'] ?? ''; ?></span>
                </div>

                <div class="form-group">
                    <label class="form-label">Video Content</label>
                    
                    <?php if($data['lesson']['video_url']): ?>
                    <div class="mb-2 p-2 bg-light rounded">
                        <small class="text-muted d-block">Current Video:</small>
                        <a href="<?php echo $data['lesson']['video_url']; ?>" target="_blank" class="text-break"><?php echo basename($data['lesson']['video_url']); ?></a>
                    </div>
                    <?php endif; ?>

                    <div class="nav nav-tabs mb-3" id="video-tabs" role="tablist">
                        <button class="nav-link active" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload" type="button" role="tab" aria-controls="upload" aria-selected="true" onclick="switchVideoInput('upload')">Upload New</button>
                        <button class="nav-link" id="url-tab" data-bs-toggle="tab" data-bs-target="#url" type="button" role="tab" aria-controls="url" aria-selected="false" onclick="switchVideoInput('url')">Video URL</button>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="upload" role="tabpanel" aria-labelledby="upload-tab">
                            <div class="file-upload" onclick="document.getElementById('video_file').click()">
                                <i class="fas fa-video fa-2x mb-2 text-muted"></i>
                                <p class="mb-0 text-muted">Click to upload video (MP4, WebM)</p>
                                <input type="file" name="video_file" id="video_file" style="display: none;" accept="video/mp4,video/webm,video/ogg" onchange="updateFileName(this)">
                                <p id="file-name" class="mt-2 text-primary"></p>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="url" role="tabpanel" aria-labelledby="url-tab">
                             <input type="url" name="video_url" id="video_url" class="form-control" value="<?php echo (filter_var($data['lesson']['video_url'], FILTER_VALIDATE_URL)) ? $data['lesson']['video_url'] : ''; ?>" placeholder="https://www.youtube.com/watch?v=...">
                        </div>
                    </div>
                    
                    <input type="hidden" name="video_source" id="video_source" value="upload">
                    <span class="text-danger d-block mt-2"><?php echo $data['video_url_err'] ?? ''; ?></span>
                </div>

                <div class="form-group">
                    <label for="order" class="form-label">Order Number</label>
                    <input type="number" name="order" id="order" class="form-control <?php echo (!empty($data['order_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['lesson']['order']; ?>" placeholder="1">
                    <span class="invalid-feedback"><?php echo $data['order_err'] ?? ''; ?></span>
                </div>

                <div class="form-group">
                    <label for="content" class="form-label">Lesson Content / Notes (Optional)</label>
                    <textarea name="content" id="content" class="form-control" placeholder="Add extra notes or resources for this lesson..."><?php echo $data['lesson']['content']; ?></textarea>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn-add-course text-center">Update Lesson</button>
                </div>
            </form>

            <script>
                function switchVideoInput(type) {
                    document.getElementById('video_source').value = type;
                }
                function updateFileName(input) {
                    if (input.files && input.files[0]) {
                        document.getElementById('file-name').innerText = input.files[0].name;
                    }
                }
            </script>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/components/footer.php'; ?>

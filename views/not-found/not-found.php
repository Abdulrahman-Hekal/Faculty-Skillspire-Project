<?php require APPROOT . '/views/components/header.php'; ?>

<!-- Inject Not Found Specific CSS -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/views/not-found/not-found.css">

<div class="not-found-container">
    <div class="not-found-content">
        <div class="illustration-box">
            <i class="fas fa-compass"></i>
        </div>
        <div class="error-code">404</div>
        <h1 class="not-found-title">Page Not Found</h1>
        <p class="not-found-text">
            Oops! The page you are looking for keeps exploring the universe. 
            It seems we can't find the page you're looking for.
        </p>
        <a href="<?php echo BASE_URL; ?>" class="btn-home">
            <i class="fas fa-home me-2"></i> Back to Home
        </a>
    </div>
</div>

<?php require APPROOT . '/views/components/footer.php'; ?>

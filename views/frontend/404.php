<?php
/**
 * 404 — Page Not Found
 */
?>
<section class="section" style="min-height: 60vh; display: flex; align-items: center;">
    <div class="container text-center">
        <p style="font-family: var(--font-serif); font-size: clamp(6rem, 15vw, 12rem); font-weight: 700; color: var(--color-gray-200); line-height: 1; margin-bottom: var(--space-md);">
            404
        </p>
        <h1 style="font-family: var(--font-serif); font-size: var(--text-3xl); margin-bottom: var(--space-md);">
            Page Not Found
        </h1>
        <p class="text-muted" style="font-size: var(--text-lg); max-width: 480px; margin: 0 auto var(--space-xl);">
            The page you're looking for doesn't exist or has been moved.
        </p>
        <a href="<?= url('/') ?>" class="btn btn-primary">Back to Homepage</a>
    </div>
</section>

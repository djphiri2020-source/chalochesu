<?php
// includes/hero_section.php
// This partial expects $hero_data and $hero_images to be set from header.php
?>
<section class="hero-section hero-slider">
    <?php if (!empty($hero_images)): ?>
        <?php foreach ($hero_images as $image): ?>
            <div class="slide" style="background-image: url('<?php echo BASE_URL; ?>/<?php echo htmlspecialchars($image['image_path']); ?>');"></div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="slide" style="background-image: url('<?php echo BASE_URL; ?>/assets/brand/environment1.jpg');"></div>
    <?php endif; ?>
    
    <div class="hero-overlay">
        <div class="container">
            <div class="hero-content" data-aos="fade-up">
                <h1><?php echo htmlspecialchars($hero_data['title'] ?? 'Welcome'); ?></h1>
                <p class="hero-tagline"><?php echo htmlspecialchars($hero_data['tagline'] ?? 'Sustainable Solutions'); ?></p>
                
                <?php if (!empty($hero_data['button1_text'])): ?>
                <div class="hero-buttons">
                    <a href="<?php echo BASE_URL . htmlspecialchars($hero_data['button1_url']); ?>" class="btn btn-primary btn-large">
                        <i class="<?php echo htmlspecialchars($hero_data['button1_icon']); ?>"></i> <?php echo htmlspecialchars($hero_data['button1_text']); ?>
                    </a>
                    <?php if (!empty($hero_data['button2_text'])): ?>
                    <a href="<?php echo BASE_URL . htmlspecialchars($hero_data['button2_url']); ?>" class="btn btn-secondary btn-large">
                        <i class="<?php echo htmlspecialchars($hero_data['button2_icon']); ?>"></i> <?php echo htmlspecialchars($hero_data['button2_text']); ?>
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
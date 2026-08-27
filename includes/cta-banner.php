<?php
// includes/cta-banner.php
if (!isset($pdo)) {
    require_once __DIR__ . '/../config/database.php';
}
$cta_settings = [];
try {
    $cta_stmt = $pdo->query("SELECT setting_key, setting_value FROM settings WHERE setting_key LIKE 'cta_%'");
    $cta_list = $cta_stmt->fetchAll();
    foreach ($cta_list as $setting) {
        $cta_settings[$setting['setting_key']] = $setting['setting_value'];
    }
} catch (Exception $e) {
    error_log("CTA Banner fetch error: " . $e->getMessage());
}
?>
<section class="cta-section" style="background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('<?php echo BASE_URL . '/' . ($cta_settings['cta_background_image'] ?? 'assets/brand/environment2.jpg'); ?>');">
    <div class="container">
        <div class="cta-content" data-aos="fade-up">
            <h2><?php echo htmlspecialchars($cta_settings['cta_title'] ?? 'Ready to Start Your Project?'); ?></h2>
            <p><?php echo htmlspecialchars($cta_settings['cta_text'] ?? 'Let\'s work together to achieve your sustainability goals. Contact us today for a consultation.'); ?></p>
            <div class="cta-buttons">
                <?php
                function render_cta_button($url, $text, $icon, $class) {
                    if (empty($text)) return; // Don't render button if text is empty
                    $final_url = htmlspecialchars($url);
                    $is_external_action = preg_match('/^(mailto:|tel:)/', $final_url);
                    if (!$is_external_action && !preg_match('/^(https:|http:)/', $final_url) && $final_url[0] !== '#') {
                        $final_url = BASE_URL . $final_url;
                    }
                    $icon_html = !empty($icon) ? '<i class="' . htmlspecialchars($icon) . '"></i> ' : '';
                    echo '<a href="' . $final_url . '" class="' . $class . '"' . ($is_external_action ? ' target="_blank"' : '') . '>' . $icon_html . htmlspecialchars($text) . '</a>';
                }

                render_cta_button(
                    $cta_settings['cta_button_url'] ?? '#',
                    $cta_settings['cta_button_text'] ?? '',
                    $cta_settings['cta_button_icon'] ?? '',
                    'btn btn-primary btn-large'
                );

                render_cta_button(
                    $cta_settings['cta_button2_url'] ?? '#',
                    $cta_settings['cta_button2_text'] ?? '',
                    $cta_settings['cta_button2_icon'] ?? '',
                    'btn btn-secondary btn-large'
                );
                ?>
            </div>
        </div>
    </div>
</section>
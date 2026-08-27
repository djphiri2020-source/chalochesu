<?php
// includes/footer.php

// Ensure constants are loaded if not already
if (file_exists(__DIR__ . '/../config/constants.php')) {
    require_once __DIR__ . '/../config/constants.php';
}

// Fetch settings if they haven't been fetched yet (e.g., if footer is used alone)
if (!isset($settings)) {
    require_once __DIR__ . '/../config/database.php';
    $settings = [];
    $stmt = $pdo->query("SELECT setting_key, setting_value FROM settings");
    if ($stmt) {
        $settings_list = $stmt->fetchAll();
        foreach ($settings_list as $setting) {
            $settings[$setting['setting_key']] = $setting['setting_value'];
        }
    }
    $facebook_url = !empty($settings['facebook_url']) ? $settings['facebook_url'] : FACEBOOK_URL;
    $linkedin_url = !empty($settings['linkedin_url']) ? $settings['linkedin_url'] : LINKEDIN_URL;
    $instagram_url = !empty($settings['instagram_url']) ? $settings['instagram_url'] : INSTAGRAM_URL;
    $twitter_url = !empty($settings['twitter_url']) ? $settings['twitter_url'] : TWITTER_URL;
}

$office_hours_mon_sat = !empty($settings['office_hours_mon_sat']) ? $settings['office_hours_mon_sat'] : OFFICE_HOURS_MON_SAT;
$office_hours_sunday = !empty($settings['office_hours_sunday']) ? $settings['office_hours_sunday'] : OFFICE_HOURS_SUNDAY;

// Fetch footer documents
$footer_documents = [];
try {
    $footer_docs_stmt = $pdo->query("SELECT * FROM footer_documents ORDER BY display_order ASC, name ASC");
    $footer_documents = $footer_docs_stmt->fetchAll();
} catch (Exception $e) {
    error_log("Footer documents fetch error: " . $e->getMessage());
}
?>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-grid">
                <!-- About Column -->
                <div class="footer-column">
                    <img src="<?php echo BASE_URL; ?>/assets/brand/logo4.png" alt="<?php echo SITE_NAME; ?> Logo" class="footer-logo-img">
                    <p class="footer-description">
                        Delivering innovative, reliable, and sustainable resource management
                        solutions for a better future.
                    </p>
                    <div class="footer-social">
                        <a href="<?php echo htmlspecialchars($facebook_url); ?>" target="_blank" class="social-icon">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="<?php echo htmlspecialchars($linkedin_url); ?>" target="_blank" class="social-icon">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="<?php echo htmlspecialchars($instagram_url); ?>" target="_blank" class="social-icon">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="<?php echo htmlspecialchars($twitter_url); ?>" target="_blank" class="social-icon">
                            <i class="fab fa-x-twitter"></i>
                        </a>
                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', CONTACT_PHONE); ?>" target="_blank" class="social-icon">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="footer-column">
                    <h4 class="footer-title">Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="<?php echo BASE_URL; ?>/pages/index.php">Home</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/pages/services.php">Services</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/pages/products.php">Products</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/pages/about.php">About Us</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/pages/partners.php">Partners</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/pages/contact.php">Contact</a></li>
                    </ul>
                </div>

                <!-- Documents Column -->
                <div class="footer-column">
                    <h4 class="footer-title">Documents</h4>
                    <ul class="footer-links">
                        <?php if (empty($footer_documents)): ?>
                            <li><a href="#">Company Profile (soon)</a></li>
                        <?php else: ?>
                            <?php foreach ($footer_documents as $doc): ?>
                                <li><a href="<?php echo BASE_URL; ?>/<?php echo htmlspecialchars($doc['file_path']); ?>" download><i class="<?php echo htmlspecialchars($doc['icon']); ?>"></i> <?php echo htmlspecialchars($doc['name']); ?></a></li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="footer-column">
                    <h4 class="footer-title">Contact Us</h4>
                    <div class="contact-info-footer">
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <strong>Address</strong>
                                <p><?php echo CONTACT_ADDRESS; ?></p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <div>
                                <strong>Phone</strong>
                                <p><?php echo CONTACT_PHONE; ?></p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <strong>Email</strong>
                                <p><?php echo CONTACT_EMAIL; ?></p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-clock"></i>
                            <div>
                                <strong>Office Hours</strong>
                                <p>Mon - Sat: <?php echo htmlspecialchars($office_hours_mon_sat); ?></p>
                                <p>Sunday: <?php echo htmlspecialchars($office_hours_sunday); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Newsletter -->
                <div class="footer-column">
                    <h4 class="footer-title">Stay Updated</h4>
                    <p class="newsletter-description">
                        Subscribe to our newsletter for the latest on our products,
                        services, and tech insights.
                    </p>
                    <form class="newsletter-form" id="newsletterForm">
                        <div class="form-group">
                            <input type="email" name="email" placeholder="Your email address" required>
                            <button type="submit" class="btn-newsletter">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                        <div class="form-message" id="newsletterMessage"></div>
                    </form>
                    <div class="payment-methods">
                        <p>We Accept:</p>
                        <div class="payment-icons">
                            <i class="fab fa-cc-visa"></i>
                            <i class="fab fa-cc-mastercard"></i>
                            <i class="fab fa-cc-paypal"></i>
                            <i class="fab fa-cc-amex"></i>
                            <i class="fas fa-university"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="copyright">
                    <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
                </div>
                <div class="footer-bottom-links">
                    <a href="<?php echo BASE_URL; ?>/pages/terms-and-conditions.php">Terms & Conditions</a>
                    <a href="<?php echo BASE_URL; ?>/pages/privacy-policy.php">Privacy Policy</a>
                    <a href="<?php echo BASE_URL; ?>/pages/corporate-governance.php">Corporate Governance</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button class="back-to-top" id="backToTop">
        <i class="fas fa-chevron-up"></i>
    </button>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/main.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/form-validation.js"></script>
    
    <!-- Initialize AOS -->
    <script>
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });

        // Newsletter Subscription AJAX
        document.getElementById('newsletterForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            const form = this;
            const emailInput = form.querySelector('input[name="email"]');
            const messageDiv = document.getElementById('newsletterMessage');
            const submitButton = form.querySelector('button[type="submit"]');

            const formData = new FormData(form);
            
            // Disable button and show loading state
            submitButton.disabled = true;
            messageDiv.textContent = 'Subscribing...';
            messageDiv.style.color = '#333';

            fetch('<?php echo BASE_URL; ?>/ajax/subscribe_newsletter.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                messageDiv.textContent = data.message;
                if (data.success) {
                    messageDiv.style.color = 'var(--primary-green)';
                    emailInput.value = ''; // Clear input on success
                } else {
                    messageDiv.style.color = '#dc3545'; // Error color
                }
            })
            .catch(error => {
                console.error('Error:', error);
                messageDiv.textContent = 'An error occurred. Please try again.';
                messageDiv.style.color = '#dc3545';
            })
            .finally(() => {
                submitButton.disabled = false; // Re-enable button
            });
        });
    </script>
    
</body>
</html>

<style>
    .footer-logo-img {
        height: 80px;
        width: auto;
        margin-bottom: 1rem;
    }
</style>
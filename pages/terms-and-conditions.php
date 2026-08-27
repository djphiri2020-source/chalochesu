<?php
require_once '../includes/header.php';
$page_title = "Terms & Conditions - " . SITE_NAME;
?>

<!-- Hero Section -->
<section class="hero-section hero-slider">
    <div class="slide" style="background-image: url('<?php echo BASE_URL; ?>assets/brand/environment4.jpg');"></div>
    <div class="hero-overlay">
        <div class="container">
            <div class="hero-content" data-aos="fade-up">
                <h1>Terms & Conditions</h1>
                <p class="hero-tagline">Please Read Our Terms of Service Carefully</p>
            </div>
        </div>
    </div>
</section>

<!-- Policy Content Section -->
<section class="section">
    <div class="container content-section">
        <div class="policy-content" data-aos="fade-up">
            <p><strong>Last Updated: <?php echo date('F j, Y'); ?></strong></p>

            <h3>1. Agreement to Terms</h3>
            <p>By using our website (the "Site"), you agree to be bound by these Terms and Conditions. If you do not agree, you must not use our Site. We reserve the right to make changes to these Terms and Conditions at any time and for any reason. We will alert you about any changes by updating the "Last Updated" date of these Terms and Conditions.</p>

            <h3>2. Intellectual Property Rights</h3>
            <p>Unless otherwise indicated, the Site is our proprietary property and all source code, databases, functionality, software, website designs, audio, video, text, photographs, and graphics on the Site (collectively, the "Content") and the trademarks, service marks, and logos contained therein (the "Marks") are owned or controlled by us or licensed to us, and are protected by copyright and trademark laws.</p>

            <h3>3. User Representations</h3>
            <p>By using the Site, you represent and warrant that: (1) you have the legal capacity and you agree to comply with these Terms and Conditions; (2) you will not access the Site through automated or non-human means, whether through a bot, script, or otherwise; (3) you will not use the Site for any illegal or unauthorized purpose; and (4) your use of the Site will not violate any applicable law or regulation.</p>

            <h3>4. Prohibited Activities</h3>
            <p>You may not access or use the Site for any purpose other than that for which we make the Site available. The Site may not be used in connection with any commercial endeavors except those that are specifically endorsed or approved by us.</p>

            <h3>5. Governing Law</h3>
            <p>These Terms and Conditions and your use of the Site are governed by and construed in accordance with the laws of the Republic of Zambia applicable to agreements made and to be entirely performed within the Republic of Zambia, without regard to its conflict of law principles.</p>

            <h3>6. Disclaimer</h3>
            <p>The Site is provided on an as-is and as-available basis. You agree that your use of the site and our services will be at your sole risk. To the fullest extent permitted by law, we disclaim all warranties, express or implied, in connection with the site and your use thereof, including, without limitation, the implied warranties of merchantability, fitness for a particular purpose, and non-infringement.</p>

            <h3>7. Contact Us</h3>
            <p>In order to resolve a complaint regarding the Site or to receive further information regarding use of the Site, please contact us at:</p>
            <p>
                <strong><?php echo SITE_NAME; ?></strong><br>
                <?php echo CONTACT_ADDRESS; ?><br>
                Email: <?php echo CONTACT_EMAIL; ?><br>
                Phone: <?php echo CONTACT_PHONE; ?>
            </p>
        </div>
    </div>
</section>

<style>
    .content-section {
        max-width: 800px;
        margin: 0 auto;
    }
    .policy-content h3 {
        margin-top: 2rem;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #eee;
    }
    .policy-content p {
        line-height: 1.8;
        text-align: justify;
    }
</style>

<?php
require_once '../includes/cta-banner.php';
require_once '../includes/footer.php';
?>
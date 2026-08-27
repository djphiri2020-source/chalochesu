<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
$page_title = "Privacy Policy - " . SITE_NAME;
?>

<!-- Hero Section -->
<section class="hero-section hero-slider">
    <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1528747045269-390a322c39b1?auto=format&fit=crop&w=1920&q=80');"></div>
    <div class="hero-overlay">
        <div class="container">
            <div class="hero-content" data-aos="fade-up">
                <h1>Privacy Policy</h1>
                <p class="hero-tagline">Your Privacy is Important to Us</p>
            </div>
        </div>
    </div>
</section>

<!-- Policy Content Section -->
<section class="section">
    <div class="container content-section">
        <div class="policy-content" data-aos="fade-up">
            <p><strong>Last Updated: <?php echo date('F j, Y'); ?></strong></p>

            <h3>1. Introduction</h3>
            <p>Welcome to Chalochesu ("we," "our," or "us"). We are committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website, including any other media form, media channel, mobile website, or mobile application related or connected thereto (collectively, the "Site"). Please read this privacy policy carefully. If you do not agree with the terms of this privacy policy, please do not access the site.</p>

            <h3>2. Information We Collect</h3>
            <p>We may collect personally identifiable information, such as your name, shipping address, email address, and telephone number, and demographic information, such as your age, gender, hometown, and interests, that you voluntarily give to us when you register with the Site or when you choose to participate in various activities related to the Site, such as online chat and message boards.</p>

            <h3>3. How We Use Your Information</h3>
            <p>Having accurate information about you permits us to provide you with a smooth, efficient, and customized experience. Specifically, we may use information collected about you via the Site to:</p>
            <ul>
                <li>Respond to your inquiries and offer support for your project.</li>
                <li>Email you regarding your account or order.</li>
                <li>Send you a newsletter.</li>
                <li>Request feedback and contact you about your use of the Site.</li>
                <li>Resolve disputes and troubleshoot problems.</li>
            </ul>

            <h3>4. Disclosure of Your Information</h3>
            <p>We may share information we have collected about you in certain situations. Your information may be disclosed as follows: by law or to protect rights, if we believe the release of information about you is necessary to respond to legal process, to investigate or remedy potential violations of our policies, or to protect the rights, property, and safety of others.</p>

            <h3>5. Security of Your Information</h3>
            <p>We use administrative, technical, and physical security measures to help protect your personal information. While we have taken reasonable steps to secure the personal information you provide to us, please be aware that despite our efforts, no security measures are perfect or impenetrable, and no method of data transmission can be guaranteed against any interception or other type of misuse.</p>

            <h3>6. Contact Us</h3>
            <p>If you have questions or comments about this Privacy Policy, please contact us at:</p>
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
    .policy-content p, .policy-content ul {
        line-height: 1.8;
        text-align: justify;
    }
    .policy-content ul {
        padding-left: 20px;
        margin-bottom: 1rem;
    }
</style>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/cta-banner.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php';
?>
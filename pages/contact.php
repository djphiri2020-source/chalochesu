<?php
// pages/contact.php

// Helper function for escaping HTML output to prevent XSS
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
require_once '../includes/header.php';
$page_title = "Contact Us - " . SITE_NAME;

// Form submission handling
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Form validation
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    // Basic validation
    $errors = [];
    if (empty($name)) $errors[] = 'Name is required';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required';
    if (empty($subject)) $errors[] = 'Subject is required';
    if (empty($message)) $errors[] = 'Message is required';
    
    if (empty($errors)) {
        try {
            require_once '../config/database.php';
            $stmt = $pdo->prepare(
                "INSERT INTO contact_submissions (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->execute([$name, $email, $phone, $subject, $message]);
            
            $success_message = "Thank you for your message, " . e($name) . "! We'll get back to you shortly.";
            $_POST = []; // Clear form on success
        } catch (PDOException $e) {
            error_log("Contact form submission error: " . $e->getMessage());
            $error_message = "Sorry, there was a problem sending your message. Please try again later.";
        }
    } else {
        $error_message = implode('<br>', $errors);
    }
}

?>

<!-- Contact Hero -->
<?php require_once '../includes/hero_section.php'; ?>

<!-- Contact Information -->
<section class="section">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Contact Information</h2>
            <p>Our team is ready to assist you with any inquiries.</p>
        </div>
        
        <div class="grid-3" data-aos="fade-up">
            <div class="text-center contact-card">
                <div class="contact-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h4>Our Location</h4>
                <p><?php echo CONTACT_ADDRESS; ?></p>
                <a href="https://maps.google.com/?q=<?php echo urlencode(CONTACT_ADDRESS); ?>" 
                   target="_blank" class="btn btn-outline btn-sm">
                    <i class="fas fa-directions"></i> Get Directions
                </a>
            </div>
            
            <div class="text-center contact-card">
                <div class="contact-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <h4>Phone & WhatsApp</h4>
                <p><?php echo CONTACT_PHONE; ?></p>
                <p>Mon-Fri, 9am-5pm</p>
                <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', CONTACT_PHONE); ?>" target="_blank" class="btn btn-outline btn-sm">
                    <i class="fab fa-whatsapp"></i> WhatsApp Us
                </a>
            </div>
            
            <div class="text-center contact-card">
                <div class="contact-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h4>Email Us</h4>
                <p><?php echo CONTACT_EMAIL; ?></p>
                <p>Response within 24 hours</p>
                <a href="mailto:<?php echo CONTACT_EMAIL; ?>" class="btn btn-outline btn-sm">
                    <i class="fas fa-paper-plane"></i> Send Email
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Contact Form -->
<section class="section bg-light" id="contact-form">
    <div class="container">
        <div class="grid-2">
            <div data-aos="fade-right">
                <h2>Send Us a Message</h2>
                <p>Fill out the form below and our team will get back to you as soon as possible.</p>
                
                <!-- Success/Error Messages -->
                <?php if ($success_message): ?>
                    <div class="alert alert-success">
                        <?php echo $success_message; // Already escaped at creation ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($error_message): ?>
                    <div class="alert alert-error">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="" class="contact-form">
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" class="form-control" 
                               value="<?php echo e($_POST['name'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" class="form-control" 
                               value="<?php echo e($_POST['email'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone Number (Optional)</label>
                        <input type="tel" id="phone" name="phone" class="form-control" 
                               value="<?php echo e($_POST['phone'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="subject">Subject *</label>
                        <input type="text" id="subject" name="subject" class="form-control" 
                               value="<?php echo e($_POST['subject'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea id="message" name="message" class="form-control" rows="5" required><?php echo e($_POST['message'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-large">
                            <i class="fas fa-paper-plane"></i> Send Message
                        </button>
                    </div>
                    
                    <p class="form-note">
                        <small>* Required fields. By submitting this form, you agree to our 
                        <a href="<?php echo BASE_URL; ?>/pages/privacy-policy.php">Privacy Policy</a>.</small>
                    </p>
                </form>
            </div>
            
            <div data-aos="fade-left">
                <!-- Map -->
                <div class="map-container">
                    <h3>Our Office Location</h3>
                    <div class="map-placeholder">
                        <!-- In production, replace with Google Maps embed -->
                        <div style="width: 100%; height: 400px; background-color: #f0f0f0; border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center;">
                            <div class="text-center">
                                <i class="fas fa-map-marker-alt fa-3x" style="color: var(--sunset-orange); margin-bottom: 1rem;"></i>
                                <p>Interactive Map Location</p>
                                <p><?php echo CONTACT_ADDRESS; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- FAQ -->
                <div class="faq-section">
                    <h3>Frequently Asked Questions</h3>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <h4>What is an Environmental Impact Assessment (EIA)?</h4>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>An Environmental Impact Assessment (EIA) is a critical tool used to predict and evaluate the environmental, social, and economic consequences of a proposed project before any decisions are made. The goal is to provide decision-makers with a clear understanding of the potential impacts, allowing for the implementation of mitigation measures to minimize negative effects and enhance positive ones. Our team manages the entire EIA process, from scoping to stakeholder engagement and final report submission. Visit our <a href="<?php echo BASE_URL; ?>/pages/services.php">services page</a> for more details.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <h4>How long does a feasibility study take?</h4>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>The timeline for a feasibility study is highly dependent on the project's scale and complexity. A preliminary or pre-feasibility study might take 2-4 weeks, providing an initial overview of viability. A comprehensive, bankable feasibility study for a large-scale project can take anywhere from 3 to 6 months or more, as it involves in-depth technical, financial, and market analysis. We recommend <a href="#contact-form">contacting us</a> with your project details for a more accurate timeline.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <h4>What sectors do you work with?</h4>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Our team has extensive experience across a wide array of sectors. We regularly provide consulting services for projects in Mining, Energy (including renewables), Agriculture, Telecommunications, Infrastructure, Conservation & Tourism, Forestry, and FMCG. We also collaborate closely with the Public Sector on national and regional development initiatives. Our adaptable methodologies allow us to deliver value to any industry where environmental and social considerations are paramount.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <h4>Why is sustainability reporting important?</h4>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>In today's market, Environmental, Social, and Governance (ESG) or sustainability reporting is crucial for building stakeholder trust and ensuring long-term viability. It allows your organization to transparently communicate its impact and performance beyond financial metrics. This process helps attract investors, improve brand reputation, identify operational efficiencies, manage risks, and meet increasing regulatory requirements. It is a powerful tool for demonstrating a commitment to responsible business practices.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Additional Contact CSS -->
<style>
    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--spacing-lg);
    }
    
    .contact-card {
        background: var(--white);
        padding: 2rem;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-md);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .contact-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }
    
    .contact-icon {
        font-size: 2.5rem;
        color: var(--sunset-orange);
        margin-bottom: 1rem;
    }
    
    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    
    .map-placeholder {
        margin-bottom: 2rem;
    }
    
    .faq-section {
        margin-top: 2rem;
    }
    
    .faq-item {
        border: 1px solid var(--light-gray);
        border-radius: var(--radius-md);
        margin-bottom: 1rem;
        overflow: hidden;
    }
    
    .faq-question {
        padding: 1rem;
        background: var(--white); /* Default background */
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
    }
    
    .faq-question h4 {
        margin: 0;
        font-size: 1rem; /* Keep font size */
        font-weight: 600; /* Make question bolder */
        color: var(--primary-dark); /* Ensure dark text */
    }
    
    .faq-answer {
        padding: 0 1.5rem; /* More padding */
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease, padding 0.3s ease; /* Smooth transition for padding */
        background: var(--off-white);
    }
    
    .faq-item.active .faq-answer {
        padding: 1rem;
        max-height: 200px;
    }
    
    .faq-item.active .faq-question {
        background: var(--light-gray); /* Slight background change when active */
    }

    .faq-item.active .faq-question i {
        transform: rotate(180deg);
    }
    
    .faq-question i {
        transition: transform 0.3s ease;
        color: var(--primary-green); /* Use primary green for icon */
    }
    
    .form-note {
        font-size: 0.9rem;
        color: var(--dark-gray);
        margin-top: 1rem;
    }
    
    @media (max-width: 768px) {
        .grid-2 {
            grid-template-columns: 1fr;
        }
        
        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- FAQ JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const faqItems = document.querySelectorAll('.faq-item');
        
        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question');
            
            question.addEventListener('click', () => {
                // Close other FAQ items
                faqItems.forEach(otherItem => {
                    if (otherItem !== item && otherItem.classList.contains('active')) {
                        otherItem.classList.remove('active');
                    }
                });
                
                // Toggle current item
                item.classList.toggle('active');
            });
        }
    });
</script>

<?php
// Include CTA Section
require_once '../includes/cta-banner.php';

// Include Footer
require_once '../includes/footer.php';
?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
$page_title = "Corporate Governance - " . SITE_NAME;
?>

<!-- Hero Section -->
<section class="hero-section hero-slider">
    <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1556761175-5973dc0f32e7?auto=format&fit=crop&w=1920&q=80');"></div>
    <div class="hero-overlay">
        <div class="container">
            <div class="hero-content" data-aos="fade-up">
                <h1>Corporate Governance</h1>
                <p class="hero-tagline">Our Commitment to Integrity, Transparency, and Accountability</p>
            </div>
        </div>
    </div>
</section>

<!-- Governance Content Section -->
<section class="section">
    <div class="container content-section">
        <div class="section-title" data-aos="fade-up">
            <h2>Our Governance Framework</h2>
            <p>At Chalochesu, we uphold transparent and ethical governance practices, ensuring accountability, fairness, and responsible decision-making in all our operations.</p>
        </div>

        <div class="governance-content" data-aos="fade-up">
            <h3>1. Introduction & Commitment</h3>
            <p>Chalochesu is committed to the highest standards of corporate governance, which we believe are essential for our long-term success and for maintaining the trust of our clients, partners, employees, and stakeholders. Our governance framework is designed to ensure effective and ethical leadership, sound decision-making, and appropriate monitoring of compliance and performance.</p>

            <h3>2. Ethical Conduct and Code of Business</h3>
            <p>We conduct our business with unwavering integrity. All directors, officers, and employees are expected to act ethically and in accordance with our Code of Business Conduct. This code outlines our policies on conflicts of interest, confidentiality, fair dealing with stakeholders, and compliance with all applicable laws and regulations.</p>

            <h3>3. Board of Directors' Role and Responsibilities</h3>
            <p>The Board of Directors is responsible for overseeing the strategic direction and management of the company. Key responsibilities include reviewing and approving strategic plans, ensuring effective risk management, monitoring financial performance, and ensuring the company's operations are conducted in a sustainable and ethical manner.</p>

            <h3>4. Transparency and Disclosure</h3>
            <p>We are committed to providing timely, accurate, and transparent information to our stakeholders. We ensure that all disclosures are fair, complete, and compliant with legal and regulatory requirements. Our financial reporting follows internationally recognized accounting standards.</p>

            <h3>5. Risk Management</h3>
            <p>Chalochesu has a comprehensive risk management framework to identify, assess, and mitigate risks that could impact our business. The Board of Directors, through its committees, oversees this framework to ensure that risks are managed effectively to protect and enhance stakeholder value.</p>

            <h3>6. Stakeholder Engagement</h3>
            <p>We value our relationships with all stakeholders, including clients, employees, suppliers, and the communities in which we operate. We are committed to open and honest communication and to considering their interests in our decision-making processes.</p>
        </div>
    </div>
</section>

<style>
    .content-section {
        max-width: 800px;
        margin: 0 auto;
    }
    .governance-content h3 {
        margin-top: 2rem;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--primary-green);
    }
    .governance-content p {
        line-height: 1.8;
        text-align: justify;
    }
</style>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/cta-banner.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php';
?>
<?php
$page_title = "Chalo Chesu | Sustainability, Environment & Natural Resource Solutions";
require_once '../includes/header.php';
require_once '../config/database.php';

$featured_services = [];
$core_values = [];
$stakeholders = [];
$sdgs = [];
$testimonial = null;
try { $featured_services = $pdo->query("SELECT name, description, featured_image FROM services WHERE is_featured = 1 ORDER BY created_at DESC LIMIT 3")->fetchAll(); } catch (PDOException $e) { error_log($e->getMessage()); }
try { $core_values = $pdo->query("SELECT * FROM core_values ORDER BY display_order ASC LIMIT 4")->fetchAll(); } catch (PDOException $e) { error_log($e->getMessage()); }
try { $stakeholders = $pdo->query("SELECT * FROM stakeholders ORDER BY display_order ASC")->fetchAll(); } catch (PDOException $e) { error_log($e->getMessage()); }
try { $sdgs = $pdo->query("SELECT * FROM sdgs ORDER BY display_order ASC")->fetchAll(); } catch (PDOException $e) { error_log($e->getMessage()); }
try { $testimonial = $pdo->query("SELECT author_name, author_position, author_image, content FROM testimonials WHERE is_approved = 1 ORDER BY created_at DESC LIMIT 1")->fetch(); } catch (PDOException $e) { error_log($e->getMessage()); }
?>
<?php require_once '../includes/hero_section.php'; ?>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/chalochesu-home-v2.css">

<section class="cc-section cc-intro">
  <div class="cc-container cc-intro-grid">
    <div data-aos="fade-up"><span class="cc-eyebrow">SUSTAINABILITY IS OUR BUSINESS</span><h2>Turning environmental challenges into informed, sustainable decisions.</h2></div>
    <div class="cc-intro-copy" data-aos="fade-up" data-aos-delay="100"><p>Chalo Chesu brings together environmental expertise, engineering, research and sustainability thinking to help organisations make better decisions about people, projects and natural resources.</p><a href="<?php echo BASE_URL; ?>/pages/about.php" class="cc-text-link">Discover Chalo Chesu <i class="fas fa-arrow-right"></i></a></div>
  </div>
</section>

<section class="cc-section cc-pathways">
  <div class="cc-container">
    <div class="cc-section-heading" data-aos="fade-up"><div><span class="cc-eyebrow">START WITH THE CHALLENGE</span><h2>What are you trying to solve?</h2></div><p>Explore the areas where our multidisciplinary expertise can support your project.</p></div>
    <div class="cc-pathway-grid">
      <a class="cc-pathway" href="<?php echo BASE_URL; ?>/pages/services.php" data-aos="fade-up"><span class="cc-number">01</span><i class="fas fa-leaf"></i><h3>Environmental &amp; Social</h3><p>Assessment, compliance, monitoring and responsible project development.</p><span class="cc-arrow">Explore <i class="fas fa-arrow-right"></i></span></a>
      <a class="cc-pathway" href="<?php echo BASE_URL; ?>/pages/services.php" data-aos="fade-up" data-aos-delay="75"><span class="cc-number">02</span><i class="fas fa-drafting-compass"></i><h3>Engineering &amp; Development</h3><p>Practical technical solutions that connect feasibility, design and delivery.</p><span class="cc-arrow">Explore <i class="fas fa-arrow-right"></i></span></a>
      <a class="cc-pathway" href="<?php echo BASE_URL; ?>/pages/services.php" data-aos="fade-up" data-aos-delay="150"><span class="cc-number">03</span><i class="fas fa-cloud-sun"></i><h3>Climate &amp; Sustainability</h3><p>Strategies that build resilience, reduce risk and create long-term value.</p><span class="cc-arrow">Explore <i class="fas fa-arrow-right"></i></span></a>
      <a class="cc-pathway" href="<?php echo BASE_URL; ?>/pages/research.php" data-aos="fade-up" data-aos-delay="225"><span class="cc-number">04</span><i class="fas fa-chart-line"></i><h3>Research, Data &amp; Advisory</h3><p>Evidence and insight that turn complex information into clearer decisions.</p><span class="cc-arrow">Explore <i class="fas fa-arrow-right"></i></span></a>
    </div>
  </div>
</section>

<section class="cc-section cc-services">
  <div class="cc-container">
    <div class="cc-section-heading" data-aos="fade-up"><div><span class="cc-eyebrow">OUR EXPERTISE</span><h2>Solutions built for real-world impact.</h2></div><a href="<?php echo BASE_URL; ?>/pages/services.php" class="cc-outline-btn">View all services <i class="fas fa-arrow-right"></i></a></div>
    <?php if ($featured_services): ?><div class="cc-service-grid">
      <?php foreach ($featured_services as $i => $service): $desc = strip_tags($service['description'] ?? ''); ?>
      <article class="cc-service-card" data-aos="fade-up" data-aos-delay="<?php echo $i * 100; ?>"><div class="cc-service-image"><img src="<?php echo BASE_URL; ?>/<?php echo htmlspecialchars($service['featured_image'] ?? 'assets/brand/placeholder.jpg'); ?>" alt="<?php echo htmlspecialchars($service['name']); ?>" loading="lazy"></div><div class="cc-service-body"><span class="cc-card-index">0<?php echo $i + 1; ?></span><h3><?php echo htmlspecialchars($service['name']); ?></h3><p><?php echo htmlspecialchars(mb_strimwidth($desc, 0, 145, '…')); ?></p><a href="<?php echo BASE_URL; ?>/pages/services.php">Learn more <i class="fas fa-arrow-right"></i></a></div></article>
      <?php endforeach; ?></div><?php endif; ?>
  </div>
</section>

<section class="cc-section cc-sectors">
  <div class="cc-container">
    <div class="cc-section-heading" data-aos="fade-up"><div><span class="cc-eyebrow">SECTORS &amp; STAKEHOLDERS</span><h2>Expertise that meets you where you are.</h2></div><p>From public institutions to private enterprise and development partners, we adapt our approach to the context.</p></div>
    <?php if ($stakeholders): ?><div class="cc-sector-list" data-aos="fade-up"><?php foreach ($stakeholders as $stakeholder): ?><div class="cc-sector-item"><i class="<?php echo htmlspecialchars($stakeholder['icon']); ?>"></i><span><?php echo htmlspecialchars($stakeholder['name']); ?></span></div><?php endforeach; ?></div><?php endif; ?>
  </div>
</section>

<section class="cc-section cc-approach"><div class="cc-container cc-approach-grid"><div class="cc-approach-statement" data-aos="fade-right"><span class="cc-eyebrow">WHY CHALO CHESU</span><h2>Technical depth. Local context. Sustainable outcomes.</h2><p>Our multidisciplinary approach brings together the perspectives needed to understand complex environmental and development challenges.</p><a href="<?php echo BASE_URL; ?>/pages/about.php" class="cc-text-link">Our approach <i class="fas fa-arrow-right"></i></a></div><div class="cc-values" data-aos="fade-left"><?php foreach ($core_values as $value): ?><div class="cc-value"><span><i class="<?php echo htmlspecialchars($value['icon']); ?>"></i></span><div><h3><?php echo htmlspecialchars($value['title']); ?></h3><p><?php echo htmlspecialchars($value['description']); ?></p></div></div><?php endforeach; ?></div></div></section>

<section class="cc-section cc-insights"><div class="cc-container cc-insights-grid"><div data-aos="fade-up"><span class="cc-eyebrow">KNOWLEDGE &amp; INSIGHT</span><h2>Better decisions start with better information.</h2><p>Explore our research, perspectives and practical knowledge on sustainability, environmental management and responsible development.</p><a href="<?php echo BASE_URL; ?>/pages/research.php" class="cc-outline-btn">Explore research <i class="fas fa-arrow-right"></i></a></div><div class="cc-insight-panel" data-aos="fade-up" data-aos-delay="100"><div class="cc-insight-orbit"></div><span>RESEARCH</span><strong>Evidence → Insight → Action</strong><p>Making complex environmental and sustainability issues easier to understand and act on.</p></div></div></section>

<?php if ($testimonial): ?><section class="cc-section cc-testimonial"><div class="cc-container" data-aos="fade-up"><span class="cc-eyebrow">CLIENT PERSPECTIVE</span><blockquote>“<?php echo htmlspecialchars($testimonial['content']); ?>”</blockquote><div class="cc-testimonial-author"><img src="<?php echo BASE_URL; ?>/<?php echo htmlspecialchars($testimonial['author_image'] ?? 'assets/brand/placeholder-avatar.png'); ?>" alt="<?php echo htmlspecialchars($testimonial['author_name']); ?>" loading="lazy"><div><strong><?php echo htmlspecialchars($testimonial['author_name']); ?></strong><span><?php echo htmlspecialchars($testimonial['author_position']); ?></span></div></div></div></section><?php endif; ?>

<section class="cc-section cc-sdg"><div class="cc-container"><div class="cc-section-heading" data-aos="fade-up"><div><span class="cc-eyebrow">OUR COMMITMENT</span><h2>Contributing to a more sustainable future.</h2></div><p><?php echo htmlspecialchars($settings['homepage_sdg_text'] ?? 'We connect our work to the Sustainable Development Goals through environmental stewardship, responsible development and capacity building.'); ?></p></div><?php if ($sdgs): ?><div class="cc-sdg-row" data-aos="fade-up"><?php foreach ($sdgs as $sdg): ?><a href="<?php echo htmlspecialchars($sdg['link_url'] ?? '#'); ?>" target="_blank" rel="noopener noreferrer"><img src="<?php echo BASE_URL; ?>/<?php echo htmlspecialchars($sdg['logo_path']); ?>" alt="<?php echo htmlspecialchars($sdg['name']); ?>" loading="lazy"></a><?php endforeach; ?></div><?php endif; ?></div></section>

<section class="cc-final-cta"><div class="cc-container" data-aos="fade-up"><span class="cc-eyebrow">LET’S WORK TOGETHER</span><h2>Have a sustainability challenge?</h2><p>Tell us what you're working on. We'll help you identify the right path forward.</p><a href="<?php echo BASE_URL; ?>/pages/contact.php" class="cc-cta-btn">Start a conversation <i class="fas fa-arrow-right"></i></a></div></section>

<?php require_once '../includes/cta-banner.php'; require_once '../includes/footer.php'; ?>

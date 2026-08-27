<?php
// pages/photography.php
$page_title = "Wildlife Photography Safaris - Professional Guidance";
require_once '../includes/header.php';
?>

<!-- Photography Hero -->
<section class="hero-section" style="background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1516483638261-f4dbaf036963?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');">
    <div class="container">
        <div class="hero-content" data-aos="fade-up">
            <h1>Wildlife Photography Safaris</h1>
            <p class="hero-tagline">Capture Africa's Magnificent Wildlife with Expert Guidance</p>
            
            <div class="hero-buttons">
                <a href="#packages" class="btn btn-primary btn-large">
                    <i class="fas fa-camera"></i> View Photography Packages
                </a>
                <a href="#equipment" class="btn btn-secondary btn-large">
                    <i class="fas fa-cogs"></i> Equipment & Setup
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Introduction -->
<section class="section">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Professional Wildlife Photography</h2>
            <p>Designed for photographers of all levels, our photography safaris combine expert guidance with optimal wildlife viewing opportunities.</p>
        </div>
        
        <div class="grid-2">
            <div data-aos="fade-right">
                <h3>Why Choose Our Photography Safaris?</h3>
                <p>South Luangwa National Park offers some of Africa's best wildlife photography opportunities, with high densities of leopards, lions, and diverse bird species.</p>
                
                <div class="photography-features">
                    <div class="feature">
                        <i class="fas fa-car-alt"></i>
                        <div>
                            <h4>Specialized Vehicles</h4>
                            <p>Custom photography vehicles with camera mounts, bean bags, and optimal positioning</p>
                        </div>
                    </div>
                    
                    <div class="feature">
                        <i class="fas fa-user-tie"></i>
                        <div>
                            <h4>Expert Photo Guides</h4>
                            <p>Professional wildlife photographers who understand animal behavior and lighting</p>
                        </div>
                    </div>
                    
                    <div class="feature">
                        <i class="fas fa-clock"></i>
                        <div>
                            <h4>Optimal Timing</h4>
                            <p>Extended time at sightings for the perfect shot, not limited by typical safari schedules</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div data-aos="fade-left">
                <div class="photo-stats">
                    <div class="stat">
                        <div class="stat-number">98%</div>
                        <div class="stat-label">Leopard Sighting Success</div>
                    </div>
                    
                    <div class="stat">
                        <div class="stat-number">400+</div>
                        <div class="stat-label">Bird Species</div>
                    </div>
                    
                    <div class="stat">
                        <div class="stat-number">Golden</div>
                        <div class="stat-label">Hour Photography</div>
                    </div>
                    
                    <div class="stat">
                        <div class="stat-number">1:4</div>
                        <div class="stat-label">Guide to Guest Ratio</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Photography Packages -->
<section class="section bg-light" id="packages">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Photography Packages</h2>
            <p>Choose the perfect photography experience for your skill level and interests</p>
        </div>
        
        <div class="grid-3">
            <!-- Beginner Package -->
            <div class="package-card" data-aos="fade-up" data-aos-delay="100">
                <div class="package-header">
                    <h3>Essentials Workshop</h3>
                    <div class="package-price">
                        <span class="price">$3,200</span>
                        <span class="duration">7 days</span>
                    </div>
                </div>
                
                <div class="package-body">
                    <ul class="package-features">
                        <li><i class="fas fa-check"></i> Basic Photography Instruction</li>
                        <li><i class="fas fa-check"></i> Camera Settings Workshop</li>
                        <li><i class="fas fa-check"></i> Daily Photo Review Sessions</li>
                        <li><i class="fas fa-check"></i> Group Size: 6 Photographers</li>
                        <li><i class="fas fa-check"></i> Bean Bags Provided</li>
                    </ul>
                    
                    <div class="package-skill">
                        <span class="skill-level beginner">Beginner</span>
                    </div>
                    
                    <a href="/pages/contact.php?package=photography-beginner" class="btn btn-primary btn-block">
                        Book This Package
                    </a>
                </div>
            </div>
            
            <!-- Intermediate Package -->
            <div class="package-card featured" data-aos="fade-up" data-aos-delay="200">
                <div class="package-badge">Most Popular</div>
                <div class="package-header">
                    <h3>Pro Expedition</h3>
                    <div class="package-price">
                        <span class="price">$4,800</span>
                        <span class="duration">10 days</span>
                    </div>
                </div>
                
                <div class="package-body">
                    <ul class="package-features">
                        <li><i class="fas fa-check"></i> Professional Photo Guide</li>
                        <li><i class="fas fa-check"></i> Private Photo Vehicle</li>
                        <li><i class="fas fa-check"></i> Advanced Composition Workshops</li>
                        <li><i class="fas fa-check"></i> Post-Processing Instruction</li>
                        <li><i class="fas fa-check"></i> Equipment Rental Available</li>
                    </ul>
                    
                    <div class="package-skill">
                        <span class="skill-level intermediate">Intermediate</span>
                    </div>
                    
                    <a href="/pages/contact.php?package=photography-pro" class="btn btn-primary btn-block">
                        Book This Package
                    </a>
                </div>
            </div>
            
            <!-- Advanced Package -->
            <div class="package-card" data-aos="fade-up" data-aos-delay="300">
                <div class="package-header">
                    <h3>Masterclass</h3>
                    <div class="package-price">
                        <span class="price">$6,500</span>
                        <span class="duration">14 days</span>
                    </div>
                </div>
                
                <div class="package-body">
                    <ul class="package-features">
                        <li><i class="fas fa-check"></i> One-on-One Mentoring</li>
                        <li><i class="fas fa-check"></i> Exclusive Locations Access</li>
                        <li><i class="fas fa-check"></i> Portfolio Development</li>
                        <li><i class="fas fa-check"></i> Night & Low Light Photography</li>
                        <li><i class="fas fa-check"></i> Professional Printing Included</li>
                    </ul>
                    
                    <div class="package-skill">
                        <span class="skill-level advanced">Advanced</span>
                    </div>
                    
                    <a href="/pages/contact.php?package=photography-master" class="btn btn-primary btn-block">
                        Book This Package
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Equipment Section -->
<section class="section" id="equipment">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Equipment & Setup</h2>
            <p>We provide specialized equipment and vehicles designed for wildlife photography</p>
        </div>
        
        <div class="equipment-showcase" data-aos="fade-up">
            <div class="grid-2">
                <div>
                    <h3>Photo Safari Vehicles</h3>
                    <p>Our custom-designed photography vehicles feature:</p>
                    
                    <ul class="equipment-list">
                        <li><strong>Camera Mounts:</strong> Multiple mounting points for stability</li>
                        <li><strong>Swivel Seats:</strong> 360-degree rotation for optimal angles</li>
                        <li><strong>Bean Bag Stations:</strong> Pre-positioned for quick setup</li>
                        <li><strong>Power Outlets:</strong> For charging equipment in the field</li>
                        <li><strong>Low Noise:</strong> Electric assist for silent approach</li>
                    </ul>
                </div>
                
                <div class="equipment-image">
                    <img src="https://images.unsplash.com/photo-1546182990-dffeafbe841d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="Photo Safari Vehicle">
                </div>
            </div>
        </div>
        
        <!-- Recommended Equipment -->
        <div class="recommended-equipment" data-aos="fade-up">
            <h3 class="text-center">Recommended Equipment</h3>
            
            <div class="grid-4">
                <div class="equipment-category">
                    <div class="category-icon">
                        <i class="fas fa-camera"></i>
                    </div>
                    <h4>Camera Bodies</h4>
                    <p>DSLR or Mirrorless with fast autofocus</p>
                    <small>Recommended: 2 bodies for backup</small>
                </div>
                
                <div class="equipment-category">
                    <div class="category-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h4>Lenses</h4>
                    <p>100-400mm or 150-600mm zoom</p>
                    <small>Wide angle for landscapes</small>
                </div>
                
                <div class="equipment-category">
                    <div class="category-icon">
                        <i class="fas fa-battery-full"></i>
                    </div>
                    <h4>Accessories</h4>
                    <p>Extra batteries & memory cards</p>
                    <small>Lens cleaning kit essential</small>
                </div>
                
                <div class="equipment-category">
                    <div class="category-icon">
                        <i class="fas fa-laptop"></i>
                    </div>
                    <h4>Post-Processing</h4>
                    <p>Laptop with editing software</p>
                    <small>External hard drive for backup</small>
                </div>
            </div>
        </div>
        
        <!-- Rental Equipment -->
        <div class="rental-section" data-aos="fade-up">
            <div class="rental-info">
                <h3>Equipment Rental Available</h3>
                <p>Don't have the right gear? We offer high-quality rental equipment:</p>
                
                <div class="rental-grid">
                    <div class="rental-item">
                        <i class="fas fa-camera"></i>
                        <div>
                            <h5>Camera Bodies</h5>
                            <p>$50/day - Canon/Nikon full-frame</p>
                        </div>
                    </div>
                    
                    <div class="rental-item">
                        <i class="fas fa-search"></i>
                        <div>
                            <h5>Telephoto Lenses</h5>
                            <p>$75/day - 400mm f/2.8 & 600mm f/4</p>
                        </div>
                    </div>
                    
                    <div class="rental-item">
                        <i class="fas fa-video"></i>
                        <div>
                            <h5>Video Equipment</h5>
                            <p>$100/day - 4K video setup</p>
                        </div>
                    </div>
                    
                    <div class="rental-item">
                        <i class="fas fa-tripod"></i>
                        <div>
                            <h5>Support Gear</h5>
                            <p>$25/day - Tripods, gimbals</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Preview -->
<section class="section bg-dark">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2 style="color: var(--white);">Photo Gallery</h2>
            <p style="color: var(--safari-sand);">See the incredible images captured by our photography guests</p>
        </div>
        
        <div class="photo-gallery" data-aos="fade-up">
            <div class="gallery-grid">
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1550358864-518f202c02ba?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                         alt="Leopard Photo">
                    <div class="photo-info">
                        <p>Leopard in Tree</p>
                        <small>Guest: Sarah J.</small>
                    </div>
                </div>
                
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1546182990-dffeafbe841d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                         alt="Lion Pride">
                    <div class="photo-info">
                        <p>Lion Family</p>
                        <small>Guest: Michael T.</small>
                    </div>
                </div>
                
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1564349683136-77e08dba1ef7?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                         alt="Elephant Herd">
                    <div class="photo-info">
                        <p>Elephant Crossing</p>
                        <small>Guest: David C.</small>
                    </div>
                </div>
                
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1516426122078-c23e76319801?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                         alt="Sunset Safari">
                    <div class="photo-info">
                        <p>Golden Hour</p>
                        <small>Guest: Maria R.</small>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-3">
                <a href="/pages/gallery.php?category=photography" class="btn btn-secondary">
                    <i class="fas fa-images"></i> View Full Photography Gallery
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Workshops -->
<section class="section">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Photography Workshops</h2>
            <p>Join our specialized workshops to improve your wildlife photography skills</p>
        </div>
        
        <div class="workshop-schedule" data-aos="fade-up">
            <div class="workshop">
                <div class="workshop-date">
                    <span class="month">MAR</span>
                    <span class="day">15-22</span>
                    <span class="year">2024</span>
                </div>
                <div class="workshop-details">
                    <h4>Bird Photography Masterclass</h4>
                    <p>Focus on capturing birds in flight and behavior with expert ornithologist</p>
                    <div class="workshop-meta">
                        <span><i class="fas fa-users"></i> 4 spots left</span>
                        <span><i class="fas fa-clock"></i> 7 days</span>
                        <span><i class="fas fa-dollar-sign"></i> $2,800</span>
                    </div>
                </div>
                <div class="workshop-action">
                    <a href="#" class="btn btn-outline">Join Waitlist</a>
                </div>
            </div>
            
            <div class="workshop">
                <div class="workshop-date">
                    <span class="month">JUN</span>
                    <span class="day">10-17</span>
                    <span class="year">2024</span>
                </div>
                <div class="workshop-details">
                    <h4>Low Light & Night Photography</h4>
                    <p>Master techniques for night drives, star trails, and moonlit landscapes</p>
                    <div class="workshop-meta">
                        <span><i class="fas fa-users"></i> Available</span>
                        <span><i class="fas fa-clock"></i> 7 days</span>
                        <span><i class="fas fa-dollar-sign"></i> $3,200</span>
                    </div>
                </div>
                <div class="workshop-action">
                    <a href="#" class="btn btn-primary">Book Now</a>
                </div>
            </div>
            
            <div class="workshop">
                <div class="workshop-date">
                    <span class="month">SEP</span>
                    <span class="day">5-12</span>
                    <span class="year">2024</span>
                </div>
                <div class="workshop-details">
                    <h4>Conservation Storytelling</h4>
                    <p>Learn to tell conservation stories through compelling wildlife imagery</p>
                    <div class="workshop-meta">
                        <span><i class="fas fa-users"></i> 2 spots left</span>
                        <span><i class="fas fa-clock"></i> 7 days</span>
                        <span><i class="fas fa-dollar-sign"></i> $3,500</span>
                    </div>
                </div>
                <div class="workshop-action">
                    <a href="#" class="btn btn-primary">Book Now</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Photography Styles -->
<style>
    .photography-features {
        margin-top: var(--spacing-md);
    }
    
    .feature {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .feature i {
        font-size: 1.5rem;
        color: var(--sunset-orange);
        margin-top: 0.25rem;
    }
    
    .photo-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }
    
    .stat {
        text-align: center;
        padding: 1.5rem;
        background: var(--white);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        color: var(--sunset-orange);
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        font-size: 0.9rem;
        color: var(--charcoal);
    }
    
    /* Package Cards */
    .package-card {
        background: var(--white);
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-md);
        transition: transform 0.3s ease;
        position: relative;
    }
    
    .package-card.featured {
        transform: scale(1.05);
        border: 2px solid var(--sunset-orange);
    }
    
    .package-card:hover {
        transform: translateY(-10px);
    }
    
    .package-card.featured:hover {
        transform: scale(1.05) translateY(-10px);
    }
    
    .package-badge {
        position: absolute;
        top: 1rem;
        right: -2rem;
        background: var(--sunset-orange);
        color: white;
        padding: 0.5rem 2rem;
        transform: rotate(45deg);
        font-size: 0.8rem;
        font-weight: bold;
    }
    
    .package-header {
        padding: 2rem;
        background: var(--savanna-green);
        color: white;
        text-align: center;
    }
    
    .package-header h3 {
        color: white;
        margin-bottom: 1rem;
    }
    
    .package-price {
        display: flex;
        justify-content: center;
        align-items: baseline;
        gap: 0.5rem;
    }
    
    .price {
        font-size: 2rem;
        font-weight: bold;
    }
    
    .duration {
        font-size: 0.9rem;
        opacity: 0.9;
    }
    
    .package-body {
        padding: 2rem;
    }
    
    .package-features {
        list-style: none;
        margin-bottom: 1.5rem;
    }
    
    .package-features li {
        padding: 0.5rem 0;
        border-bottom: 1px solid #eee;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .package-features li:last-child {
        border-bottom: none;
    }
    
    .package-features i {
        color: var(--sunset-orange);
    }
    
    .package-skill {
        margin-bottom: 1.5rem;
    }
    
    .skill-level {
        display: inline-block;
        padding: 0.25rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: bold;
    }
    
    .skill-level.beginner {
        background: #d4edda;
        color: #155724;
    }
    
    .skill-level.intermediate {
        background: #fff3cd;
        color: #856404;
    }
    
    .skill-level.advanced {
        background: #d1ecf1;
        color: #0c5460;
    }
    
    .btn-block {
        display: block;
        width: 100%;
    }
    
    /* Equipment Section */
    .equipment-list {
        list-style: none;
        margin: 1.5rem 0;
    }
    
    .equipment-list li {
        padding: 0.75rem 0;
        border-bottom: 1px solid #eee;
    }
    
    .equipment-list li:last-child {
        border-bottom: none;
    }
    
    .equipment-image img {
        width: 100%;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
    }
    
    .recommended-equipment {
        margin-top: var(--spacing-xl);
    }
    
    .equipment-category {
        text-align: center;
        padding: 1.5rem;
        background: var(--white);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
    }
    
    .category-icon {
        font-size: 2rem;
        color: var(--sunset-orange);
        margin-bottom: 1rem;
    }
    
    .rental-section {
        margin-top: var(--spacing-xl);
        background: var(--light-gray);
        padding: 2rem;
        border-radius: var(--radius-lg);
    }
    
    .rental-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-top: 1.5rem;
    }
    
    .rental-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: var(--white);
        border-radius: var(--radius-md);
    }
    
    .rental-item i {
        font-size: 1.5rem;
        color: var(--sunset-orange);
    }
    
    /* Photo Gallery */
    .photo-gallery {
        color: white;
    }
    
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
    }
    
    .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: var(--radius-md);
    }
    
    .gallery-item img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .gallery-item:hover img {
        transform: scale(1.1);
    }
    
    .photo-info {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(0,0,0,0.8));
        padding: 1rem;
        transform: translateY(100%);
        transition: transform 0.3s ease;
    }
    
    .gallery-item:hover .photo-info {
        transform: translateY(0);
    }
    
    /* Workshops */
    .workshop-schedule {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .workshop {
        display: grid;
        grid-template-columns: auto 1fr auto;
        gap: 2rem;
        align-items: center;
        padding: 1.5rem;
        margin-bottom: 1rem;
        background: var(--white);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        transition: transform 0.3s ease;
    }
    
    .workshop:hover {
        transform: translateX(10px);
    }
    
    .workshop-date {
        text-align: center;
        background: var(--savanna-green);
        color: white;
        padding: 1rem;
        border-radius: var(--radius-md);
        min-width: 80px;
    }
    
    .month {
        display: block;
        font-size: 0.9rem;
        text-transform: uppercase;
    }
    
    .day {
        display: block;
        font-size: 1.5rem;
        font-weight: bold;
    }
    
    .year {
        display: block;
        font-size: 0.9rem;
    }
    
    .workshop-details h4 {
        margin-bottom: 0.5rem;
    }
    
    .workshop-meta {
        display: flex;
        gap: 1rem;
        margin-top: 0.5rem;
        font-size: 0.9rem;
        color: var(--dark-gray);
    }
    
    .workshop-meta i {
        margin-right: 0.25rem;
    }
    
    @media (max-width: 1024px) {
        .gallery-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .photo-stats {
            grid-template-columns: 1fr;
        }
        
        .package-card.featured {
            transform: none;
        }
        
        .gallery-grid {
            grid-template-columns: 1fr;
        }
        
        .workshop {
            grid-template-columns: 1fr;
            text-align: center;
        }
        
        .rental-grid {
            grid-template-columns: 1fr;
        }
        
        .grid-4 {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 480px) {
        .grid-4 {
            grid-template-columns: 1fr;
        }
    }
</style>

<?php
// Include CTA Section
require_once '../includes/cta-banner.php';

// Include Footer
require_once '../includes/footer.php';
?>
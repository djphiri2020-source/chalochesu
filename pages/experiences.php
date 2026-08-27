<?php
// pages/experiences.php
$page_title = "Safari Experiences - Guided Wildlife Adventures";
require_once '../includes/header.php';
?>

<!-- Experiences Hero -->
<section class="hero-section" style="background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1550358864-518f202c02ba?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');">
    <div class="container">
        <div class="hero-content" data-aos="fade-up">
            <h1>Wildlife Safari Experiences</h1>
            <p class="hero-tagline">Immerse yourself in the heart of South Luangwa with our expertly guided adventures</p>
            
            <div class="hero-buttons">
                <a href="#activities" class="btn btn-primary btn-large">
                    <i class="fas fa-binoculars"></i> Explore Activities
                </a>
                <a href="/pages/contact.php?action=book" class="btn btn-secondary btn-large">
                    <i class="fas fa-calendar-check"></i> Book Experience
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Introduction -->
<section class="section">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Experience the Wild Heart of Africa</h2>
            <p>Our guided safari experiences are designed to provide intimate encounters with Africa's magnificent wildlife while ensuring your comfort and safety.</p>
        </div>
        
        <div class="grid-2">
            <div data-aos="fade-right">
                <h3>Expert Guided Adventures</h3>
                <p>At Mbowo Camp, we believe the best safari experiences come from deep knowledge and respect for the wilderness. Our guides are local experts with years of experience in South Luangwa National Park.</p>
                
                <div class="feature-list">
                    <div class="feature-item">
                        <i class="fas fa-user-tie"></i>
                        <div>
                            <h4>Professional Guides</h4>
                            <p>All guides are licensed professionals with extensive training in wildlife behavior and safety.</p>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <i class="fas fa-users"></i>
                        <div>
                            <h4>Small Group Sizes</h4>
                            <p>Maximum 6 guests per vehicle for personalized attention and optimal wildlife viewing.</p>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <i class="fas fa-shield-alt"></i>
                        <div>
                            <h4>Safety First</h4>
                            <p>Comprehensive safety protocols and emergency procedures for all activities.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div data-aos="fade-left">
                <img src="https://images.unsplash.com/photo-1546182990-dffeafbe841d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                     alt="Expert Safari Guide" class="experience-img">
            </div>
        </div>
    </div>
</section>

<!-- Activities Section -->
<section class="section bg-light" id="activities">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Safari Activities</h2>
            <p>Choose from our range of daily activities designed to showcase the best of South Luangwa</p>
        </div>
        
        <div class="grid-3">
            <!-- Activity 1 -->
            <div class="activity-card" data-aos="fade-up" data-aos-delay="100">
                <div class="activity-icon">
                    <i class="fas fa-car"></i>
                </div>
                <h3>Game Drives</h3>
                <p>Morning and evening drives in custom 4x4 vehicles with open roofs for optimal viewing.</p>
                
                <div class="activity-details">
                    <div class="detail">
                        <i class="fas fa-clock"></i>
                        <span>3-4 hours</span>
                    </div>
                    <div class="detail">
                        <i class="fas fa-user-friends"></i>
                        <span>2-6 guests</span>
                    </div>
                    <div class="detail">
                        <i class="fas fa-mountain"></i>
                        <span>All terrains</span>
                    </div>
                </div>
                
                <a href="#game-drives-details" class="btn btn-outline btn-sm">Learn More</a>
            </div>
            
            <!-- Activity 2 -->
            <div class="activity-card" data-aos="fade-up" data-aos-delay="200">
                <div class="activity-icon">
                    <i class="fas fa-hiking"></i>
                </div>
                <h3>Walking Safaris</h3>
                <p>Experience the bush on foot with armed guides, focusing on tracks, signs, and small wildlife.</p>
                
                <div class="activity-details">
                    <div class="detail">
                        <i class="fas fa-clock"></i>
                        <span>2-3 hours</span>
                    </div>
                    <div class="detail">
                        <i class="fas fa-user-friends"></i>
                        <span>4 guests max</span>
                    </div>
                    <div class="detail">
                        <i class="fas fa-walking"></i>
                        <span>Moderate fitness</span>
                    </div>
                </div>
                
                <a href="#walking-safaris-details" class="btn btn-outline btn-sm">Learn More</a>
            </div>
            
            <!-- Activity 3 -->
            <div class="activity-card" data-aos="fade-up" data-aos-delay="300">
                <div class="activity-icon">
                    <i class="fas fa-moon"></i>
                </div>
                <h3>Night Drives</h3>
                <p>Discover nocturnal wildlife with powerful spotlights - perfect for spotting leopards and other night creatures.</p>
                
                <div class="activity-details">
                    <div class="detail">
                        <i class="fas fa-clock"></i>
                        <span>2-3 hours</span>
                    </div>
                    <div class="detail">
                        <i class="fas fa-user-friends"></i>
                        <span>2-6 guests</span>
                    </div>
                    <div class="detail">
                        <i class="fas fa-eye"></i>
                        <span>Spotlight viewing</span>
                    </div>
                </div>
                
                <a href="#night-drives-details" class="btn btn-outline btn-sm">Learn More</a>
            </div>
        </div>
        
        <div class="text-center mt-3">
            <a href="/pages/packages.php" class="btn btn-primary">
                <i class="fas fa-list-alt"></i> View Complete Packages
            </a>
        </div>
    </div>
</section>

<!-- Detailed Activities -->
<section class="section">
    <div class="container">
        <div class="activity-detail" id="game-drives-details" data-aos="fade-up">
            <div class="grid-2">
                <div>
                    <img src="https://images.unsplash.com/photo-1550358864-518f202c02ba?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="Game Drive" class="detail-img">
                </div>
                <div>
                    <h3>Game Drives</h3>
                    <p>Our game drives are conducted in custom-designed 4x4 vehicles with open roofs, allowing for 360-degree views and excellent photographic opportunities.</p>
                    
                    <h4>What to Expect:</h4>
                    <ul class="detail-list">
                        <li><strong>Morning Drives:</strong> Depart at dawn when wildlife is most active</li>
                        <li><strong>Evening Drives:</strong> Late afternoon departures with sundowner stops</li>
                        <li><strong>Expert Commentary:</strong> Guides share knowledge about wildlife behavior</li>
                        <li><strong>Refreshments:</strong> Coffee/tea on morning drives, sundowners on evening drives</li>
                    </ul>
                    
                    <div class="wildlife-highlights">
                        <h4>Common Sightings:</h4>
                        <div class="wildlife-tags">
                            <span class="tag">Elephants</span>
                            <span class="tag">Lions</span>
                            <span class="tag">Leopards</span>
                            <span class="tag">Buffalo</span>
                            <span class="tag">Hippos</span>
                            <span class="tag">Giraffes</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="activity-detail" id="walking-safaris-details" data-aos="fade-up">
            <div class="grid-2">
                <div>
                    <h3>Walking Safaris</h3>
                    <p>Walking safaris offer a completely different perspective, allowing you to connect with the bush through all your senses.</p>
                    
                    <h4>Unique Aspects:</h4>
                    <ul class="detail-list">
                        <li><strong>Track Reading:</strong> Learn to identify animal tracks and signs</li>
                        <li><strong>Close Encounters:</strong> Experience wildlife at a more intimate level</li>
                        <li><strong>Botanical Focus:</strong> Learn about plants, insects, and ecosystems</li>
                        <li><strong>Safety:</strong> Always accompanied by armed, experienced guides</li>
                    </ul>
                    
                    <h4>Requirements:</h4>
                    <div class="requirements">
                        <div class="requirement">
                            <i class="fas fa-shoe-prints"></i>
                            <span>Comfortable walking shoes</span>
                        </div>
                        <div class="requirement">
                            <i class="fas fa-tshirt"></i>
                            <span>Neutral-colored clothing</span>
                        </div>
                        <div class="requirement">
                            <i class="fas fa-tint"></i>
                            <span>Water bottle provided</span>
                        </div>
                    </div>
                </div>
                <div>
                    <img src="https://images.unsplash.com/photo-1546182990-dffeafbe841d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="Walking Safari" class="detail-img">
                </div>
            </div>
        </div>
        
        <div class="activity-detail" id="night-drives-details" data-aos="fade-up">
            <div class="grid-2">
                <div>
                    <img src="https://images.unsplash.com/photo-1516426122078-c23e76319801?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="Night Drive" class="detail-img">
                </div>
                <div>
                    <h3>Night Drives</h3>
                    <p>Experience the magic of the African bush after dark, when nocturnal creatures emerge and predators become active.</p>
                    
                    <h4>Special Features:</h4>
                    <ul class="detail-list">
                        <li><strong>Powerful Spotlights:</strong> Specially designed for wildlife viewing</li>
                        <li><strong>Nocturnal Species:</strong> Best chance to see leopards, genets, bushbabies</li>
                        <li><strong>Predator Activity:</strong> Lions and hyenas often hunt at night</li>
                        <li><strong>Star Gazing:</strong> Marvel at the unpolluted African night sky</li>
                    </ul>
                    
                    <div class="equipment-info">
                        <h4>Equipment Provided:</h4>
                        <div class="equipment-grid">
                            <div class="equipment-item">
                                <i class="fas fa-lightbulb"></i>
                                <span>Red-filter spotlights</span>
                            </div>
                            <div class="equipment-item">
                                <i class="fas fa-blanket"></i>
                                <span>Warm blankets</span>
                            </div>
                            <div class="equipment-item">
                                <i class="fas fa-camera"></i>
                                <span>Camera bean bags</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Evening Activities -->
<section class="section bg-dark">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2 style="color: var(--white);">Evening Experiences</h2>
            <p style="color: var(--safari-sand);">After your daily safari activities, enjoy our curated evening programs</p>
        </div>
        
        <div class="grid-3">
            <div class="evening-card" data-aos="fade-up" data-aos-delay="100">
                <div class="evening-icon">
                    <i class="fas fa-campfire"></i>
                </div>
                <h3>Campfire Talks</h3>
                <p>Join our guides around the campfire for stories about local culture, wildlife behavior, and conservation efforts.</p>
            </div>
            
            <div class="evening-card" data-aos="fade-up" data-aos-delay="200">
                <div class="evening-icon">
                    <i class="fas fa-utensils"></i>
                </div>
                <h3>Gourmet Dining</h3>
                <p>Enjoy chef-prepared meals featuring local ingredients, often served under the stars in our boma area.</p>
            </div>
            
            <div class="evening-card" data-aos="fade-up" data-aos-delay="300">
                <div class="evening-icon">
                    <i class="fas fa-star"></i>
                </div>
                <h3>Stargazing</h3>
                <p>With minimal light pollution, our location offers spectacular views of the Milky Way and southern constellations.</p>
            </div>
        </div>
    </div>
</section>

<!-- Experience Styles -->
<style>
    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--spacing-lg);
        align-items: center;
    }
    
    .experience-img {
        width: 100%;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-lg);
    }
    
    .feature-list {
        margin-top: var(--spacing-md);
    }
    
    .feature-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .feature-item i {
        font-size: 1.5rem;
        color: var(--sunset-orange);
        margin-top: 0.25rem;
    }
    
    .feature-item h4 {
        margin-bottom: 0.5rem;
    }
    
    /* Activity Cards */
    .activity-card {
        background: var(--white);
        padding: 2rem;
        border-radius: var(--radius-lg);
        text-align: center;
        box-shadow: var(--shadow-md);
        transition: transform 0.3s ease;
    }
    
    .activity-card:hover {
        transform: translateY(-10px);
    }
    
    .activity-icon {
        font-size: 3rem;
        color: var(--sunset-orange);
        margin-bottom: 1rem;
    }
    
    .activity-details {
        display: flex;
        justify-content: space-around;
        margin: 1.5rem 0;
        padding: 1rem 0;
        border-top: 1px solid #eee;
        border-bottom: 1px solid #eee;
    }
    
    .detail {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    .detail i {
        color: var(--savanna-green);
        margin-bottom: 0.5rem;
    }
    
    /* Activity Details */
    .activity-detail {
        margin-bottom: var(--spacing-xl);
        padding-bottom: var(--spacing-xl);
        border-bottom: 2px solid #eee;
    }
    
    .activity-detail:last-child {
        border-bottom: none;
    }
    
    .detail-img {
        width: 100%;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
    }
    
    .detail-list {
        list-style: none;
        margin: 1.5rem 0;
    }
    
    .detail-list li {
        padding: 0.5rem 0;
        border-bottom: 1px solid #eee;
    }
    
    .detail-list li:last-child {
        border-bottom: none;
    }
    
    .wildlife-highlights {
        margin-top: 2rem;
    }
    
    .wildlife-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 1rem;
    }
    
    .tag {
        background: var(--savanna-green);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
    }
    
    .requirements {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin: 1.5rem 0;
    }
    
    .requirement {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--light-gray);
        padding: 0.75rem 1rem;
        border-radius: var(--radius-md);
    }
    
    .requirement i {
        color: var(--sunset-orange);
    }
    
    .equipment-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-top: 1rem;
    }
    
    .equipment-item {
        text-align: center;
        padding: 1rem;
        background: var(--light-gray);
        border-radius: var(--radius-md);
    }
    
    .equipment-item i {
        font-size: 1.5rem;
        color: var(--sunset-orange);
        margin-bottom: 0.5rem;
        display: block;
    }
    
    /* Evening Cards */
    .evening-card {
        text-align: center;
        color: var(--white);
        padding: 2rem;
    }
    
    .evening-icon {
        font-size: 3rem;
        color: var(--sunset-orange);
        margin-bottom: 1rem;
    }
    
    .evening-card h3 {
        color: var(--white);
        margin-bottom: 1rem;
    }
    
    .evening-card p {
        opacity: 0.9;
    }
    
    @media (max-width: 768px) {
        .grid-2 {
            grid-template-columns: 1fr;
        }
        
        .equipment-grid {
            grid-template-columns: 1fr;
        }
        
        .activity-detail .grid-2 {
            grid-template-columns: 1fr;
        }
        
        .activity-detail .grid-2 div:first-child {
            order: 2;
        }
        
        .activity-detail .grid-2 div:last-child {
            order: 1;
        }
    }
</style>

<?php
// Include CTA Section
require_once '../includes/cta-banner.php';

// Include Footer
require_once '../includes/footer.php';
?>
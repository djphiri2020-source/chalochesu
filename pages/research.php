<?php
// pages/research.php
$page_title = "Wildlife Research & Conservation Programs";
require_once '../includes/header.php';
?>

<!-- Research Hero -->
<section class="hero-section" style="background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1516426122078-c23e76319801?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');">
    <div class="container">
        <div class="hero-content" data-aos="fade-up">
            <h1>Wildlife Research & Conservation</h1>
            <p class="hero-tagline">Contributing to Conservation Through Science and Education</p>
            
            <div class="hero-buttons">
                <a href="#programs" class="btn btn-primary btn-large">
                    <i class="fas fa-microscope"></i> Research Programs
                </a>
                <a href="#partners" class="btn btn-secondary btn-large">
                    <i class="fas fa-handshake"></i> University Partners
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Mission Statement -->
<section class="section">
    <div class="container">
        <div class="mission-statement" data-aos="fade-up">
            <div class="mission-icon">
                <i class="fas fa-leaf"></i>
            </div>
            <h2 class="text-center">Our Conservation Mission</h2>
            <p class="mission-text text-center">
                Mbowo Camp is committed to wildlife conservation through scientific research, 
                community engagement, and sustainable tourism. We bridge the gap between tourism 
                and conservation by providing platforms for meaningful research and education.
            </p>
        </div>
        
        <div class="grid-3" data-aos="fade-up">
            <div class="pillar">
                <div class="pillar-icon">
                    <i class="fas fa-flask"></i>
                </div>
                <h3>Scientific Research</h3>
                <p>Conducting and supporting field research on South Luangwa's ecosystems</p>
            </div>
            
            <div class="pillar">
                <div class="pillar-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3>Education</h3>
                <p>Providing learning opportunities for students and researchers</p>
            </div>
            
            <div class="pillar">
                <div class="pillar-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Community</h3>
                <p>Engaging local communities in conservation efforts</p>
            </div>
        </div>
    </div>
</section>

<!-- Research Programs -->
<section class="section bg-light" id="programs">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Research Programs</h2>
            <p>Join our ongoing conservation research initiatives in South Luangwa National Park</p>
        </div>
        
        <div class="programs-grid">
            <!-- Program 1 -->
            <div class="program-card" data-aos="fade-up" data-aos-delay="100">
                <div class="program-image">
                    <img src="https://images.unsplash.com/photo-1550358864-518f202c02ba?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                         alt="Leopard Research">
                </div>
                <div class="program-content">
                    <span class="program-category">Predator Research</span>
                    <h3>Leopard Population Study</h3>
                    <p>Long-term monitoring of leopard populations using camera traps and tracking collars.</p>
                    
                    <div class="program-details">
                        <div class="detail">
                            <i class="fas fa-calendar"></i>
                            <span>Ongoing</span>
                        </div>
                        <div class="detail">
                            <i class="fas fa-user-friends"></i>
                            <span>4-6 participants</span>
                        </div>
                        <div class="detail">
                            <i class="fas fa-clock"></i>
                            <span>Min. 2 weeks</span>
                        </div>
                    </div>
                    
                    <a href="#leopard-study" class="btn btn-outline">Learn More</a>
                </div>
            </div>
            
            <!-- Program 2 -->
            <div class="program-card" data-aos="fade-up" data-aos-delay="200">
                <div class="program-image">
                    <img src="https://images.unsplash.com/photo-1546182990-dffeafbe841d?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                         alt="Elephant Research">
                </div>
                <div class="program-content">
                    <span class="program-category">Large Mammal Research</span>
                    <h3>Elephant Movement Patterns</h3>
                    <p>Tracking elephant herds to understand migration routes and human-wildlife conflict zones.</p>
                    
                    <div class="program-details">
                        <div class="detail">
                            <i class="fas fa-calendar"></i>
                            <span>Seasonal</span>
                        </div>
                        <div class="detail">
                            <i class="fas fa-user-friends"></i>
                            <span>3-5 participants</span>
                        </div>
                        <div class="detail">
                            <i class="fas fa-clock"></i>
                            <span>Min. 3 weeks</span>
                        </div>
                    </div>
                    
                    <a href="#elephant-study" class="btn btn-outline">Learn More</a>
                </div>
            </div>
            
            <!-- Program 3 -->
            <div class="program-card" data-aos="fade-up" data-aos-delay="300">
                <div class="program-image">
                    <img src="https://images.unsplash.com/photo-1516426122078-c23e76319801?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                         alt="Bird Research">
                </div>
                <div class="program-content">
                    <span class="program-category">Avian Research</span>
                    <h3>Bird Biodiversity Survey</h3>
                    <p>Documenting bird species diversity and population changes across different habitats.</p>
                    
                    <div class="program-details">
                        <div class="detail">
                            <i class="fas fa-calendar"></i>
                            <span>Annual</span>
                        </div>
                        <div class="detail">
                            <i class="fas fa-user-friends"></i>
                            <span>2-4 participants</span>
                        </div>
                        <div class="detail">
                            <i class="fas fa-clock"></i>
                            <span>Min. 1 week</span>
                        </div>
                    </div>
                    
                    <a href="#bird-survey" class="btn btn-outline">Learn More</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- University Partnerships -->
<section class="section" id="partners">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Academic Partnerships</h2>
            <p>Collaborating with leading universities and research institutions worldwide</p>
        </div>
        
        <div class="partners-grid" data-aos="fade-up">
            <div class="partner-logo">
                <div class="logo-placeholder">University of Zambia</div>
                <p>Local Research Partner</p>
            </div>
            
            <div class="partner-logo">
                <div class="logo-placeholder">Cambridge University</div>
                <p>Conservation Research</p>
            </div>
            
            <div class="partner-logo">
                <div class="logo-placeholder">WWF</div>
                <p>Conservation Partner</p>
            </div>
            
            <div class="partner-logo">
                <div class="logo-placeholder">African Wildlife Foundation</div>
                <p>Field Research Support</p>
            </div>
        </div>
        
        <div class="partnership-benefits" data-aos="fade-up">
            <h3 class="text-center">Benefits for Academic Institutions</h3>
            
            <div class="grid-3">
                <div class="benefit">
                    <i class="fas fa-map-marked-alt"></i>
                    <h4>Field Research Site</h4>
                    <p>Access to prime research locations in South Luangwa National Park</p>
                </div>
                
                <div class="benefit">
                    <i class="fas fa-database"></i>
                    <h4>Data Collection Support</h4>
                    <p>Logistical support and local expertise for field research</p>
                </div>
                
                <div class="benefit">
                    <i class="fas fa-user-graduate"></i>
                    <h4>Student Programs</h4>
                    <p>Structured programs for undergraduate and graduate students</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Student Programs -->
<section class="section bg-dark">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2 style="color: var(--white);">Student Research Programs</h2>
            <p style="color: var(--safari-sand);">Hands-on field experience for students and early-career researchers</p>
        </div>
        
        <div class="program-options" data-aos="fade-up">
            <div class="program-option">
                <div class="option-header">
                    <h3>Undergraduate Field Course</h3>
                    <div class="option-duration">2-4 weeks</div>
                </div>
                <div class="option-content">
                    <ul>
                        <li>Introduction to field research methods</li>
                        <li>Data collection techniques</li>
                        <li>Ecosystem monitoring</li>
                        <li>Conservation ethics</li>
                    </ul>
                    <div class="option-footer">
                        <span class="price">From $2,800</span>
                        <a href="#" class="btn btn-outline">Details</a>
                    </div>
                </div>
            </div>
            
            <div class="program-option">
                <div class="option-header">
                    <h3>Graduate Research</h3>
                    <div class="option-duration">4-12 weeks</div>
                </div>
                <div class="option-content">
                    <ul>
                        <li>Thesis/dissertation support</li>
                        <li>Mentorship from experts</li>
                        <li>Access to long-term data</li>
                        <li>Publication opportunities</li>
                    </ul>
                    <div class="option-footer">
                        <span class="price">From $3,500</span>
                        <a href="#" class="btn btn-outline">Details</a>
                    </div>
                </div>
            </div>
            
            <div class="program-option">
                <div class="option-header">
                    <h3>Faculty-led Groups</h3>
                    <div class="option-duration">Custom</div>
                </div>
                <div class="option-content">
                    <ul>
                        <li>Customized itineraries</li>
                        <li>Academic credit arrangements</li>
                        <li>Faculty accommodations</li>
                        <li>Research vehicle access</li>
                    </ul>
                    <div class="option-footer">
                        <span class="price">Custom Quote</span>
                        <a href="#" class="btn btn-outline">Contact</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Research Facilities -->
<section class="section">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Research Facilities</h2>
            <p>Our camp provides dedicated facilities to support scientific research</p>
        </div>
        
        <div class="facilities-grid" data-aos="fade-up">
            <div class="facility">
                <div class="facility-icon">
                    <i class="fas fa-laptop"></i>
                </div>
                <h4>Research Lab</h4>
                <p>Equipped workspace with microscopes, computers, and internet access</p>
            </div>
            
            <div class="facility">
                <div class="facility-icon">
                    <i class="fas fa-database"></i>
                </div>
                <h4>Data Center</h4>
                <p>Secure data storage and backup systems for research data</p>
            </div>
            
            <div class="facility">
                <div class="facility-icon">
                    <i class="fas fa-satellite-dish"></i>
                </div>
                <h4>Communication</h4>
                <p>Satellite internet and communication equipment</p>
            </div>
            
            <div class="facility">
                <div class="facility-icon">
                    <i class="fas fa-tools"></i>
                </div>
                <h4>Field Equipment</h4>
                <p>Camera traps, GPS units, tracking collars, and sampling kits</p>
            </div>
        </div>
    </div>
</section>

<!-- Publications & Impact -->
<section class="section bg-light">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Research Impact</h2>
            <p>Contributing to wildlife conservation through published research</p>
        </div>
        
        <div class="publications" data-aos="fade-up">
            <div class="publication">
                <div class="pub-year">2023</div>
                <div class="pub-details">
                    <h4>"Leopard population dynamics in fragmented habitats"</h4>
                    <p class="pub-journal">Journal of Wildlife Management, Vol. 87, Issue 3</p>
                    <p class="pub-authors">Smith, J., Banda, L., Mbowo Research Team</p>
                </div>
            </div>
            
            <div class="publication">
                <div class="pub-year">2022</div>
                <div class="pub-details">
                    <h4>"Elephant migration corridors in the Luangwa Valley"</h4>
                    <p class="pub-journal">African Journal of Ecology, Vol. 60, Issue 2</p>
                    <p class="pub-authors">Chen, D., Mbowo Research Team</p>
                </div>
            </div>
            
            <div class="publication">
                <div class="pub-year">2021</div>
                <div class="pub-details">
                    <h4>"Impact of sustainable tourism on local conservation attitudes"</h4>
                    <p class="pub-journal">Conservation & Society, Vol. 19, Issue 4</p>
                    <p class="pub-authors">Rodriguez, M., et al.</p>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-3">
            <a href="#publications-archive" class="btn btn-primary">
                <i class="fas fa-book-open"></i> View All Publications
            </a>
        </div>
    </div>
</section>

<!-- Research Page Styles -->
<style>
    .mission-statement {
        max-width: 800px;
        margin: 0 auto var(--spacing-xl);
        text-align: center;
    }
    
    .mission-icon {
        font-size: 3rem;
        color: var(--sunset-orange);
        margin-bottom: 1rem;
    }
    
    .mission-text {
        font-size: 1.2rem;
        line-height: 1.8;
    }
    
    .pillar {
        text-align: center;
        padding: 2rem;
    }
    
    .pillar-icon {
        font-size: 2.5rem;
        color: var(--savanna-green);
        margin-bottom: 1rem;
    }
    
    /* Program Cards */
    .programs-grid {
        display: grid;
        gap: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .program-card {
        display: grid;
        grid-template-columns: 300px 1fr;
        background: var(--white);
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-md);
    }
    
    .program-image {
        height: 100%;
    }
    
    .program-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .program-content {
        padding: 2rem;
    }
    
    .program-category {
        display: inline-block;
        background: var(--savanna-green);
        color: white;
        padding: 0.25rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }
    
    .program-details {
        display: flex;
        gap: 1.5rem;
        margin: 1.5rem 0;
        padding: 1rem 0;
        border-top: 1px solid #eee;
        border-bottom: 1px solid #eee;
    }
    
    .detail {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
    }
    
    .detail i {
        color: var(--sunset-orange);
    }
    
    /* Partners */
    .partners-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 2rem;
        margin-bottom: var(--spacing-xl);
    }
    
    .partner-logo {
        text-align: center;
        padding: 2rem;
        background: var(--white);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
    }
    
    .logo-placeholder {
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--light-gray);
        border-radius: var(--radius-md);
        margin-bottom: 1rem;
        font-weight: bold;
        color: var(--savanna-green);
    }
    
    .partnership-benefits {
        margin-top: var(--spacing-xl);
    }
    
    .benefit {
        text-align: center;
        padding: 2rem;
    }
    
    .benefit i {
        font-size: 2.5rem;
        color: var(--sunset-orange);
        margin-bottom: 1rem;
    }
    
    /* Student Programs */
    .program-options {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
        color: var(--white);
    }
    
    .program-option {
        background: rgba(255,255,255,0.1);
        border-radius: var(--radius-lg);
        overflow: hidden;
        backdrop-filter: blur(10px);
    }
    
    .option-header {
        padding: 1.5rem;
        background: rgba(0,0,0,0.2);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .option-header h3 {
        color: var(--white);
        margin: 0;
        font-size: 1.2rem;
    }
    
    .option-duration {
        background: var(--sunset-orange);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.9rem;
    }
    
    .option-content {
        padding: 1.5rem;
    }
    
    .option-content ul {
        list-style: none;
        margin-bottom: 1.5rem;
    }
    
    .option-content li {
        padding: 0.5rem 0;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    
    .option-content li:last-child {
        border-bottom: none;
    }
    
    .option-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .price {
        font-weight: bold;
        font-size: 1.1rem;
    }
    
    /* Facilities */
    .facilities-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 2rem;
    }
    
    .facility {
        text-align: center;
        padding: 2rem;
        background: var(--white);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
    }
    
    .facility-icon {
        font-size: 2.5rem;
        color: var(--savanna-green);
        margin-bottom: 1rem;
    }
    
    /* Publications */
    .publications {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .publication {
        display: grid;
        grid-template-columns: 100px 1fr;
        gap: 2rem;
        padding: 1.5rem;
        margin-bottom: 1rem;
        background: var(--white);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
    }
    
    .pub-year {
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--savanna-green);
        color: white;
        border-radius: var(--radius-md);
        font-size: 1.2rem;
        font-weight: bold;
    }
    
    .pub-details h4 {
        margin-bottom: 0.5rem;
    }
    
    .pub-journal {
        font-style: italic;
        color: var(--dark-gray);
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    
    .pub-authors {
        font-size: 0.9rem;
        color: var(--sunset-orange);
    }
    
    @media (max-width: 1024px) {
        .program-card {
            grid-template-columns: 1fr;
        }
        
        .program-image {
            height: 250px;
        }
        
        .partners-grid,
        .facilities-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .program-options {
            grid-template-columns: 1fr;
        }
        
        .partners-grid,
        .facilities-grid {
            grid-template-columns: 1fr;
        }
        
        .publication {
            grid-template-columns: 1fr;
            text-align: center;
        }
        
        .pub-year {
            height: 60px;
        }
    }
</style>

<?php
// Include CTA Section
require_once '../includes/cta-banner.php';

// Include Footer
require_once '../includes/footer.php';
?>
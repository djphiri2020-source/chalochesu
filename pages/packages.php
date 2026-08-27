<?php
// pages/packages.php
$page_title = "Safari Packages & Pricing - All-Inclusive Adventures";
require_once '../includes/header.php';
?>

<!-- Packages Hero -->
<section class="hero-section" style="background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1516483638261-f4dbaf036963?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');">
    <div class="container">
        <div class="hero-content" data-aos="fade-up">
            <h1>Safari Packages</h1>
            <p class="hero-tagline">All-Inclusive Luxury Safari Experiences in South Luangwa</p>
            
            <div class="hero-buttons">
                <a href="#packages-grid" class="btn btn-primary btn-large">
                    <i class="fas fa-gem"></i> View Packages
                </a>
                <a href="#comparison" class="btn btn-secondary btn-large">
                    <i class="fas fa-balance-scale"></i> Compare Packages
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Packages Introduction -->
<section class="section">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>All-Inclusive Safari Experiences</h2>
            <p>Every package includes luxury accommodation, expert guiding, meals, and activities for a seamless African adventure.</p>
        </div>
        
        <div class="included-features" data-aos="fade-up">
            <h3 class="text-center mb-2">What's Included in All Packages</h3>
            
            <div class="features-grid">
                <div class="feature-item">
                    <i class="fas fa-plane"></i>
                    <span>Internal flights & transfers</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-bed"></i>
                    <span>Luxury tent accommodation</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-utensils"></i>
                    <span>All meals & beverages</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-car"></i>
                    <span>Daily safari activities</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-user-tie"></i>
                    <span>Professional guide services</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-shield-alt"></i>
                    <span>Park fees & conservation levies</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-suitcase"></i>
                    <span>Laundry service</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-wifi"></i>
                    <span>Complimentary Wi-Fi</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Packages Grid -->
<section class="section bg-light" id="packages-grid">
    <div class="container">
        <div class="packages-filter" data-aos="fade-up">
            <h3 class="text-center mb-2">Find Your Perfect Safari</h3>
            
            <div class="filter-options">
                <button class="filter-btn active" data-filter="all">All Packages</button>
                <button class="filter-btn" data-filter="wildlife">Wildlife Safaris</button>
                <button class="filter-btn" data-filter="photography">Photography</button>
                <button class="filter-btn" data-filter="research">Research</button>
                <button class="filter-btn" data-filter="family">Family</button>
                <button class="filter-btn" data-filter="luxury">Luxury</button>
            </div>
        </div>
        
        <div class="packages-container">
            <!-- Package 1: Classic Wildlife Safari -->
            <div class="package-card wildlife" data-aos="fade-up" data-aos-delay="100">
                <div class="package-badge">Best Seller</div>
                <div class="package-image">
                    <img src="https://images.unsplash.com/photo-1550358864-518f202c02ba?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="Classic Wildlife Safari">
                </div>
                <div class="package-content">
                    <div class="package-header">
                        <span class="package-category wildlife">Wildlife Safari</span>
                        <h3>Classic Wildlife Safari</h3>
                        <div class="package-meta">
                            <span><i class="fas fa-clock"></i> 7 Days</span>
                            <span><i class="fas fa-user-friends"></i> 2-6 Guests</span>
                            <span><i class="fas fa-map-marker-alt"></i> South Luangwa</span>
                        </div>
                    </div>
                    
                    <div class="package-body">
                        <p>Our signature safari experience with daily game drives, walking safaris, and expert wildlife viewing.</p>
                        
                        <div class="package-highlights">
                            <h4>Experience Highlights:</h4>
                            <ul>
                                <li>Twice-daily game drives</li>
                                <li>Walking safaris with armed guides</li>
                                <li>Night drives for nocturnal wildlife</li>
                                <li>Bush breakfasts and sundowners</li>
                                <li>Evening conservation talks</li>
                            </ul>
                        </div>
                        
                        <div class="package-price">
                            <div class="price-info">
                                <span class="price">$3,500</span>
                                <span class="per-person">per person</span>
                            </div>
                            <div class="season-note">High Season Rate</div>
                        </div>
                    </div>
                    
                    <div class="package-footer">
                        <a href="/pages/contact.php?package=classic-wildlife" class="btn btn-primary">
                            <i class="fas fa-calendar-check"></i> Book Now
                        </a>
                        <a href="#classic-details" class="btn btn-outline">Details</a>
                    </div>
                </div>
            </div>
            
            <!-- Package 2: Photography Expedition -->
            <div class="package-card photography" data-aos="fade-up" data-aos-delay="200">
                <div class="package-badge">Photography Focus</div>
                <div class="package-image">
                    <img src="https://images.unsplash.com/photo-1516483638261-f4dbaf036963?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="Photography Expedition">
                </div>
                <div class="package-content">
                    <div class="package-header">
                        <span class="package-category photography">Photography</span>
                        <h3>Photography Expedition</h3>
                        <div class="package-meta">
                            <span><i class="fas fa-clock"></i> 10 Days</span>
                            <span><i class="fas fa-user-friends"></i> 4 Guests Max</span>
                            <span><i class="fas fa-map-marker-alt"></i> South Luangwa</span>
                        </div>
                    </div>
                    
                    <div class="package-body">
                        <p>Designed for photographers, with specialized vehicles, expert guidance, and optimal lighting conditions.</p>
                        
                        <div class="package-highlights">
                            <h4>Photography Features:</h4>
                            <ul>
                                <li>Professional photography guide</li>
                                <li>Specialized photo vehicle</li>
                                <li>Extended time at sightings</li>
                                <li>Photo editing workshops</li>
                                <li>Equipment rental available</li>
                            </ul>
                        </div>
                        
                        <div class="package-price">
                            <div class="price-info">
                                <span class="price">$4,800</span>
                                <span class="per-person">per person</span>
                            </div>
                            <div class="season-note">Includes equipment</div>
                        </div>
                    </div>
                    
                    <div class="package-footer">
                        <a href="/pages/contact.php?package=photography-expedition" class="btn btn-primary">
                            <i class="fas fa-calendar-check"></i> Book Now
                        </a>
                        <a href="#photography-details" class="btn btn-outline">Details</a>
                    </div>
                </div>
            </div>
            
            <!-- Package 3: Research & Conservation -->
            <div class="package-card research" data-aos="fade-up" data-aos-delay="300">
                <div class="package-badge">Conservation Focus</div>
                <div class="package-image">
                    <img src="https://images.unsplash.com/photo-1516426122078-c23e76319801?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="Research Program">
                </div>
                <div class="package-content">
                    <div class="package-header">
                        <span class="package-category research">Research</span>
                        <h3>Research & Conservation Program</h3>
                        <div class="package-meta">
                            <span><i class="fas fa-clock"></i> 14 Days</span>
                            <span><i class="fas fa-user-friends"></i> 2-4 Participants</span>
                            <span><i class="fas fa-map-marker-alt"></i> Research Zones</span>
                        </div>
                    </div>
                    
                    <div class="package-body">
                        <p>Hands-on conservation experience with field researchers, data collection, and ecosystem monitoring.</p>
                        
                        <div class="package-highlights">
                            <h4>Research Activities:</h4>
                            <ul>
                                <li>Field research participation</li>
                                <li>Data collection training</li>
                                <li>Conservation workshops</li>
                                <li>University credit options</li>
                                <li>Research materials provided</li>
                            </ul>
                        </div>
                        
                        <div class="package-price">
                            <div class="price-info">
                                <span class="price">$4,200</span>
                                <span class="per-person">per person</span>
                            </div>
                            <div class="season-note">Academic discounts available</div>
                        </div>
                    </div>
                    
                    <div class="package-footer">
                        <a href="/pages/contact.php?package=research-conservation" class="btn btn-primary">
                            <i class="fas fa-calendar-check"></i> Book Now
                        </a>
                        <a href="#research-details" class="btn btn-outline">Details</a>
                    </div>
                </div>
            </div>
            
            <!-- Package 4: Family Safari Adventure -->
            <div class="package-card family" data-aos="fade-up" data-aos-delay="400">
                <div class="package-badge">Family Friendly</div>
                <div class="package-image">
                    <img src="https://images.unsplash.com/photo-1546182990-dffeafbe841d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="Family Safari">
                </div>
                <div class="package-content">
                    <div class="package-header">
                        <span class="package-category family">Family</span>
                        <h3>Family Safari Adventure</h3>
                        <div class="package-meta">
                            <span><i class="fas fa-clock"></i> 8 Days</span>
                            <span><i class="fas fa-user-friends"></i> Families</span>
                            <span><i class="fas fa-map-marker-alt"></i> Child-friendly Areas</span>
                        </div>
                    </div>
                    
                    <div class="package-body">
                        <p>Specially designed for families with children, offering age-appropriate activities and flexible schedules.</p>
                        
                        <div class="package-highlights">
                            <h4>Family Features:</h4>
                            <ul>
                                <li>Child-friendly guides</li>
                                <li>Family tent accommodations</li>
                                <li>Educational activities for kids</li>
                                <li>Flexible meal times</li>
                                <li>Private vehicle option</li>
                            </ul>
                        </div>
                        
                        <div class="package-price">
                            <div class="price-info">
                                <span class="price">$3,200</span>
                                <span class="per-person">per adult</span>
                            </div>
                            <div class="season-note">Children 50% off</div>
                        </div>
                    </div>
                    
                    <div class="package-footer">
                        <a href="/pages/contact.php?package=family-safari" class="btn btn-primary">
                            <i class="fas fa-calendar-check"></i> Book Now
                        </a>
                        <a href="#family-details" class="btn btn-outline">Details</a>
                    </div>
                </div>
            </div>
            
            <!-- Package 5: Luxury Private Safari -->
            <div class="package-card luxury" data-aos="fade-up" data-aos-delay="500">
                <div class="package-badge">Ultimate Luxury</div>
                <div class="package-image">
                    <img src="https://images.unsplash.com/photo-1564349683136-77e08dba1ef7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="Luxury Safari">
                </div>
                <div class="package-content">
                    <div class="package-header">
                        <span class="package-category luxury">Luxury</span>
                        <h3>Luxury Private Safari</h3>
                        <div class="package-meta">
                            <span><i class="fas fa-clock"></i> Custom</span>
                            <span><i class="fas fa-user-friends"></i> Private</span>
                            <span><i class="fas fa-map-marker-alt"></i> Exclusive Areas</span>
                        </div>
                    </div>
                    
                    <div class="package-body">
                        <p>The ultimate safari experience with private guides, exclusive accommodations, and personalized itinerary.</p>
                        
                        <div class="package-highlights">
                            <h4>Luxury Inclusions:</h4>
                            <ul>
                                <li>Private guide and vehicle</li>
                                <li>Suite accommodation</li>
                                <li>Personalized itinerary</li>
                                <li>Private chef and butler</li>
                                <li>Helicopter transfers available</li>
                            </ul>
                        </div>
                        
                        <div class="package-price">
                            <div class="price-info">
                                <span class="price">From $6,500</span>
                                <span class="per-person">per person</span>
                            </div>
                            <div class="season-note">Custom pricing</div>
                        </div>
                    </div>
                    
                    <div class="package-footer">
                        <a href="/pages/contact.php?package=luxury-private" class="btn btn-primary">
                            <i class="fas fa-calendar-check"></i> Enquire
                        </a>
                        <a href="#luxury-details" class="btn btn-outline">Details</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Package Comparison -->
<section class="section" id="comparison">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Package Comparison</h2>
            <p>Compare features across our different safari packages to find your perfect match</p>
        </div>
        
        <div class="comparison-table" data-aos="fade-up">
            <table>
                <thead>
                    <tr>
                        <th>Features</th>
                        <th>Classic Wildlife</th>
                        <th>Photography</th>
                        <th>Research</th>
                        <th>Family</th>
                        <th>Luxury</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Daily Game Drives</td>
                        <td><i class="fas fa-check"></i></td>
                        <td><i class="fas fa-check"></i></td>
                        <td><i class="fas fa-check"></i></td>
                        <td><i class="fas fa-check"></i></td>
                        <td><i class="fas fa-check"></i></td>
                    </tr>
                    <tr>
                        <td>Walking Safaris</td>
                        <td><i class="fas fa-check"></i></td>
                        <td><i class="fas fa-check"></i></td>
                        <td><i class="fas fa-check"></i></td>
                        <td>Limited</td>
                        <td><i class="fas fa-check"></i></td>
                    </tr>
                    <tr>
                        <td>Night Drives</td>
                        <td><i class="fas fa-check"></i></td>
                        <td><i class="fas fa-check"></i></td>
                        <td><i class="fas fa-check"></i></td>
                        <td>Optional</td>
                        <td><i class="fas fa-check"></i></td>
                    </tr>
                    <tr>
                        <td>Private Vehicle</td>
                        <td>-</td>
                        <td><i class="fas fa-check"></i></td>
                        <td><i class="fas fa-check"></i></td>
                        <td>Optional</td>
                        <td><i class="fas fa-check"></i></td>
                    </tr>
                    <tr>
                        <td>Expert Specialization</td>
                        <td>Wildlife Guide</td>
                        <td>Photo Guide</td>
                        <td>Research Lead</td>
                        <td>Family Guide</td>
                        <td>Private Guide</td>
                    </tr>
                    <tr>
                        <td>Group Size</td>
                        <td>2-6</td>
                        <td>2-4</td>
                        <td>2-4</td>
                        <td>Family</td>
                        <td>Private</td>
                    </tr>
                    <tr>
                        <td>Accommodation</td>
                        <td>Luxury Tent</td>
                        <td>Luxury Tent</td>
                        <td>Research Tent</td>
                        <td>Family Tent</td>
                        <td>Suite</td>
                    </tr>
                    <tr>
                        <td>Meal Plan</td>
                        <td>Full Board</td>
                        <td>Full Board</td>
                        <td>Full Board</td>
                        <td>Full Board</td>
                        <td>Gourmet</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Season Pricing -->
<section class="section bg-dark">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2 style="color: var(--white);">Seasonal Pricing</h2>
            <p style="color: var(--safari-sand);">Rates vary by season - choose the best time for your safari adventure</p>
        </div>
        
        <div class="season-calendar" data-aos="fade-up">
            <div class="season">
                <div class="season-header green">
                    <h3>Green Season</h3>
                    <p>Nov - Apr</p>
                </div>
                <div class="season-content">
                    <h4>Best For:</h4>
                    <ul>
                        <li>Bird Watching</li>
                        <li>Photography</li>
                        <li>Lower Rates</li>
                        <li>Lush Landscapes</li>
                    </ul>
                    <div class="season-price">
                        <span class="discount">20% Off</span>
                        <span class="rate">Standard Rates</span>
                    </div>
                </div>
            </div>
            
            <div class="season">
                <div class="season-header orange">
                    <h3>Shoulder Season</h3>
                    <p>May - Jun, Oct</p>
                </div>
                <div class="season-content">
                    <h4>Best For:</h4>
                    <ul>
                        <li>Wildlife Viewing</li>
                        <li>Comfortable Weather</li>
                        <li>Good Value</li>
                        <li>Fewer Crowds</li>
                    </ul>
                    <div class="season-price">
                        <span class="discount">10% Off</span>
                        <span class="rate">Standard Rates</span>
                    </div>
                </div>
            </div>
            
            <div class="season">
                <div class="season-header red">
                    <h3>High Season</h3>
                    <p>Jul - Sep</p>
                </div>
                <div class="season-content">
                    <h4>Best For:</h4>
                    <ul>
                        <li>Premium Wildlife</li>
                        <li>Dry Weather</li>
                        <li>Peak Viewing</li>
                        <li>All Activities</li>
                    </ul>
                    <div class="season-price">
                        <span class="standard">Standard</span>
                        <span class="rate">Full Rates</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Booking Information -->
<section class="section">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Booking Information</h2>
            <p>Everything you need to know before booking your safari adventure</p>
        </div>
        
        <div class="booking-info" data-aos="fade-up">
            <div class="info-grid">
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h4>When to Book</h4>
                    <p>We recommend booking 6-12 months in advance for high season, 3-6 months for other seasons.</p>
                </div>
                
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <h4>Deposit & Payment</h4>
                    <p>30% deposit to confirm booking, balance due 60 days before arrival. Multiple payment methods accepted.</p>
                </div>
                
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <h4>Cancellation Policy</h4>
                    <p>60+ days: Full refund. 30-59 days: 50% refund. Less than 30 days: No refund. Travel insurance recommended.</p>
                </div>
                
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h4>Single Supplement</h4>
                    <p>Single room supplement applies for solo travelers. We can sometimes match solo travelers to share.</p>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-3">
            <a href="/pages/contact.php" class="btn btn-primary btn-large">
                <i class="fas fa-question-circle"></i> Have Questions? Contact Us
            </a>
        </div>
    </div>
</section>

<!-- Packages Page Styles -->
<style>
    .included-features {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .features-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-top: 2rem;
    }
    
    .feature-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 1.5rem;
        background: var(--white);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
    }
    
    .feature-item i {
        font-size: 2rem;
        color: var(--sunset-orange);
        margin-bottom: 1rem;
    }
    
    /* Filter */
    .filter-options {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 2rem;
    }
    
    .filter-btn {
        padding: 0.5rem 1.5rem;
        background: var(--white);
        border: 2px solid var(--light-gray);
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
    }
    
    .filter-btn:hover,
    .filter-btn.active {
        background: var(--savanna-green);
        color: white;
        border-color: var(--savanna-green);
    }
    
    /* Package Cards */
    .packages-container {
        display: grid;
        gap: 2rem;
    }
    
    .package-card {
        display: grid;
        grid-template-columns: 400px 1fr;
        background: var(--white);
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-lg);
        position: relative;
    }
    
    .package-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: var(--sunset-orange);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: bold;
        z-index: 2;
    }
    
    .package-image {
        height: 100%;
    }
    
    .package-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .package-content {
        padding: 2rem;
    }
    
    .package-header {
        margin-bottom: 1.5rem;
    }
    
    .package-category {
        display: inline-block;
        padding: 0.25rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    
    .package-category.wildlife {
        background: #d4edda;
        color: #155724;
    }
    
    .package-category.photography {
        background: #d1ecf1;
        color: #0c5460;
    }
    
    .package-category.research {
        background: #cce5ff;
        color: #004085;
    }
    
    .package-category.family {
        background: #fff3cd;
        color: #856404;
    }
    
    .package-category.luxury {
        background: #e2d5b8;
        color: #8b4513;
    }
    
    .package-meta {
        display: flex;
        gap: 1.5rem;
        color: var(--dark-gray);
        font-size: 0.9rem;
        margin-top: 0.5rem;
    }
    
    .package-meta i {
        margin-right: 0.25rem;
        color: var(--sunset-orange);
    }
    
    .package-body {
        margin: 1.5rem 0;
    }
    
    .package-highlights {
        margin: 1.5rem 0;
    }
    
    .package-highlights h4 {
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }
    
    .package-highlights ul {
        list-style: none;
        margin-left: 1rem;
    }
    
    .package-highlights li {
        padding: 0.25rem 0;
        position: relative;
    }
    
    .package-highlights li:before {
        content: "•";
        color: var(--sunset-orange);
        font-weight: bold;
        position: absolute;
        left: -1rem;
    }
    
    .package-price {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: var(--light-gray);
        border-radius: var(--radius-md);
        margin-top: 1.5rem;
    }
    
    .price-info {
        display: flex;
        align-items: baseline;
        gap: 0.5rem;
    }
    
    .price {
        font-size: 2rem;
        font-weight: bold;
        color: var(--savanna-green);
    }
    
    .per-person {
        color: var(--dark-gray);
        font-size: 0.9rem;
    }
    
    .season-note {
        font-size: 0.9rem;
        color: var(--sunset-orange);
        font-style: italic;
    }
    
    .package-footer {
        display: flex;
        gap: 1rem;
    }
    
    /* Comparison Table */
    .comparison-table {
        overflow-x: auto;
    }
    
    .comparison-table table {
        width: 100%;
        border-collapse: collapse;
        background: var(--white);
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-md);
    }
    
    .comparison-table th,
    .comparison-table td {
        padding: 1rem;
        text-align: center;
        border: 1px solid #eee;
    }
    
    .comparison-table th {
        background: var(--savanna-green);
        color: white;
        font-weight: 600;
    }
    
    .comparison-table th:first-child {
        background: var(--charcoal);
        text-align: left;
    }
    
    .comparison-table td:first-child {
        text-align: left;
        font-weight: 500;
        background: var(--light-gray);
    }
    
    .comparison-table tr:nth-child(even) {
        background: #f9f9f9;
    }
    
    .comparison-table i.fa-check {
        color: var(--sunset-orange);
        font-size: 1.2rem;
    }
    
    /* Season Calendar */
    .season-calendar {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
        color: var(--white);
    }
    
    .season {
        background: rgba(255,255,255,0.1);
        border-radius: var(--radius-lg);
        overflow: hidden;
        backdrop-filter: blur(10px);
    }
    
    .season-header {
        padding: 1.5rem;
        text-align: center;
    }
    
    .season-header.green {
        background: rgba(58, 95, 62, 0.8);
    }
    
    .season-header.orange {
        background: rgba(217, 108, 41, 0.8);
    }
    
    .season-header.red {
        background: rgba(139, 69, 19, 0.8);
    }
    
    .season-header h3 {
        color: var(--white);
        margin-bottom: 0.5rem;
    }
    
    .season-content {
        padding: 1.5rem;
    }
    
    .season-content h4 {
        color: var(--white);
        margin-bottom: 1rem;
    }
    
    .season-content ul {
        list-style: none;
        margin-bottom: 1.5rem;
    }
    
    .season-content li {
        padding: 0.5rem 0;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    
    .season-content li:last-child {
        border-bottom: none;
    }
    
    .season-price {
        text-align: center;
        padding-top: 1rem;
        border-top: 1px solid rgba(255,255,255,0.2);
    }
    
    .discount {
        display: block;
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--safari-sand);
    }
    
    .standard {
        display: block;
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--white);
    }
    
    .rate {
        font-size: 0.9rem;
        opacity: 0.8;
    }
    
    /* Booking Info */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 2rem;
    }
    
    .info-card {
        text-align: center;
        padding: 2rem;
        background: var(--white);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
    }
    
    .info-icon {
        font-size: 2.5rem;
        color: var(--sunset-orange);
        margin-bottom: 1rem;
    }
    
    @media (max-width: 1200px) {
        .package-card {
            grid-template-columns: 300px 1fr;
        }
    }
    
    @media (max-width: 1024px) {
        .features-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .package-card {
            grid-template-columns: 1fr;
        }
        
        .package-image {
            height: 300px;
        }
        
        .info-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .season-calendar {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .features-grid {
            grid-template-columns: 1fr;
        }
        
        .filter-options {
            flex-direction: column;
            align-items: center;
        }
        
        .filter-btn {
            width: 200px;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .comparison-table {
            font-size: 0.9rem;
        }
    }
</style>

<!-- Package Filter JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterBtns = document.querySelectorAll('.filter-btn');
        const packageCards = document.querySelectorAll('.package-card');
        
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                filterBtns.forEach(b => b.classList.remove('active'));
                
                // Add active class to clicked button
                this.classList.add('active');
                
                const filter = this.dataset.filter;
                
                // Filter packages
                packageCards.forEach(card => {
                    if (filter === 'all' || card.classList.contains(filter)) {
                        card.style.display = 'grid';
                        setTimeout(() => {
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0)';
                        }, 100);
                    } else {
                        card.style.opacity = '0';
                        card.style.transform = 'translateY(20px)';
                        setTimeout(() => {
                            card.style.display = 'none';
                        }, 300);
                    }
                });
            });
        });
    });
</script>

<?php
// Include CTA Section
require_once '../includes/cta-banner.php';

// Include Footer
require_once '../includes/footer.php';
?>
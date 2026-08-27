<?php
// pages/gallery.php
$page_title = "Photo Gallery - South Luangwa Wildlife & Safari";
require_once '../includes/header.php';

// Simulated gallery data - in production, this would come from database
$gallery_categories = [
    'all' => 'All Photos',
    'wildlife' => 'Wildlife',
    'landscapes' => 'Landscapes',
    'camp' => 'Camp Life',
    'activities' => 'Safari Activities',
    'photography' => 'Photography Tours'
];

$gallery_images = [
    [
        'id' => 1,
        'category' => 'wildlife',
        'title' => 'Leopard in Acacia Tree',
        'url' => 'https://images.unsplash.com/photo-1550358864-518f202c02ba?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
        'thumb' => 'https://images.unsplash.com/photo-1550358864-518f202c02ba?ixlib=rb-4.0.3&auto=format&fit=crop&w-400&q=80',
        'alt' => 'Leopard resting in an acacia tree in South Luangwa',
        'photographer' => 'Guest: Sarah J.'
    ],
    [
        'id' => 2,
        'category' => 'wildlife',
        'title' => 'Elephant Family',
        'url' => 'https://images.unsplash.com/photo-1546182990-dffeafbe841d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
        'thumb' => 'https://images.unsplash.com/photo-1546182990-dffeafbe841d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
        'alt' => 'Elephant family crossing the Luangwa River',
        'photographer' => 'Guide: John M.'
    ],
    [
        'id' => 3,
        'category' => 'landscapes',
        'title' => 'Safari Sunset',
        'url' => 'https://images.unsplash.com/photo-1516426122078-c23e76319801?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
        'thumb' => 'https://images.unsplash.com/photo-1516426122078-c23e76319801?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
        'alt' => 'Golden hour sunset over the South Luangwa savanna',
        'photographer' => 'Guest: Michael T.'
    ],
    [
        'id' => 4,
        'category' => 'camp',
        'title' => 'Luxury Tent Interior',
        'url' => 'https://images.unsplash.com/photo-1564349683136-77e08dba1ef7?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
        'thumb' => 'https://images.unsplash.com/photo-1564349683136-77e08dba1ef7?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
        'alt' => 'Interior of luxury safari tent at Mbowo Camp',
        'photographer' => 'Camp Photographer'
    ],
    [
        'id' => 5,
        'category' => 'activities',
        'title' => 'Walking Safari',
        'url' => 'https://images.unsplash.com/photo-1516483638261-f4dbaf036963?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
        'thumb' => 'https://images.unsplash.com/photo-1516483638261-f4dbaf036963?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
        'alt' => 'Guests on guided walking safari',
        'photographer' => 'Guide: David C.'
    ],
    [
        'id' => 6,
        'category' => 'photography',
        'title' => 'Photography Session',
        'url' => 'https://images.unsplash.com/photo-1550358864-518f202c02ba?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
        'thumb' => 'https://images.unsplash.com/photo-1550358864-518f202c02ba?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
        'alt' => 'Photography guest capturing wildlife',
        'photographer' => 'Guest: Maria R.'
    ],
    [
        'id' => 7,
        'category' => 'wildlife',
        'title' => 'Lion Pride',
        'url' => 'https://images.unsplash.com/photo-1546182990-dffeafbe841d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
        'thumb' => 'https://images.unsplash.com/photo-1546182990-dffeafbe841d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
        'alt' => 'Lion pride resting in the shade',
        'photographer' => 'Guest: David C.'
    ],
    [
        'id' => 8,
        'category' => 'landscapes',
        'title' => 'Morning Mist',
        'url' => 'https://images.unsplash.com/photo-1516426122078-c23e76319801?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
        'thumb' => 'https://images.unsplash.com/photo-1516426122078-c23e76319801?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
        'alt' => 'Morning mist over the Luangwa River',
        'photographer' => 'Guide: John M.'
    ]
];

// Get category from URL
$current_category = $_GET['category'] ?? 'all';
?>

<!-- Gallery Hero -->
<section class="hero-section" style="background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1516426122078-c23e76319801?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');">
    <div class="container">
        <div class="hero-content" data-aos="fade-up">
            <h1>South Luangwa Gallery</h1>
            <p class="hero-tagline">Capturing the Beauty of Zambia's Wildlife and Wilderness</p>
            
            <div class="hero-buttons">
                <a href="#gallery-grid" class="btn btn-primary btn-large">
                    <i class="fas fa-images"></i> View Gallery
                </a>
                <a href="#categories" class="btn btn-secondary btn-large">
                    <i class="fas fa-filter"></i> Filter Categories
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Introduction -->
<section class="section">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Through the Lens</h2>
            <p>Explore stunning images captured by our guests and guides in South Luangwa National Park</p>
        </div>
        
        <div class="gallery-stats" data-aos="fade-up">
            <div class="stat">
                <div class="stat-number"><?php echo count($gallery_images); ?></div>
                <div class="stat-label">Photos in Gallery</div>
            </div>
            <div class="stat">
                <div class="stat-number">6</div>
                <div class="stat-label">Categories</div>
            </div>
            <div class="stat">
                <div class="stat-number">50+</div>
                <div class="stat-label">Wildlife Species</div>
            </div>
            <div class="stat">
                <div class="stat-number">2020-2024</div>
                <div class="stat-label">Time Span</div>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Categories -->
<section class="section bg-light" id="categories">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Gallery Categories</h2>
            <p>Browse photos by category or view all images</p>
        </div>
        
        <div class="category-filters" data-aos="fade-up">
            <?php foreach($gallery_categories as $key => $category): ?>
                <a href="?category=<?php echo $key; ?>" 
                   class="category-filter <?php echo $current_category === $key ? 'active' : ''; ?>"
                   data-category="<?php echo $key; ?>">
                    <div class="category-icon">
                        <?php 
                        $icons = [
                            'all' => 'fas fa-images',
                            'wildlife' => 'fas fa-paw',
                            'landscapes' => 'fas fa-mountain',
                            'camp' => 'fas fa-campground',
                            'activities' => 'fas fa-hiking',
                            'photography' => 'fas fa-camera'
                        ];
                        ?>
                        <i class="<?php echo $icons[$key]; ?>"></i>
                    </div>
                    <span><?php echo $category; ?></span>
                </a>
            <?php endforeach; ?>
        </div>
        
        <div class="category-info" data-aos="fade-up">
            <h3><?php echo $gallery_categories[$current_category]; ?> Photos</h3>
            <p id="category-description">
                <?php 
                $descriptions = [
                    'all' => 'All photos from our collection showing wildlife, landscapes, camp life, and safari activities.',
                    'wildlife' => 'Close encounters with South Luangwa\'s magnificent wildlife including leopards, lions, elephants, and more.',
                    'landscapes' => 'Breathtaking landscapes, sunsets, and scenic views of the Luangwa Valley.',
                    'camp' => 'Our luxury camp accommodations, facilities, and camp life experiences.',
                    'activities' => 'Guests enjoying game drives, walking safaris, and other safari activities.',
                    'photography' => 'Special moments captured during our photography safaris and workshops.'
                ];
                echo $descriptions[$current_category];
                ?>
            </p>
        </div>
    </div>
</section>

<!-- Gallery Grid -->
<section class="section" id="gallery-grid">
    <div class="container">
        <div class="gallery-container" data-aos="fade-up">
            <?php 
            // Filter images by category
            $filtered_images = array_filter($gallery_images, function($image) use ($current_category) {
                return $current_category === 'all' || $image['category'] === $current_category;
            });
            
            if (empty($filtered_images)): ?>
                <div class="no-images">
                    <i class="fas fa-images fa-3x"></i>
                    <h3>No Images Found</h3>
                    <p>No photos available for this category yet.</p>
                </div>
            <?php else: ?>
                <div class="masonry-grid">
                    <?php foreach($filtered_images as $image): ?>
                        <div class="masonry-item" data-category="<?php echo $image['category']; ?>">
                            <div class="gallery-item">
                                <img src="<?php echo $image['thumb']; ?>" 
                                     alt="<?php echo $image['alt']; ?>"
                                     data-full="<?php echo $image['url']; ?>"
                                     class="gallery-image">
                                
                                <div class="gallery-overlay">
                                    <div class="overlay-content">
                                        <h4><?php echo $image['title']; ?></h4>
                                        <p><?php echo $image['photographer']; ?></p>
                                        <div class="overlay-actions">
                                            <button class="view-btn" data-id="<?php echo $image['id']; ?>">
                                                <i class="fas fa-expand"></i> View
                                            </button>
                                            <button class="share-btn" data-id="<?php echo $image['id']; ?>">
                                                <i class="fas fa-share"></i> Share
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($filtered_images)): ?>
        <div class="gallery-actions" data-aos="fade-up">
            <div class="action-buttons">
                <button id="loadMore" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Load More Photos
                </button>
                <button id="downloadGallery" class="btn btn-outline">
                    <i class="fas fa-download"></i> Download Gallery Guide
                </button>
            </div>
            
            <div class="view-options">
                <span>View:</span>
                <button class="view-option active" data-view="grid">
                    <i class="fas fa-th"></i> Grid
                </button>
                <button class="view-option" data-view="masonry">
                    <i class="fas fa-th-large"></i> Masonry
                </button>
                <button class="view-option" data-view="slideshow">
                    <i class="fas fa-play"></i> Slideshow
                </button>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Featured Albums -->
<section class="section bg-dark">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2 style="color: var(--white);">Featured Albums</h2>
            <p style="color: var(--safari-sand);">Special collections curated by our photography team</p>
        </div>
        
        <div class="albums-grid" data-aos="fade-up">
            <div class="album-card">
                <div class="album-cover">
                    <img src="https://images.unsplash.com/photo-1550358864-518f202c02ba?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                         alt="Leopards of Luangwa">
                    <div class="album-count">24 photos</div>
                </div>
                <div class="album-info">
                    <h3>Leopards of Luangwa</h3>
                    <p>A collection showcasing South Luangwa's famous leopard population</p>
                    <a href="#" class="btn btn-outline">View Album</a>
                </div>
            </div>
            
            <div class="album-card">
                <div class="album-cover">
                    <img src="https://images.unsplash.com/photo-1546182990-dffeafbe841d?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                         alt="Elephant Families">
                    <div class="album-count">18 photos</div>
                </div>
                <div class="album-info">
                    <h3>Elephant Families</h3>
                    <p>Documenting the social structures of elephant herds in the valley</p>
                    <a href="#" class="btn btn-outline">View Album</a>
                </div>
            </div>
            
            <div class="album-card">
                <div class="album-cover">
                    <img src="https://images.unsplash.com/photo-1516426122078-c23e76319801?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                         alt="Golden Hours">
                    <div class="album-count">32 photos</div>
                </div>
                <div class="album-info">
                    <h3>Golden Hours</h3>
                    <p>Sunrise and sunset moments captured across the seasons</p>
                    <a href="#" class="btn btn-outline">View Album</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Guest Photos -->
<section class="section">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Guest Photos</h2>
            <p>Amazing images captured by our photography safari guests</p>
        </div>
        
        <div class="guest-gallery" data-aos="fade-up">
            <div class="guest-photo">
                <img src="https://images.unsplash.com/photo-1550358864-518f202c02ba?ixlib=rb-4.0.3&auto=format&fit=crop&w-400&q=80" 
                     alt="Guest Photo 1">
                <div class="guest-info">
                    <h4>Sarah Johnson</h4>
                    <p>"My first leopard sighting - unforgettable!"</p>
                    <small>Photography Safari Guest</small>
                </div>
            </div>
            
            <div class="guest-photo">
                <img src="https://images.unsplash.com/photo-1546182990-dffeafbe841d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                     alt="Guest Photo 2">
                <div class="guest-info">
                    <h4>Michael Chen</h4>
                    <p>"The golden light made this shot magical"</p>
                    <small>Wildlife Photographer</small>
                </div>
            </div>
            
            <div class="guest-photo">
                <img src="https://images.unsplash.com/photo-1516426122078-c23e76319801?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                     alt="Guest Photo 3">
                <div class="guest-info">
                    <h4>Maria Rodriguez</h4>
                    <p>"Captured during our night drive"</p>
                    <small>Family Safari Guest</small>
                </div>
            </div>
            
            <div class="guest-photo">
                <img src="https://images.unsplash.com/photo-1564349683136-77e08dba1ef7?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                     alt="Guest Photo 4">
                <div class="guest-info">
                    <h4>David Thompson</h4>
                    <p>"The camp at sunrise was breathtaking"</p>
                    <small>Research Program Participant</small>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-3">
            <a href="/pages/contact.php" class="btn btn-primary">
                <i class="fas fa-camera"></i> Submit Your Photos
            </a>
        </div>
    </div>
</section>

<!-- Lightbox Modal -->
<div class="lightbox-modal" id="lightboxModal">
    <div class="lightbox-content">
        <button class="lightbox-close">
            <i class="fas fa-times"></i>
        </button>
        
        <div class="lightbox-nav">
            <button class="nav-btn prev-btn">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="nav-btn next-btn">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        
        <div class="lightbox-image-container">
            <img id="lightboxImage" src="" alt="">
        </div>
        
        <div class="lightbox-info">
            <h3 id="lightboxTitle"></h3>
            <p id="lightboxPhotographer"></p>
            <div class="lightbox-actions">
                <button id="downloadImage" class="btn btn-outline">
                    <i class="fas fa-download"></i> Download
                </button>
                <button id="shareImage" class="btn btn-outline">
                    <i class="fas fa-share"></i> Share
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Gallery Page Styles -->
<style>
    .gallery-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 2rem;
        max-width: 800px;
        margin: 0 auto;
    }
    
    .gallery-stats .stat {
        text-align: center;
        padding: 1.5rem;
        background: var(--white);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
    }
    
    .gallery-stats .stat-number {
        font-size: 2rem;
        font-weight: bold;
        color: var(--sunset-orange);
        margin-bottom: 0.5rem;
    }
    
    .gallery-stats .stat-label {
        font-size: 0.9rem;
        color: var(--charcoal);
    }
    
    /* Category Filters */
    .category-filters {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .category-filter {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 1rem;
        background: var(--white);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
        text-align: center;
        min-width: 120px;
    }
    
    .category-filter:hover,
    .category-filter.active {
        background: var(--savanna-green);
        color: white;
        transform: translateY(-5px);
    }
    
    .category-filter.active .category-icon {
        background: var(--white);
        color: var(--savanna-green);
    }
    
    .category-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: var(--light-gray);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .category-filter.active .category-icon i {
        color: var(--savanna-green);
    }
    
    .category-info {
        text-align: center;
        max-width: 800px;
        margin: 0 auto;
    }
    
    /* Masonry Grid */
    .masonry-grid {
        column-count: 3;
        column-gap: 1rem;
    }
    
    .masonry-item {
        break-inside: avoid;
        margin-bottom: 1rem;
    }
    
    .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: var(--radius-md);
        cursor: pointer;
    }
    
    .gallery-image {
        width: 100%;
        height: auto;
        display: block;
        transition: transform 0.5s ease;
    }
    
    .gallery-item:hover .gallery-image {
        transform: scale(1.05);
    }
    
    .gallery-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0) 60%);
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        align-items: flex-end;
        padding: 1rem;
    }
    
    .gallery-item:hover .gallery-overlay {
        opacity: 1;
    }
    
    .overlay-content {
        color: white;
        width: 100%;
    }
    
    .overlay-content h4 {
        color: white;
        margin-bottom: 0.25rem;
        font-size: 1rem;
    }
    
    .overlay-content p {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-bottom: 0.5rem;
    }
    
    .overlay-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .view-btn,
    .share-btn {
        background: rgba(255,255,255,0.2);
        color: white;
        border: none;
        padding: 0.25rem 0.75rem;
        border-radius: 4px;
        font-size: 0.8rem;
        cursor: pointer;
        transition: background 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .view-btn:hover {
        background: var(--sunset-orange);
    }
    
    .share-btn:hover {
        background: var(--savanna-green);
    }
    
    .no-images {
        text-align: center;
        padding: 4rem;
        color: var(--dark-gray);
    }
    
    .no-images i {
        color: var(--light-gray);
        margin-bottom: 1rem;
    }
    
    /* Gallery Actions */
    .gallery-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #eee;
    }
    
    .action-buttons {
        display: flex;
        gap: 1rem;
    }
    
    .view-options {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .view-option {
        background: var(--white);
        border: 1px solid #ddd;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .view-option.active,
    .view-option:hover {
        background: var(--savanna-green);
        color: white;
        border-color: var(--savanna-green);
    }
    
    /* Albums */
    .albums-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
        color: var(--white);
    }
    
    .album-card {
        background: rgba(255,255,255,0.1);
        border-radius: var(--radius-lg);
        overflow: hidden;
        backdrop-filter: blur(10px);
    }
    
    .album-cover {
        position: relative;
        height: 200px;
    }
    
    .album-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .album-count {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: var(--sunset-orange);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
    }
    
    .album-info {
        padding: 1.5rem;
    }
    
    .album-info h3 {
        color: var(--white);
        margin-bottom: 0.5rem;
    }
    
    .album-info p {
        opacity: 0.9;
        margin-bottom: 1rem;
        font-size: 0.9rem;
    }
    
    .album-info .btn-outline {
        color: white;
        border-color: white;
    }
    
    .album-info .btn-outline:hover {
        background: white;
        color: var(--charcoal);
    }
    
    /* Guest Gallery */
    .guest-gallery {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
    }
    
    .guest-photo {
        position: relative;
        border-radius: var(--radius-md);
        overflow: hidden;
    }
    
    .guest-photo img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    
    .guest-info {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        color: white;
        padding: 1rem;
        transform: translateY(100%);
        transition: transform 0.3s ease;
    }
    
    .guest-photo:hover .guest-info {
        transform: translateY(0);
    }
    
    .guest-info h4 {
        color: white;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }
    
    .guest-info p {
        font-size: 0.8rem;
        margin-bottom: 0.25rem;
    }
    
    .guest-info small {
        font-size: 0.7rem;
        opacity: 0.7;
    }
    
    /* Lightbox Modal */
    .lightbox-modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.9);
        z-index: 10000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }
    
    .lightbox-modal.active {
        display: flex;
    }
    
    .lightbox-content {
        position: relative;
        max-width: 90%;
        max-height: 90%;
        background: var(--charcoal);
        border-radius: var(--radius-lg);
        overflow: hidden;
    }
    
    .lightbox-close {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(0,0,0,0.5);
        color: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        z-index: 10001;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    
    .lightbox-nav {
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        transform: translateY(-50%);
        display: flex;
        justify-content: space-between;
        padding: 0 1rem;
        z-index: 10001;
    }
    
    .nav-btn {
        background: rgba(0,0,0,0.5);
        color: white;
        border: none;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        transition: background 0.3s ease;
    }
    
    .nav-btn:hover {
        background: var(--sunset-orange);
    }
    
    .lightbox-image-container {
        max-height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }
    
    #lightboxImage {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    
    .lightbox-info {
        padding: 1.5rem;
        background: var(--charcoal);
        color: white;
        text-align: center;
    }
    
    .lightbox-info h3 {
        color: white;
        margin-bottom: 0.5rem;
    }
    
    .lightbox-actions {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-top: 1rem;
    }
    
    .lightbox-actions .btn-outline {
        color: white;
        border-color: white;
    }
    
    .lightbox-actions .btn-outline:hover {
        background: white;
        color: var(--charcoal);
    }
    
    @media (max-width: 1024px) {
        .masonry-grid {
            column-count: 2;
        }
        
        .gallery-stats {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .albums-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .guest-gallery {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .masonry-grid {
            column-count: 1;
        }
        
        .gallery-stats {
            grid-template-columns: 1fr;
        }
        
        .category-filters {
            flex-direction: column;
            align-items: center;
        }
        
        .category-filter {
            width: 200px;
        }
        
        .gallery-actions {
            flex-direction: column;
            gap: 1rem;
        }
        
        .albums-grid {
            grid-template-columns: 1fr;
        }
        
        .guest-gallery {
            grid-template-columns: 1fr;
        }
        
        .lightbox-content {
            max-width: 95%;
            max-height: 95%;
        }
        
        .lightbox-image-container {
            padding: 1rem;
        }
    }
</style>

<!-- Gallery JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gallery filtering
        const categoryFilters = document.querySelectorAll('.category-filter');
        const galleryItems = document.querySelectorAll('.masonry-item');
        
        categoryFilters.forEach(filter => {
            filter.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Update active filter
                categoryFilters.forEach(f => f.classList.remove('active'));
                this.classList.add('active');
                
                const category = this.dataset.category;
                
                // Filter items
                galleryItems.forEach(item => {
                    if (category === 'all' || item.dataset.category === category) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
                
                // Update URL
                history.pushState(null, '', `?category=${category}`);
                
                // Update category description
                updateCategoryDescription(category);
            });
        });
        
        // Lightbox functionality
        const lightboxModal = document.getElementById('lightboxModal');
        const lightboxImage = document.getElementById('lightboxImage');
        const lightboxTitle = document.getElementById('lightboxTitle');
        const lightboxPhotographer = document.getElementById('lightboxPhotographer');
        const closeBtn = document.querySelector('.lightbox-close');
        const prevBtn = document.querySelector('.prev-btn');
        const nextBtn = document.querySelector('.next-btn');
        const viewBtns = document.querySelectorAll('.view-btn');
        const shareBtns = document.querySelectorAll('.share-btn');
        
        let currentImageIndex = 0;
        const images = <?php echo json_encode($filtered_images); ?>;
        
        // Open lightbox
        viewBtns.forEach((btn, index) => {
            btn.addEventListener('click', function() {
                const id = parseInt(this.dataset.id);
                currentImageIndex = images.findIndex(img => img.id === id);
                openLightbox(currentImageIndex);
            });
        });
        
        // Open lightbox when clicking gallery image
        document.querySelectorAll('.gallery-image').forEach((img, index) => {
            img.addEventListener('click', function() {
                const id = parseInt(this.closest('.gallery-item').querySelector('.view-btn').dataset.id);
                currentImageIndex = images.findIndex(img => img.id === id);
                openLightbox(currentImageIndex);
            });
        });
        
        // Close lightbox
        closeBtn.addEventListener('click', closeLightbox);
        lightboxModal.addEventListener('click', function(e) {
            if (e.target === lightboxModal) {
                closeLightbox();
            }
        });
        
        // Navigation
        prevBtn.addEventListener('click', showPrevImage);
        nextBtn.addEventListener('click', showNextImage);
        
        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (lightboxModal.classList.contains('active')) {
                if (e.key === 'Escape') closeLightbox();
                if (e.key === 'ArrowLeft') showPrevImage();
                if (e.key === 'ArrowRight') showNextImage();
            }
        });
        
        // View options
        const viewOptions = document.querySelectorAll('.view-option');
        const masonryGrid = document.querySelector('.masonry-grid');
        
        viewOptions.forEach(option => {
            option.addEventListener('click', function() {
                viewOptions.forEach(o => o.classList.remove('active'));
                this.classList.add('active');
                
                const view = this.dataset.view;
                
                if (view === 'grid') {
                    masonryGrid.style.columnCount = '3';
                } else if (view === 'masonry') {
                    masonryGrid.style.columnCount = '3';
                } else if (view === 'slideshow') {
                    // Start slideshow
                    startSlideshow();
                }
            });
        });
        
        // Load more button
        const loadMoreBtn = document.getElementById('loadMore');
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', function() {
                // In production, this would load more images from server
                this.innerHTML = '<i class="fas fa-sync fa-spin"></i> Loading...';
                
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-plus"></i> Load More Photos';
                    alert('More photos would be loaded in production implementation.');
                }, 1000);
            });
        }
        
        // Download gallery guide
        const downloadBtn = document.getElementById('downloadGallery');
        if (downloadBtn) {
            downloadBtn.addEventListener('click', function() {
                window.open('/assets/downloads/mbowo-camp-gallery-guide.pdf', '_blank');
            });
        }
        
        // Helper functions
        function openLightbox(index) {
            const image = images[index];
            lightboxImage.src = image.url;
            lightboxTitle.textContent = image.title;
            lightboxPhotographer.textContent = image.photographer;
            lightboxModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function closeLightbox() {
            lightboxModal.classList.remove('active');
            document.body.style.overflow = '';
        }
        
        function showPrevImage() {
            currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
            openLightbox(currentImageIndex);
        }
        
        function showNextImage() {
            currentImageIndex = (currentImageIndex + 1) % images.length;
            openLightbox(currentImageIndex);
        }
        
        function updateCategoryDescription(category) {
            const descriptions = {
                'all': 'All photos from our collection showing wildlife, landscapes, camp life, and safari activities.',
                'wildlife': 'Close encounters with South Luangwa\'s magnificent wildlife including leopards, lions, elephants, and more.',
                'landscapes': 'Breathtaking landscapes, sunsets, and scenic views of the Luangwa Valley.',
                'camp': 'Our luxury camp accommodations, facilities, and camp life experiences.',
                'activities': 'Guests enjoying game drives, walking safaris, and other safari activities.',
                'photography': 'Special moments captured during our photography safaris and workshops.'
            };
            
            document.getElementById('category-description').textContent = descriptions[category];
        }
        
        function startSlideshow() {
            // Simple slideshow implementation
            let slideIndex = 0;
            const slides = document.querySelectorAll('.gallery-image');
            
            // Hide all slides
            slides.forEach(slide => {
                slide.style.display = 'none';
            });
            
            // Show first slide
            if (slides.length > 0) {
                slides[0].style.display = 'block';
            }
            
            // Auto advance slides
            setInterval(() => {
                slides.forEach(slide => slide.style.display = 'none');
                slideIndex = (slideIndex + 1) % slides.length;
                slides[slideIndex].style.display = 'block';
            }, 3000);
        }
        
        // Share functionality
        shareBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = parseInt(this.dataset.id);
                const image = images.find(img => img.id === id);
                
                if (navigator.share) {
                    navigator.share({
                        title: image.title,
                        text: 'Check out this amazing photo from Mbowo Camp Safari!',
                        url: window.location.href
                    });
                } else {
                    // Fallback for browsers without Web Share API
                    const shareUrl = `${window.location.origin}/pages/gallery.php?photo=${id}`;
                    navigator.clipboard.writeText(shareUrl).then(() => {
                        alert('Link copied to clipboard!');
                    });
                }
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
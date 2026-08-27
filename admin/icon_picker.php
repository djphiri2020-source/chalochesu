<?php
// This is a simplified list. A full list would be very long.
// You can add any Font Awesome 6 Free icons to this array.
$fa_icons = [
    'fas fa-leaf', 'fas fa-tree', 'fas fa-seedling', 'fas fa-solar-panel', 'fas fa-wind', 'fas fa-water',
    'fas fa-recycle', 'fas fa-globe-africa', 'fas fa-mountain', 'fas fa-smog', 'fas fa-cloud-sun-rain',
    'fas fa-fire', 'fas fa-industry', 'fas fa-building', 'fas fa-city', 'fas fa-road', 'fas fa-broadcast-tower',
    'fas fa-car-side', 'fas fa-truck', 'fas fa-tractor', 'fas fa-gem', 'fas fa-bolt', 'fas fa-vial',
    'fas fa-flask', 'fas fa-microscope', 'fas fa-ruler-combined', 'fas fa-hard-hat', 'fas fa-tools',
    'fas fa-chart-line', 'fas fa-chart-bar', 'fas fa-chart-pie', 'fas fa-file-alt', 'fas fa-file-contract',
    'fas fa-tasks', 'fas fa-clipboard-check', 'fas fa-search', 'fas fa-map', 'fas fa-map-marker-alt',
    'fas fa-users', 'fas fa-user-tie', 'fas fa-handshake', 'fas fa-hand-holding-usd', 'fas fa-landmark',
    'fas fa-university', 'fas fa-balance-scale', 'fas fa-gavel', 'fas fa-book', 'fas fa-award',
    'fas fa-star', 'fas fa-check-circle', 'fas fa-times-circle', 'fas fa-info-circle', 'fas fa-question-circle',
    'fas fa-concierge-bell', 'fas fa-box-open', 'fas fa-comment-dots', 'fas fa-users-cog', 'fas fa-envelope-open-text',
    'fas fa-at', 'fas fa-cogs', 'fas fa-tags', 'fas fa-newspaper', 'fas fa-tachometer-alt', 'fas fa-edit', 'fas fa-trash'
];
sort($fa_icons);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select an Icon</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            padding: 1rem;
            background-color: #f8f9fa;
        }
        #search-container {
            position: sticky;
            top: 0;
            background: #f8f9fa;
            padding: 1rem 0;
            z-index: 10;
        }
        #icon-search {
            width: 100%;
            padding: 0.75rem;
            font-size: 1rem;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            box-sizing: border-box;
        }
        #icon-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        .icon-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            text-align: center;
        }
        .icon-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            color: #007bff;
        }
        .icon-item i {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        .icon-item span {
            font-size: 0.8rem;
            color: #6c757d;
            word-break: break-all;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>

    <div id="search-container">
        <input type="text" id="icon-search" placeholder="Search for an icon...">
    </div>

    <div id="icon-grid">
        <?php foreach ($fa_icons as $icon): ?>
            <div class="icon-item" data-icon-class="<?php echo htmlspecialchars($icon); ?>">
                <i class="<?php echo htmlspecialchars($icon); ?>"></i>
                <span><?php echo htmlspecialchars(str_replace('fas fa-', '', $icon)); ?></span>
            </div>
        <?php endforeach; ?>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('icon-search');
    const iconItems = document.querySelectorAll('.icon-item');
    const params = new URLSearchParams(window.location.search);
    const inputId = params.get('inputId');
    const previewId = params.get('previewId');

    // Handle icon selection
    iconItems.forEach(item => {
        item.addEventListener('click', function() {
            const iconClass = this.dataset.iconClass;

            // Pass the value back to the parent window's form
            if (window.opener && !window.opener.closed) {
                const openerInput = window.opener.document.getElementById(inputId);
                const openerPreview = window.opener.document.getElementById(previewId);

                if (openerInput) {
                    openerInput.value = iconClass;
                }
                if (openerPreview) {
                    openerPreview.className = iconClass;
                }
                
                // Trigger a change event for any other scripts that might be listening
                var event = new Event('change', { bubbles: true });
                openerInput.dispatchEvent(event);
            }
            window.close();
        });
    });

    // Handle search/filtering
    searchInput.addEventListener('keyup', function() {
        const filter = searchInput.value.toLowerCase();
        iconItems.forEach(item => {
            const iconName = item.dataset.iconClass.toLowerCase();
            if (iconName.includes(filter)) {
                item.classList.remove('hidden');
            } else {
                item.classList.add('hidden');
            }
        });
    });
});
</script>

</body>
</html>
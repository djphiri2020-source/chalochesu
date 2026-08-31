<?php
// includes/navigation.php
$current_path = $_SERVER['REQUEST_URI'];
$nav_links = [
    'Home' => BASE_URL . '/pages/index.php',
    'About' => BASE_URL . '/pages/about.php',
    'Services' => BASE_URL . '/pages/services.php',
    'Solutions' => BASE_URL . '/pages/products.php',
    'Insights' => BASE_URL . '/pages/blog.php',
    'Partners' => BASE_URL . '/pages/partners.php',
    'Contact' => BASE_URL . '/pages/contact.php',
];
function render_nav_links($links, $current_path, $is_mobile = false) {
    $link_class = $is_mobile ? 'mobile-nav-link' : 'nav-link';
    foreach ($links as $title => $url) {
        $path = parse_url($url, PHP_URL_PATH);
        $is_active = ($current_path === $path) || (basename($current_path) === 'index.php' && $title === 'Home');
        echo '<li><a href="' . htmlspecialchars($url) . '" class="' . $link_class . ' ' . ($is_active ? 'active' : '') . '">' . htmlspecialchars($title) . '</a></li>';
    }
}
?>
<nav class="main-nav" aria-label="Primary navigation">
    <ul class="nav-list"><?php render_nav_links($nav_links, $current_path); ?></ul>
    <button class="mobile-menu-toggle" type="button" aria-label="Open menu" aria-expanded="false"><i class="fas fa-bars"></i></button>
</nav>
<div class="mobile-nav" aria-hidden="true">
    <div class="mobile-nav-header">
        <div class="mobile-logo"><a href="<?php echo BASE_URL; ?>/"><img src="<?php echo BASE_URL; ?>/<?php echo htmlspecialchars($site_logo_path); ?>" alt="<?php echo htmlspecialchars($site_name); ?>"></a></div>
        <button class="mobile-close" type="button" aria-label="Close menu"><i class="fas fa-times"></i></button>
    </div>
    <ul class="mobile-nav-list"><?php render_nav_links($nav_links, $current_path, true); ?></ul>
    <div class="mobile-nav-footer"><a href="<?php echo BASE_URL; ?>/pages/contact.php" class="btn btn-primary"><i class="fas fa-envelope"></i> Start a Conversation</a></div>
</div>
<style>
.nav-list{align-items:center}.nav-link{font-size:.82rem;letter-spacing:.02em;padding:.6rem .75rem;border-radius:0}.nav-link:hover{background:transparent}.nav-link.active{border-bottom:2px solid var(--primary-green)}
.mobile-menu-toggle,.mobile-close{border:0;background:transparent;cursor:pointer}.mobile-logo img{height:40px;width:auto}.mobile-nav.active{left:0}body.menu-open{overflow:hidden}
</style>
<script>
document.addEventListener('DOMContentLoaded',function(){const t=document.querySelector('.mobile-menu-toggle'),n=document.querySelector('.mobile-nav'),c=document.querySelector('.mobile-close');if(!t||!n)return;function close(){n.classList.remove('active');n.setAttribute('aria-hidden','true');t.setAttribute('aria-expanded','false');document.body.classList.remove('menu-open')}t.addEventListener('click',function(){const open=!n.classList.contains('active');n.classList.toggle('active',open);n.setAttribute('aria-hidden',String(!open));t.setAttribute('aria-expanded',String(open));document.body.classList.toggle('menu-open',open)});if(c)c.addEventListener('click',close);n.querySelectorAll('a').forEach(a=>a.addEventListener('click',close));document.addEventListener('keydown',e=>{if(e.key==='Escape')close()})});
</script>
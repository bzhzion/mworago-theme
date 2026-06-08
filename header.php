<?php
/**
 * mworago 2026 — header.php
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> <?php echo is_rtl() ? 'dir="rtl"' : ''; ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script>
  (function(){
    var d=document.documentElement;
    d.classList.add('no-transition');
    var t=localStorage.getItem('mworago-theme')||(matchMedia('(prefers-color-scheme:dark)').matches?'dark':'light');
    d.setAttribute('data-theme',t);
    requestAnimationFrame(function(){requestAnimationFrame(function(){d.classList.remove('no-transition');});});
  })();
  </script>
  <style>html[data-theme="dark"],html[data-theme="dark"] body{background:#111111;color:#e5e5e5;}</style>
  <?php wp_head(); ?>
  <style>
    /* Override GP p margins dans nos composants */
    .header p,.hero p,.hero-main p,.hero-side p,.side-card p,
    .articles p,.a-card p,.newsletter p,.footer p,
    .breaking p,.single-article p,.archive-section p,
    .search-section p,.page-content p { margin:0 !important; }
  </style>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- BREAKING NEWS BAR -->
<?php
global $wpdb;
$breaking_posts = $wpdb->get_results(
  "SELECT p.ID, p.post_title, SUM(s.pageviews) AS views
   FROM {$wpdb->prefix}popularpostssummary s
   INNER JOIN {$wpdb->posts} p ON p.ID = s.postid
   WHERE p.post_status = 'publish' AND p.post_type = 'post'
     AND s.view_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
   GROUP BY p.ID
   ORDER BY views DESC
   LIMIT 5"
);
if ( empty( $breaking_posts ) ) {
  $breaking_posts = $wpdb->get_results(
    "SELECT ID, post_title FROM {$wpdb->posts}
     WHERE post_status = 'publish' AND post_type = 'post'
     ORDER BY post_date DESC LIMIT 5"
  );
}
if ( ! empty( $breaking_posts ) ) :
?>
<div class="breaking">
  <div class="breaking__inner">
    <span class="breaking__label">🔥 Hot</span>
    <div class="breaking__ticker">
      <span>
        <?php foreach ( $breaking_posts as $bp ) : ?>
          ✦ <a href="<?php echo esc_url( get_permalink( $bp->ID ) ); ?>" class="breaking__link"><?php echo esc_html( $bp->post_title ); ?></a> &nbsp;&nbsp;&nbsp;
        <?php endforeach; ?>
        <?php foreach ( $breaking_posts as $bp ) : ?>
          ✦ <a href="<?php echo esc_url( get_permalink( $bp->ID ) ); ?>" class="breaking__link"><?php echo esc_html( $bp->post_title ); ?></a> &nbsp;&nbsp;&nbsp;
        <?php endforeach; ?>
      </span>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- HEADER -->
<header class="header">
  <div class="header__inner">

    <!-- Logo -->
    <?php if ( has_custom_logo() ) :
      $logo_id   = get_theme_mod( 'custom_logo' );
      $logo_src  = wp_get_attachment_image_url( $logo_id, 'full' );
      $logo_meta = wp_get_attachment_metadata( $logo_id );
      $logo_w    = $logo_meta['width']  ?? 0;
      $logo_h    = $logo_meta['height'] ?? 0;
    ?>
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo" style="display:flex;align-items:center;">
        <img src="<?php echo esc_url( $logo_src ); ?>" alt="<?php bloginfo( 'name' ); ?>"
             width="<?php echo (int) $logo_w; ?>" height="<?php echo (int) $logo_h; ?>"
             style="height:36px;width:auto;max-height:36px;object-fit:contain;">
      </a>
    <?php else : ?>
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo">mworago<em>.</em></a>
    <?php endif; ?>

    <!-- Navigation principale -->
    <nav class="nav">
      <?php
      wp_nav_menu( [
        'theme_location' => 'primary',
        'container'      => false,
        'items_wrap'     => '%3$s',
        'walker'         => new mworago_Nav_Walker(),
        'fallback_cb'    => false,
      ] );
      ?>
    </nav>

    <!-- Burger mobile -->
    <button class="burger" id="burgerBtn" aria-label="<?php esc_attr_e( 'Ouvrir le menu', 'mworago' ); ?>" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>

    <!-- Actions header -->
    <div class="header__right">
      <form class="search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
        <input type="search" name="s" placeholder="<?php echo esc_attr( get_theme_mod( 'mworago_search_placeholder', 'BTS, aespa, IVE…' ) ); ?>" value="<?php echo get_search_query(); ?>">
      </form>

      <button class="btn-toggle" id="themeToggle" aria-label="<?php esc_attr_e( 'Changer le thème', 'mworago' ); ?>">
        <svg id="ico-sun" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none">
          <circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/>
          <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
          <line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/>
          <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
        </svg>
        <svg id="ico-moon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
        </svg>
      </button>

      <?php if ( is_user_logged_in() ) : ?>
        <a href="<?php echo esc_url( admin_url( 'profile.php' ) ); ?>" class="header-avatar" aria-label="<?php esc_attr_e( 'Mon compte', 'mworago' ); ?>">
          <?php echo get_avatar( get_current_user_id(), 32, '', '', [ 'class' => 'header-avatar__img' ] ); ?>
        </a>
      <?php else : ?>
        <?php if ( get_option( 'users_can_register' ) ) : ?>
          <a href="<?php echo esc_url( wp_registration_url() ); ?>" class="btn-auth btn-auth--register"><?php esc_html_e( 'Register', 'mworago' ); ?></a>
        <?php endif; ?>
        <a href="<?php echo esc_url( wp_login_url( get_permalink() ) ); ?>" class="btn-auth btn-auth--login"><?php esc_html_e( 'Log in', 'mworago' ); ?></a>
      <?php endif; ?>

      <?php
      $support_url   = get_theme_mod( 'mworago_support_url', '' ) ?: get_permalink( get_page_by_path( 'soutenir' ) );
      $support_label = get_theme_mod( 'mworago_support_label', __( 'Nous soutenir', 'mworago' ) );
      ?>
      <a href="<?php echo esc_url( $support_url ); ?>" class="btn-support">
        ♥&nbsp;<?php echo esc_html( $support_label ); ?>
      </a>
    </div>

  </div>
</header>

<script>
(function(){
  function setNavTop(){
    var bar=document.querySelector('.breaking');
    var h=bar?bar.getBoundingClientRect().height:0;
    document.documentElement.style.setProperty('--breaking-h',h+'px');
  }
  if(document.readyState==='loading'){document.addEventListener('DOMContentLoaded',setNavTop);}
  else{setNavTop();}
  window.addEventListener('resize',setNavTop);
})();
</script>
<!-- MOBILE NAV DRAWER -->
<div class="mobile-nav" id="mobileNav" aria-hidden="true" inert>
  <div class="mobile-nav__inner">
    <?php
    wp_nav_menu( [
      'theme_location' => 'primary',
      'container'      => false,
      'items_wrap'     => '<ul class="mobile-nav__list">%3$s</ul>',
      'fallback_cb'    => false,
    ] );
    ?>
    <div class="mobile-nav__auth">
      <?php if ( is_user_logged_in() ) : ?>
        <a href="<?php echo esc_url( admin_url( 'profile.php' ) ); ?>" class="mobile-nav__auth-link">
          <?php echo get_avatar( get_current_user_id(), 24, '', '', [ 'class' => 'header-avatar__img' ] ); ?>
          <?php esc_html_e( 'Mon compte', 'mworago' ); ?>
        </a>
      <?php else : ?>
        <?php if ( get_option( 'users_can_register' ) ) : ?>
          <a href="<?php echo esc_url( wp_registration_url() ); ?>" class="mobile-nav__auth-link"><?php esc_html_e( 'Register', 'mworago' ); ?></a>
        <?php endif; ?>
        <a href="<?php echo esc_url( wp_login_url( get_permalink() ) ); ?>" class="mobile-nav__auth-link"><?php esc_html_e( 'Log in', 'mworago' ); ?></a>
      <?php endif; ?>
    </div>
  </div>
</div>
<div class="mobile-nav__overlay" id="mobileOverlay"></div>

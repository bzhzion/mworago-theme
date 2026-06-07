<?php
/**
 * mworago 2026 — 404.php
 */
get_header();
?>

<main class="not-found">
  <div class="not-found__code" aria-hidden="true">404</div>
  <h1 class="not-found__title"><?php esc_html_e( 'Page not found', 'mworago' ); ?></h1>
  <p class="not-found__text">
    <?php esc_html_e( 'This page does not exist or has been moved. Idols move fast.', 'mworago' ); ?>
  </p>

  <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-home">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
      <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
      <polyline points="9 22 9 12 15 12 15 22"/>
    </svg>
    <?php esc_html_e( 'Back to home', 'mworago' ); ?>
  </a>

  <?php
  // Derniers articles en bas
  $recent = new WP_Query( [
      'posts_per_page' => 3,
      'post_status'    => 'publish',
      'orderby'        => 'date',
      'order'          => 'DESC',
  ] );
  if ( $recent->have_posts() ) : ?>
  <div class="not-found__recent">
    <p class="not-found__recent-label"><?php esc_html_e( 'Recent articles', 'mworago' ); ?></p>
    <div class="not-found__links">
      <?php while ( $recent->have_posts() ) : $recent->the_post(); ?>
        <a href="<?php the_permalink(); ?>" class="not-found__link">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
          <?php the_title(); ?>
        </a>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
  <?php endif; ?>
</main>

<?php get_footer(); ?>

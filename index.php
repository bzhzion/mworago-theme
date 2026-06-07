<?php
/**
 * mworago 2026 — Fallback index.php (requis par WordPress)
 * Redirige vers front-page.php ou archive selon le contexte.
 */
get_header();
?>

<main class="archive-section">
  <div class="archive-inner">
    <?php if ( have_posts() ) : ?>
      <div class="articles-grid">
        <?php while ( have_posts() ) : the_post(); ?>
        <article class="a-card<?php if ( 'private' === get_post_status() ) echo ' a-card--vip'; ?>">
          <div class="a-card__img">
            <?php if ( has_post_thumbnail() ) : ?>
              <div class="a-card__img-inner"><?php the_post_thumbnail( 'medium_large' ); ?></div>
            <?php else : ?>
              <div class="a-card__img-inner g<?php echo ( ( get_the_ID() % 7 ) + 1 ); ?>"></div>
            <?php endif; ?>
            <?php $idx_cats = get_the_category(); if ( $idx_cats ) : ?>
              <span class="a-card__badge badge"><?php echo esc_html( $idx_cats[0]->name ); ?></span>
            <?php endif; ?>
            <?php if ( 'private' === get_post_status() ) : ?>
              <span class="a-card__vip-badge badge">⭐ Exclu</span>
            <?php endif; ?>
          </div>
          <div class="a-card__body">
            <h2 class="a-card__title clamp2"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <p class="a-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
            <div class="a-card__footer">
              <span class="a-card__date"><?php echo get_the_date( '' ); ?></span>
              <span class="a-card__time">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <?php echo mworago_reading_time(); ?>
              </span>
            </div>
          </div>
        </article>
        <?php endwhile; ?>
      </div>
      <?php mworago_pagination(); ?>
    <?php else : ?>
      <p class="no-results"><?php esc_html_e( 'No articles found.', 'mworago' ); ?></p>
    <?php endif; ?>
  </div>
</main>

<?php get_footer(); ?>

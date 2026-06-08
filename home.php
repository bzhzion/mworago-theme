<?php
/**
 * mworago 2026 — home.php (blog posts listing / page des actualités)
 * Utilisé quand page_for_posts est défini et show_on_front = page.
 */
get_header();
global $wp_query;
?>

<main class="archive-section">
  <div class="archive-inner">

    <header class="archive-header">
      <p class="archive-header__eyebrow"><?php esc_html_e( 'All articles', 'mworago' ); ?></p>
      <h1 class="archive-header__title">
        <?php
        $posts_page_id = get_option( 'page_for_posts' );
        if ( $posts_page_id ) {
            echo esc_html( get_the_title( $posts_page_id ) );
        } else {
            bloginfo( 'name' );
        }
        ?>
      </h1>
      <p class="archive-header__count">
        <?php
        printf(
          _n( '%d article', '%d articles', $wp_query->found_posts, 'mworago' ),
          $wp_query->found_posts
        );
        ?>
      </p>
    </header>

    <?php if ( have_posts() ) : ?>
      <div class="articles-grid">
        <?php $mw_i = 0; while ( have_posts() ) : the_post(); $mw_i++; ?>
        <article class="a-card reveal<?php if ( 'private' === get_post_status() ) echo ' a-card--vip'; ?>">
          <div class="a-card__img">
            <?php if ( has_post_thumbnail() ) : ?>
              <div class="a-card__img-inner"><?php the_post_thumbnail( 'medium_large' ); ?></div>
            <?php else : ?>
              <div class="a-card__img-inner g<?php echo ( ( get_the_ID() % 7 ) + 1 ); ?>"></div>
            <?php endif; ?>
            <?php $cats = get_the_category(); if ( $cats ) : ?>
              <span class="a-card__badge badge"><?php echo esc_html( $cats[0]->name ); ?></span>
            <?php endif; ?>
            <?php if ( 'private' === get_post_status() ) : ?>
              <span class="a-card__vip-badge badge">⭐ Exclu</span>
            <?php endif; ?>
          </div>
          <div class="a-card__body">
            <h2 class="a-card__title clamp2">
              <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h2>
            <p class="a-card__excerpt">
              <?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?>
            </p>
            <div class="a-card__footer">
              <span class="a-card__date"><?php echo get_the_date( '' ); ?></span>
              <span class="a-card__time"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg><?php echo mworago_reading_time(); ?></span>
            </div>
          </div>
        </article>
        <?php if ( $mw_i === 3 ) mworago_ad( 'mworago_ad_grid', 'mw-ad--grid-span' ); ?>
        <?php endwhile; ?>
      </div>
      <?php mworago_pagination(); ?>
    <?php else : ?>
      <p class="no-results"><?php esc_html_e( 'No articles found.', 'mworago' ); ?></p>
    <?php endif; ?>

  </div>
</main>

<?php get_footer(); ?>

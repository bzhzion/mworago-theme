<?php
/**
 * mworago 2026 — archive.php (archives date, auteur, tag...)
 */
get_header();
?>

<main class="archive-section">
  <div class="archive-inner">

    <header class="archive-header">
      <p class="archive-header__eyebrow">
        <?php
        if ( is_day() )        esc_html_e( 'Day', 'mworago' );
        elseif ( is_month() )  esc_html_e( 'Month', 'mworago' );
        elseif ( is_year() )   esc_html_e( 'Year', 'mworago' );
        elseif ( is_author() ) esc_html_e( 'Author', 'mworago' );
        elseif ( is_tag() )    esc_html_e( 'Tag', 'mworago' );
        else                   esc_html_e( 'Archive', 'mworago' );
        ?>
      </p>
      <h1 class="archive-header__title">
        <?php the_archive_title(); ?>
      </h1>
      <?php
      $desc = get_the_archive_description();
      if ( $desc ) : ?>
        <p class="archive-header__count"><?php echo wp_kses_post( $desc ); ?></p>
      <?php endif; ?>
      <?php if ( is_author() ) : ?>
        <p class="archive-header__count">
          <?php
          global $wp_query;
          printf(
            esc_html( _n( '%d article', '%d articles', $wp_query->found_posts, 'mworago' ) ),
            $wp_query->found_posts
          );
          ?>
        </p>
      <?php endif; ?>
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

<?php
/**
 * mworago 2026 — category.php
 */
get_header();

$category = get_queried_object();
?>

<main class="archive-section">
  <div class="archive-inner">

    <header class="archive-header">
      <p class="archive-header__eyebrow"><?php esc_html_e( 'Category', 'mworago' ); ?></p>
      <h1 class="archive-header__title"><?php single_cat_title(); ?></h1>
      <?php
      $desc = category_description();
      if ( $desc ) : ?>
        <p class="archive-header__count"><?php echo wp_kses_post( $desc ); ?></p>
      <?php else : ?>
        <p class="archive-header__count">
          <?php
          global $wp_query;
          printf(
            _n( '%d article', '%d articles', $wp_query->found_posts, 'mworago' ),
            $wp_query->found_posts
          );
          ?>
        </p>
      <?php endif; ?>
    </header>

    <?php if ( have_posts() ) : ?>

      <?php
      // Premier article en hero
      the_post();
      $first = get_post();
      $cats  = get_the_category( $first->ID );
      ?>
      <article class="cat-hero">
        <a href="<?php echo esc_url( get_permalink( $first ) ); ?>" class="cat-hero__img-link" tabindex="-1" aria-hidden="true">
        <?php if ( has_post_thumbnail( $first ) ) : ?>
          <div class="cat-hero__img">
            <?php echo get_the_post_thumbnail( $first, 'large' ); ?>
          </div>
        <?php else : ?>
          <div class="cat-hero__img cat-hero__img--gradient g<?php echo ( ( $first->ID % 7 ) + 1 ); ?>"></div>
        <?php endif; ?>
        </a>
        <div class="cat-hero__body">
          <?php if ( $cats ) : ?>
            <span class="badge"><?php echo esc_html( $cats[0]->name ); ?></span>
          <?php endif; ?>
          <h2 class="cat-hero__title">
            <a href="<?php echo esc_url( get_permalink( $first ) ); ?>">
              <?php echo esc_html( get_the_title( $first ) ); ?>
            </a>
          </h2>
          <p class="cat-hero__excerpt">
            <?php echo esc_html( wp_trim_words( get_the_excerpt( $first ), 30 ) ); ?>
          </p>
          <div class="cat-hero__meta">
            <span><?php echo esc_html( get_the_date( '', $first ) ); ?></span>
            <span>·</span>
            <span><?php echo mworago_reading_time(); ?></span>
          </div>
        </div>
      </article>

      <?php if ( have_posts() ) : ?>
      <div class="articles-grid" style="margin-top:var(--sp-xxl)">
        <?php $mw_i = 0; while ( have_posts() ) : the_post(); $mw_i++; ?>
        <article class="a-card reveal<?php if ( 'private' === get_post_status() ) echo ' a-card--vip'; ?>">
          <div class="a-card__img">
            <?php if ( has_post_thumbnail() ) : ?>
              <div class="a-card__img-inner"><?php the_post_thumbnail( 'medium_large' ); ?></div>
            <?php else : ?>
              <div class="a-card__img-inner g<?php echo ( ( get_the_ID() % 7 ) + 1 ); ?>"></div>
            <?php endif; ?>
            <?php $cats2 = get_the_category(); if ( $cats2 ) : ?>
              <span class="a-card__badge badge"><?php echo esc_html( $cats2[0]->name ); ?></span>
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
        <?php if ( $mw_i === 2 ) mworago_ad( 'mworago_ad_grid', 'mw-ad--grid-span' ); ?>
        <?php endwhile; ?>
      </div>
      <?php endif; ?>

      <?php mworago_pagination(); ?>

    <?php else : ?>
      <p class="no-results"><?php esc_html_e( 'No articles in this category.', 'mworago' ); ?></p>
    <?php endif; ?>

  </div>
</main>

<?php get_footer(); ?>

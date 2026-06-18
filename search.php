<?php
/**
 * mworago 2026 — search.php
 * Basé sur category.php
 */
get_header();

$query = get_search_query();
global $wp_query;
$total = $wp_query->found_posts;
?>

<main class="archive-section">
  <div class="archive-inner">

    <header class="archive-header">
      <p class="archive-header__eyebrow"><?php esc_html_e( 'Search', 'mworago' ); ?></p>
      <h1 class="archive-header__title">
        &laquo;&nbsp;<?php echo esc_html( $query ); ?>&nbsp;&raquo;
      </h1>
      <p class="archive-header__count">
        <?php
        if ( $total > 0 ) {
            printf(
              esc_html( _n( '%d result found', '%d results found', $total, 'mworago' ) ),
              $total
            );
        } else {
            esc_html_e( 'No results.', 'mworago' );
        }
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

      <div style="text-align:center;padding:var(--sp-3xl) 0">
        <p class="archive-header__count">
          <?php printf(
            esc_html__( 'No articles for "%s". Try different keywords.', 'mworago' ),
            esc_html( $query )
          ); ?>
        </p>
        <div style="display:flex;flex-wrap:wrap;gap:var(--sp-sm);justify-content:center;margin-top:var(--sp-xl)">
          <?php
          $suggestions = [ 'BTS', 'BLACKPINK', 'aespa', 'IVE', 'NewJeans', 'Stray Kids', 'Kdrama', 'Comeback' ];
          foreach ( $suggestions as $s ) : ?>
            <a href="<?php echo esc_url( home_url( '/?s=' . rawurlencode( $s ) ) ); ?>" class="single-tag">
              <?php echo esc_html( $s ); ?>
            </a>
          <?php endforeach; ?>
        </div>
      </div>

    <?php endif; ?>

  </div>
</main>

<?php get_footer(); ?>

<?php
/**
 * mworago 2026 — Homepage (front-page.php)
 */
get_header();
?>

<!-- HERO -->
<?php
$hero_post  = null;
$side_posts = [];
$hero_ids   = [];

$hero_q = new WP_Query( [
    'posts_per_page' => 6,
    'post_status'    => [ 'publish', 'private' ],
    'orderby'        => 'date',
    'order'          => 'DESC',
] );
if ( $hero_q->have_posts() ) {
    $hero_q->the_post();
    $hero_post  = get_post();
    $hero_ids[] = $hero_post->ID;
    while ( $hero_q->have_posts() ) {
        $hero_q->the_post();
        $side_posts[] = get_post();
        $hero_ids[]   = get_the_ID();
    }
    wp_reset_postdata();
}
?>

<main id="main-content">
<?php if ( $hero_post ) : setup_postdata( $hero_post ); ?>
<section class="hero">

  <!-- Article vedette -->
  <article class="hero-main<?php if ( 'private' === get_post_status( $hero_post ) ) echo ' hero-main--vip'; ?>">
    <?php if ( has_post_thumbnail( $hero_post ) ) : ?>
      <div class="hero-main__bg" style="background:none">
        <?php echo get_the_post_thumbnail( $hero_post, 'full', [ 'fetchpriority' => 'high', 'loading' => 'eager', 'decoding' => 'sync' ] ); ?>
      </div>
    <?php else : ?>
      <div class="hero-main__bg g1"></div>
    <?php endif; ?>
    <a href="<?php echo esc_url( get_permalink( $hero_post ) ); ?>" class="hero-main__link" tabindex="-1" aria-hidden="true"></a>
    <div class="hero-main__overlay"></div>
    <div class="hero-main__deco">K</div>
    <div class="hero-main__content">
      <?php
      $cats = get_the_category( $hero_post->ID );
      if ( $cats ) : ?>
        <span class="badge"><?php echo esc_html( $cats[0]->name ); ?></span>
      <?php endif; ?>
      <?php if ( 'private' === get_post_status( $hero_post ) ) : ?>
        <span class="badge badge--vip">⭐ Exclu</span>
      <?php endif; ?>
      <h1 class="hero-main__title">
        <a href="<?php echo esc_url( get_permalink( $hero_post ) ); ?>">
          <?php echo esc_html( get_the_title( $hero_post ) ); ?>
        </a>
      </h1>
      <p class="hero-main__excerpt">
        <?php echo esc_html( wp_trim_words( get_the_excerpt( $hero_post ), 25 ) ); ?>
      </p>
      <div class="hero-main__footer">
        <span class="hero-main__date">
          <?php echo esc_html( get_the_date( '', $hero_post ) ); ?>
        </span>
        <a href="<?php echo esc_url( get_permalink( $hero_post ) ); ?>" class="btn-read">
          <?php esc_html_e( 'Read article', 'mworago' ); ?>
          <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="m9 18 6-6-6-6"/></svg>
        </a>
      </div>
    </div>
  </article>

  <!-- A la une — 5 articles les plus vus -->
  <?php if ( $side_posts ) : ?>
  <aside class="hero-side">
    <?php foreach ( $side_posts as $side ) : setup_postdata( $side ); ?>
    <a href="<?php echo esc_url( get_permalink( $side ) ); ?>" class="side-card<?php if ( 'private' === get_post_status( $side ) ) echo ' side-card--vip'; ?>">
      <div class="side-card__img">
        <?php if ( has_post_thumbnail( $side ) ) : ?>
          <?php echo get_the_post_thumbnail( $side, 'thumbnail' ); ?>
        <?php else : ?>
          <div class="g<?php echo rand(1,7); ?>" style="width:100%;height:100%;border-radius:var(--r-sm)"></div>
        <?php endif; ?>
      </div>
      <div class="side-card__body">
        <p class="side-card__title"><?php echo esc_html( get_the_title( $side ) ); ?></p>
        <span class="side-card__date">
          <?php if ( 'private' === get_post_status( $side ) ) : ?><span class="badge badge--vip" style="font-size:10px;padding:2px 6px;margin-right:4px">⭐ Exclu</span><?php endif; ?>
          <?php echo esc_html( get_the_date( '', $side ) ); ?>
        </span>
      </div>
    </a>
    <?php endforeach; wp_reset_postdata(); ?>
  </aside>
  <?php endif; ?>

</section>
<?php endif; ?>

<!-- PUB — après hero -->
<?php mworago_ad( 'mworago_ad_hero' ); ?>

<!-- DERNIERES ACTUALITES -->
<?php
$latest = new WP_Query( [
    'posts_per_page' => 9,
    'post_status'    => [ 'publish', 'private' ],
    'orderby'        => 'date',
    'order'          => 'DESC',
    'post__not_in'   => $hero_ids,
] );
?>
<?php if ( $latest->have_posts() ) : ?>
<section class="articles">
  <div class="s-heading">
    <h2 class="s-heading__title"><?php esc_html_e( 'Latest news', 'mworago' ); ?></h2>
    <?php
    $blog_page_id = get_option( 'page_for_posts' );
    if ( ! $blog_page_id ) {
        $first_cat = get_categories( [ 'orderby' => 'count', 'order' => 'DESC', 'number' => 1, 'hide_empty' => true ] );
        $blog_url  = $first_cat ? get_category_link( $first_cat[0]->term_id ) : '';
    } else {
        $blog_url = get_permalink( $blog_page_id );
    }
    ?>
    <?php if ( $blog_url ) : ?>
    <a href="<?php echo esc_url( $blog_url ); ?>" class="s-heading__more">
      <?php esc_html_e( 'View all', 'mworago' ); ?>
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
    </a>
    <?php endif; ?>
  </div>

  <div class="articles-grid">
    <?php $delay = 0; $card_idx = 0; while ( $latest->have_posts() ) : $latest->the_post(); $card_idx++; ?>
    <article class="a-card reveal<?php if ( 'private' === get_post_status() ) echo ' a-card--vip'; ?>" <?php if ( $delay ) echo 'style="--delay:' . $delay . 's"'; ?>>
      <div class="a-card__img">
        <?php if ( has_post_thumbnail() ) : ?>
          <div class="a-card__img-inner"><?php the_post_thumbnail( 'medium_large' ); ?></div>
        <?php else : ?>
          <div class="a-card__img-inner g<?php echo ( ( get_the_ID() % 7 ) + 1 ); ?>">
            <span class="a-card__img-label"><?php the_title(); ?></span>
          </div>
        <?php endif; ?>
        <?php
        $card_cats = get_the_category();
        if ( $card_cats ) : ?>
          <span class="a-card__badge badge"><?php echo esc_html( $card_cats[0]->name ); ?></span>
        <?php endif; ?>
        <?php if ( 'private' === get_post_status() ) : ?>
          <span class="a-card__vip-badge badge">⭐ Exclu</span>
        <?php endif; ?>
      </div>
      <div class="a-card__body">
        <h3 class="a-card__title clamp2">
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
        <p class="a-card__excerpt">
          <?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?>
        </p>
        <div class="a-card__footer">
          <span class="a-card__date"><?php echo get_the_date( '' ); ?></span>
          <span class="a-card__time">
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <?php echo mworago_reading_time(); ?>
          </span>
        </div>
      </div>
    </article>
    <?php if ( 3 === $card_idx ) mworago_ad( 'mworago_ad_grid', 'mw-ad--grid-span' ); ?>
    <?php $delay = round( $delay + 0.08, 2 ); endwhile; wp_reset_postdata(); ?>
  </div>

  <?php if ( $blog_url ) : ?>
  <div style="text-align:center;margin-top:var(--sp-xxl)">
    <a href="<?php echo esc_url( $blog_url ); ?>" class="btn-home">
      <?php esc_html_e( 'View all news', 'mworago' ); ?>
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
    </a>
  </div>
  <?php endif; ?>

</section>
<?php endif; ?>

</main>

<?php get_footer(); ?>

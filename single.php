<?php
/**
 * mworago 2026 — single.php (article individuel)
 */
get_header();

if ( ! have_posts() ) {
    get_footer();
    exit;
}

the_post();
?>

<main class="single-wrap">

  <!-- HERO IMAGE -->
  <?php if ( has_post_thumbnail() ) : ?>
  <div class="single-hero">
    <div class="single-hero__img">
      <?php the_post_thumbnail( 'full' ); ?>
    </div>
    <div class="single-hero__overlay"></div>
    <div class="single-hero__meta">
      <?php
      $cats = get_the_category();
      if ( $cats ) :
      ?>
        <a href="<?php echo esc_url( get_category_link( $cats[0]->term_id ) ); ?>" class="badge">
          <?php echo esc_html( $cats[0]->name ); ?>
        </a>
      <?php endif; ?>
    </div>
  </div>
  <?php endif; ?>

  <!-- ARTICLE -->
  <article class="single-article" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="single-article__inner">

      <!-- EN-TÊTE -->
      <header class="single-header">

        <?php if ( ! has_post_thumbnail() ) :
          $cats = get_the_category();
          if ( $cats ) : ?>
            <a href="<?php echo esc_url( get_category_link( $cats[0]->term_id ) ); ?>" class="badge" style="margin-bottom:var(--sp-lg);display:inline-flex">
              <?php echo esc_html( $cats[0]->name ); ?>
            </a>
          <?php endif;
        endif; ?>

        <h1 class="single-title"><?php the_title(); ?></h1>

        <div class="single-meta">
          <span class="single-meta__date">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
              <line x1="16" y1="2" x2="16" y2="6"/>
              <line x1="8" y1="2" x2="8" y2="6"/>
              <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            <?php echo get_the_date( '' ); ?>
          </span>
          <span class="single-meta__sep">·</span>
          <span class="single-meta__time">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"/>
              <polyline points="12 6 12 12 16 14"/>
            </svg>
            <?php echo mworago_reading_time(); ?>
          </span>
          <span class="single-meta__sep">·</span>
          <span class="single-meta__author">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
              <circle cx="12" cy="7" r="4"/>
            </svg>
            <?php the_author(); ?>
          </span>
        </div>

      </header>

      <!-- PUB — entre titre et contenu -->
      <?php mworago_ad( 'mworago_ad_article' ); ?>

      <!-- CONTENU -->
      <div class="single-content entry-content">
        <?php
        if ( 'private' === get_post_status() && ! is_user_logged_in() ) {
            echo mworago_paywall_html();
        } else {
            the_content();
        }
        ?>
      </div>

      <!-- TAGS -->
      <?php
      $tags = get_the_tags();
      if ( $tags ) : ?>
      <div class="single-tags">
        <?php foreach ( $tags as $tag ) : ?>
          <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="single-tag">
            #<?php echo esc_html( $tag->name ); ?>
          </a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <!-- PARTAGE -->
      <div class="single-share">
        <span class="single-share__label"><?php esc_html_e( 'Share', 'mworago' ); ?></span>
        <a class="single-share__btn"
           href="https://twitter.com/intent/tweet?url=<?php echo rawurlencode( get_permalink() ); ?>&text=<?php echo rawurlencode( get_the_title() ); ?>"
           target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'Share on X / Twitter', 'mworago' ); ?>">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.73-8.835L1.254 2.25H8.08l4.259 5.627 5.905-5.627Zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
          </svg>
        </a>
        <a class="single-share__btn"
           href="https://www.facebook.com/sharer/sharer.php?u=<?php echo rawurlencode( get_permalink() ); ?>"
           target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'Share on Facebook', 'mworago' ); ?>">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
          </svg>
        </a>
        <button class="single-share__btn single-share__copy" data-url="<?php echo esc_attr( get_permalink() ); ?>" aria-label="<?php esc_attr_e( 'Copy link', 'mworago' ); ?>">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
          </svg>
        </button>
      </div>

      <!-- COMMENTAIRES — masqués sur articles privés si non connecté -->
      <?php if ( 'private' !== get_post_status() || is_user_logged_in() ) : ?>
        <?php comments_template(); ?>
      <?php endif; ?>

      <!-- PUB — après commentaires -->
      <?php mworago_ad( 'mworago_ad_article' ); ?>

    </div><!-- /.single-article__inner -->
  </article>

  <!-- NAVIGATION PREV / NEXT -->
  <?php
  $prev = get_previous_post();
  $next = get_next_post();
  if ( $prev || $next ) :
  ?>
  <nav class="post-nav" aria-label="<?php esc_attr_e( 'Articles précédent et suivant', 'mworago' ); ?>">
    <div class="post-nav__inner">

      <?php if ( $prev ) : ?>
      <a href="<?php echo esc_url( get_permalink( $prev ) ); ?>" class="post-nav__item post-nav__item--prev">
        <span class="post-nav__dir">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
          <?php esc_html_e( 'Previous article', 'mworago' ); ?>
        </span>
        <span class="post-nav__title"><?php echo esc_html( get_the_title( $prev ) ); ?></span>
      </a>
      <?php endif; ?>

      <?php if ( $next ) : ?>
      <a href="<?php echo esc_url( get_permalink( $next ) ); ?>" class="post-nav__item post-nav__item--next">
        <span class="post-nav__dir">
          <?php esc_html_e( 'Next article', 'mworago' ); ?>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
        </span>
        <span class="post-nav__title"><?php echo esc_html( get_the_title( $next ) ); ?></span>
      </a>
      <?php endif; ?>

    </div>
  </nav>
  <?php endif; ?>

  <!-- ARTICLES LIES -->
  <?php
  $cats_obj = get_the_category();
  if ( $cats_obj ) {
      $related = new WP_Query( [
          'cat'            => $cats_obj[0]->term_id,
          'posts_per_page' => 3,
          'post__not_in'   => [ get_the_ID() ],
          'post_status'    => 'publish',
          'orderby'        => 'date',
          'order'          => 'DESC',
      ] );
      if ( $related->have_posts() ) :
  ?>
  <section class="related">
    <div class="related__inner">
      <div class="s-heading">
        <h2 class="s-heading__title"><?php esc_html_e( 'In the same category', 'mworago' ); ?></h2>
      </div>
      <div class="articles-grid">
        <?php while ( $related->have_posts() ) : $related->the_post(); ?>
        <article class="a-card<?php if ( 'private' === get_post_status() ) echo ' a-card--vip'; ?>">
          <div class="a-card__img">
            <?php if ( has_post_thumbnail() ) : ?>
              <div class="a-card__img-inner"><?php the_post_thumbnail( 'medium_large' ); ?></div>
            <?php else : ?>
              <div class="a-card__img-inner g<?php echo ( ( get_the_ID() % 7 ) + 1 ); ?>"></div>
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
              <?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?>
            </p>
            <div class="a-card__footer">
              <span class="a-card__date"><?php echo get_the_date( '' ); ?></span>
              <span class="a-card__time"><?php echo mworago_reading_time(); ?></span>
            </div>
          </div>
        </article>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    </div>
  </section>
  <?php
      endif;
  }
  ?>


</main>

<script>
// Copy link
document.querySelectorAll('.single-share__copy').forEach(function(btn){
  btn.addEventListener('click', function(){
    navigator.clipboard.writeText(btn.dataset.url).then(function(){
      btn.style.color = '#22C55E';
      setTimeout(function(){ btn.style.color = ''; }, 1500);
    });
  });
});
</script>

<?php get_footer(); ?>

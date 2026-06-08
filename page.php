<?php
/**
 * mworago 2026 — page.php (pages statiques)
 * Même mise en page que single.php
 */
get_header();

if ( ! have_posts() ) { get_footer(); exit; }
the_post();
?>

<main class="single-wrap">

  <!-- HERO IMAGE (si image à la une) -->
  <?php if ( has_post_thumbnail() ) : ?>
  <div class="single-hero">
    <div class="single-hero__img">
      <?php the_post_thumbnail( 'full' ); ?>
    </div>
    <div class="single-hero__overlay"></div>
  </div>
  <?php endif; ?>

  <!-- CONTENU -->
  <article class="single-article" id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="single-article__inner">

      <header class="single-header">
        <h1 class="single-title"><?php the_title(); ?></h1>
      </header>

      <div class="single-content entry-content page-body">
        <?php the_content(); ?>
      </div>

      <?php
      wp_link_pages( [
          'before'      => '<div class="page-links">',
          'after'       => '</div>',
          'link_before' => '<span>',
          'link_after'  => '</span>',
      ] );
      ?>

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
        <button class="single-share__btn single-share__copy" data-url="<?php echo esc_attr( get_permalink() ); ?>" aria-label="Copier le lien">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
          </svg>
        </button>
      </div>

    </div>
  </article>

</main>

<script>
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

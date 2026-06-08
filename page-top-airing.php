<?php
/**
 * Template Name: Top Airing Dramas
 * Template Post Type: page
 *
 * mworago 2026 — Dramas en cours les mieux notés
 * Données : /top-airing.json (généré par generate-top-airing.py)
 */
get_header();

$data_url  = apply_filters( 'mworago_top_airing_url', 'https://fr.mworago.com/top-airing.json' );
$cache_key = 'mworago_top_airing_v1';
$data      = get_transient( $cache_key );

if ( false === $data ) {
    $resp = wp_remote_get( $data_url, [ 'timeout' => 10 ] );
    if ( ! is_wp_error( $resp ) && 200 === wp_remote_retrieve_response_code( $resp ) ) {
        $data = json_decode( wp_remote_retrieve_body( $resp ), true );
        set_transient( $cache_key, $data, HOUR_IN_SECONDS );
    }
}

$shows     = $data['shows']     ?? [];
$generated = $data['generated'] ?? '';
?>

<main class="mw-page-wrap">

  <header class="mw-page-header">
    <p class="mw-page-header__eyebrow"><?php esc_html_e( 'K-drama', 'mworago' ); ?></p>
    <h1 class="mw-page-header__title"><?php the_title(); ?></h1>
    <?php if ( $generated ) : ?>
      <p class="mw-page-header__meta">
        <?php printf( esc_html__( 'Mis à jour le %s', 'mworago' ), esc_html( wp_date( get_option( 'date_format' ), strtotime( $generated ) ) ) ); ?>
      </p>
    <?php endif; ?>
  </header>

  <?php if ( empty( $shows ) ) : ?>
    <p class="mw-empty"><?php esc_html_e( 'Aucune donnée disponible.', 'mworago' ); ?></p>

  <?php else : ?>
  <div class="mw-airing-grid">
    <?php foreach ( $shows as $i => $show ) : ?>
    <article class="mw-airing-card">
      <div class="mw-airing-card__rank"><?php echo $i + 1; ?></div>
      <div class="mw-airing-card__img">
        <?php if ( ! empty( $show['image'] ) ) : ?>
          <img src="<?php echo esc_url( $show['image'] ); ?>" alt="<?php echo esc_attr( $show['title'] ); ?>" loading="lazy">
        <?php else : ?>
          <div class="mw-airing-card__img-placeholder g<?php echo ( abs( crc32( $show['title'] ) ) % 7 ) + 1; ?>"></div>
        <?php endif; ?>
      </div>
      <div class="mw-airing-card__body">
        <h2 class="mw-airing-card__title"><?php echo esc_html( $show['title'] ); ?></h2>
        <div class="mw-airing-card__meta">
          <?php if ( ! empty( $show['rating'] ) ) : ?>
            <span class="mw-airing-card__rating">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
              <?php echo esc_html( number_format( $show['rating'], 1 ) ); ?>
            </span>
          <?php endif; ?>
          <?php if ( ! empty( $show['episodes'] ) ) : ?>
            <span class="mw-airing-card__eps">
              <?php printf( _n( '%d ép.', '%d éps.', $show['episodes'], 'mworago' ), $show['episodes'] ); ?>
            </span>
          <?php endif; ?>
        </div>
        <?php if ( ! empty( $show['synopsis'] ) ) : ?>
          <p class="mw-airing-card__synopsis">
            <?php echo esc_html( wp_trim_words( $show['synopsis'], 20 ) ); ?>
          </p>
        <?php endif; ?>
      </div>
    </article>
    <?php if ( ( $i + 1 ) % 5 === 0 ) mworago_ad( 'mworago_ad_grid', 'mw-ad--grid-span' ); ?>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

</main>

<?php get_footer(); ?>

<?php
/**
 * Template Name: Dramas — Calendrier
 * Template Post Type: page
 *
 * mworago 2026 — Calendrier des diffusions K-drama
 * Données : /dramas.json (généré par generate-dramas.py)
 */
get_header();

$data_url  = apply_filters( 'mworago_dramas_url', 'https://fr.mworago.com/dramas.json' );
$cache_key = 'mworago_dramas_v1';
$data      = get_transient( $cache_key );

if ( false === $data ) {
    $resp = wp_remote_get( $data_url, [ 'timeout' => 10 ] );
    if ( ! is_wp_error( $resp ) && 200 === wp_remote_retrieve_response_code( $resp ) ) {
        $data = mworago_sanitize_scraped_data( json_decode( wp_remote_retrieve_body( $resp ), true ) );
        set_transient( $cache_key, $data, HOUR_IN_SECONDS );
    }
}

$dramas    = $data['dramas']    ?? [];
$generated = $data['generated'] ?? '';

/* Grouper par date */
$today   = current_time( 'Y-m-d' );
$by_date = [];
foreach ( $dramas as $dr ) {
    $by_date[ $dr['date'] ?? '' ][] = $dr;
}
ksort( $by_date );
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

  <?php if ( empty( $dramas ) ) : ?>
    <p class="mw-empty"><?php esc_html_e( 'Aucune donnée disponible.', 'mworago' ); ?></p>

  <?php else : ?>
    <?php foreach ( $by_date as $date => $items ) :
      $is_today  = ( $date === $today );
      $is_past   = ( $date < $today );
    ?>
    <?php if ( $is_today ) mworago_ad( 'mworago_ad_grid' ); ?>
    <section class="mw-section mw-drama-day<?php echo $is_past ? ' mw-section--past' : ''; ?>">
      <div class="mw-date-label<?php echo $is_today ? ' mw-date-label--today' : ''; ?>">
        <span>
          <?php if ( $is_today ) : ?>
            <span class="mw-today-badge"><?php esc_html_e( "Aujourd'hui", 'mworago' ); ?></span>
          <?php endif; ?>
          <?php echo esc_html( wp_date( 'l j F Y', strtotime( $date ) ) ); ?>
        </span>
      </div>

      <div class="mw-drama-list">
        <?php foreach ( $items as $dr ) : ?>
        <a href="<?php echo mworago_safe_url( $dr['url'] ?? '' ); ?>" class="mw-drama-item" target="_blank" rel="noopener noreferrer">
          <div class="mw-drama-item__img">
            <?php if ( ! empty( $dr['image'] ) ) : ?>
              <img src="<?php echo esc_url( $dr['image'] ); ?>" alt="<?php echo esc_attr( $dr['title'] ); ?>" loading="lazy">
            <?php else : ?>
              <div class="mw-drama-item__img-placeholder g<?php echo ( abs( crc32( $dr['title'] ?? '' ) ) % 7 ) + 1; ?>"></div>
            <?php endif; ?>
          </div>
          <div class="mw-drama-item__body">
            <p class="mw-drama-item__title"><?php echo esc_html( $dr['title'] ); ?></p>
            <div class="mw-drama-item__meta">
              <span class="mw-drama-item__ep">
                <?php printf( esc_html__( 'Ép. %d', 'mworago' ), (int) ( $dr['episode'] ?? 0 ) ); ?>
              </span>
              <?php if ( ! empty( $dr['time'] ) ) : ?>
                <span class="mw-drama-item__time"><?php echo esc_html( $dr['time'] ); ?></span>
              <?php endif; ?>
            </div>
          </div>
          <svg class="mw-drama-item__arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
        </a>
        <?php endforeach; ?>
      </div>
    </section>
    <?php endforeach; ?>
  <?php endif; ?>

</main>

<?php get_footer(); ?>

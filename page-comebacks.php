<?php
/**
 * Template Name: Comebacks
 * Template Post Type: page
 *
 * mworago 2026 — Calendrier des comebacks K-pop
 * Données : /comebacks.json (généré par generate-comebacks.py)
 */
get_header();

/* ── Fetch JSON avec cache 1h ───────────────────────────────────────────── */
$data_url  = apply_filters( 'mworago_comebacks_url', 'https://fr.mworago.com/comebacks.json' );
$cache_key = 'mworago_comebacks_v1';
$data      = get_transient( $cache_key );

if ( false === $data ) {
    $resp = wp_remote_get( $data_url, [ 'timeout' => 10 ] );
    if ( ! is_wp_error( $resp ) && 200 === wp_remote_retrieve_response_code( $resp ) ) {
        $data = json_decode( wp_remote_retrieve_body( $resp ), true );
        set_transient( $cache_key, $data, HOUR_IN_SECONDS );
    }
}

$comebacks = $data['comebacks'] ?? [];
$generated = $data['generated'] ?? '';

/* ── Grouper par date ───────────────────────────────────────────────────── */
$today    = current_time( 'Y-m-d' );
$by_date  = [];
foreach ( $comebacks as $cb ) {
    $by_date[ $cb['date'] ][] = $cb;
}
ksort( $by_date );

/* ── Séparer passé / futur ──────────────────────────────────────────────── */
$upcoming = array_filter( $by_date, fn( $k ) => $k >= $today, ARRAY_FILTER_USE_KEY );
$past     = array_filter( $by_date, fn( $k ) => $k <  $today, ARRAY_FILTER_USE_KEY );
krsort( $past );

$type_labels = [
    'full'    => 'Album',
    'EP'      => 'EP',
    'mini'    => 'Mini Album',
    'single'  => 'Single',
    'release' => __( 'Sortie', 'mworago' ),
    'repack'  => 'Repack',
    'digital' => 'Digital',
];
?>

<main class="mw-page-wrap">

  <header class="mw-page-header">
    <p class="mw-page-header__eyebrow"><?php esc_html_e( 'K-pop', 'mworago' ); ?></p>
    <h1 class="mw-page-header__title"><?php the_title(); ?></h1>
    <?php if ( $generated ) : ?>
      <p class="mw-page-header__meta">
        <?php printf( esc_html__( 'Mis à jour le %s', 'mworago' ), esc_html( wp_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $generated ) ) ) ); ?>
      </p>
    <?php endif; ?>
  </header>

  <?php if ( empty( $comebacks ) ) : ?>
    <p class="mw-empty"><?php esc_html_e( 'Aucune donnée disponible.', 'mworago' ); ?></p>

  <?php else : ?>

    <?php $mw_cb_day = 0; ?>
  <?php if ( $upcoming ) : ?>
    <section class="mw-section">
      <div class="s-heading">
        <h2 class="s-heading__title"><?php esc_html_e( 'À venir', 'mworago' ); ?></h2>
      </div>
      <?php foreach ( $upcoming as $date => $items ) : ?>
        <div class="mw-date-group">
          <div class="mw-date-label">
            <span><?php echo esc_html( wp_date( 'l j F Y', strtotime( $date ) ) ); ?></span>
          </div>
          <div class="mw-cb-grid">
            <?php foreach ( $items as $cb ) :
              $type = $cb['type'] ?? '';
              $label = $type_labels[ $type ] ?? ucfirst( $type );
            ?>
            <a href="<?php echo esc_url( $cb['url'] ); ?>" class="mw-cb-card" target="_blank" rel="noopener">
              <div class="mw-cb-card__img">
                <?php if ( $cb['image'] ) : ?>
                  <img src="<?php echo esc_url( $cb['image'] ); ?>" alt="<?php echo esc_attr( $cb['album'] ); ?>" loading="lazy">
                <?php else : ?>
                  <div class="mw-cb-card__img-placeholder g<?php echo ( abs( crc32( $cb['artist'] ) ) % 7 ) + 1; ?>"></div>
                <?php endif; ?>
                <?php if ( $label ) : ?>
                  <span class="mw-cb-card__type badge"><?php echo esc_html( $label ); ?></span>
                <?php endif; ?>
              </div>
              <div class="mw-cb-card__body">
                <p class="mw-cb-card__artist"><?php echo esc_html( $cb['artist'] ); ?></p>
                <p class="mw-cb-card__album"><?php echo esc_html( $cb['album'] ); ?></p>
                <?php if ( ! empty( $cb['title'] ) && $cb['title'] !== $cb['album'] ) : ?>
                  <p class="mw-cb-card__track"><?php echo esc_html( $cb['title'] ); ?></p>
                <?php endif; ?>
              </div>
            </a>
            <?php endforeach; ?>
          </div>
        </div>
        <?php $mw_cb_day++; if ( $mw_cb_day % 3 === 0 ) mworago_ad( 'mworago_ad_grid' ); ?>
      <?php endforeach; ?>
    </section>
    <?php endif; ?>

    <?php if ( $past ) : ?>
    <section class="mw-section mw-section--past">
      <div class="s-heading">
        <h2 class="s-heading__title"><?php esc_html_e( 'Récents', 'mworago' ); ?></h2>
      </div>
      <?php foreach ( $past as $date => $items ) : ?>
        <div class="mw-date-group mw-date-group--past">
          <div class="mw-date-label">
            <span><?php echo esc_html( wp_date( 'l j F Y', strtotime( $date ) ) ); ?></span>
          </div>
          <div class="mw-cb-grid">
            <?php foreach ( $items as $cb ) :
              $type = $cb['type'] ?? '';
              $label = $type_labels[ $type ] ?? ucfirst( $type );
            ?>
            <a href="<?php echo esc_url( $cb['url'] ); ?>" class="mw-cb-card mw-cb-card--past" target="_blank" rel="noopener">
              <div class="mw-cb-card__img">
                <?php if ( $cb['image'] ) : ?>
                  <img src="<?php echo esc_url( $cb['image'] ); ?>" alt="<?php echo esc_attr( $cb['album'] ); ?>" loading="lazy">
                <?php else : ?>
                  <div class="mw-cb-card__img-placeholder g<?php echo ( abs( crc32( $cb['artist'] ) ) % 7 ) + 1; ?>"></div>
                <?php endif; ?>
                <?php if ( $label ) : ?>
                  <span class="mw-cb-card__type badge"><?php echo esc_html( $label ); ?></span>
                <?php endif; ?>
              </div>
              <div class="mw-cb-card__body">
                <p class="mw-cb-card__artist"><?php echo esc_html( $cb['artist'] ); ?></p>
                <p class="mw-cb-card__album"><?php echo esc_html( $cb['album'] ); ?></p>
              </div>
            </a>
            <?php endforeach; ?>
          </div>
        </div>
        <?php $mw_cb_day++; if ( $mw_cb_day % 3 === 0 ) mworago_ad( 'mworago_ad_grid' ); ?>
      <?php endforeach; ?>
    </section>
    <?php endif; ?>

  <?php endif; ?>

</main>

<?php get_footer(); ?>

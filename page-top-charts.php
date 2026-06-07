<?php
/**
 * Template Name: Top Charts K-pop
 * Template Post Type: page
 *
 * mworago 2026 — Charts YouTube K-pop
 * Données : /top-charts.json (généré par generate-top-charts.py)
 */
get_header();

$data_url  = apply_filters( 'mworago_top_charts_url', 'https://fr.mworago.com/top-charts.json' );
$cache_key = 'mworago_top_charts_v1';
$data      = get_transient( $cache_key );

if ( false === $data ) {
    $resp = wp_remote_get( $data_url, [ 'timeout' => 10 ] );
    if ( ! is_wp_error( $resp ) && 200 === wp_remote_retrieve_response_code( $resp ) ) {
        $data = json_decode( wp_remote_retrieve_body( $resp ), true );
        set_transient( $cache_key, $data, HOUR_IN_SECONDS );
    }
}

$items       = $data['items']        ?? [];
$generated   = $data['generated_at'] ?? '';

function mworago_format_views( $n ) {
    if ( $n >= 1000000 ) return round( $n / 1000000, 1 ) . 'M';
    if ( $n >= 1000 )    return round( $n / 1000, 0 )    . 'K';
    return $n;
}
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

  <?php if ( empty( $items ) ) : ?>
    <p class="mw-empty"><?php esc_html_e( 'Aucune donnée disponible.', 'mworago' ); ?></p>

  <?php else : ?>
  <div class="mw-charts-list">
    <?php $mw_chart_i = 0; foreach ( $items as $item ) :
      $mw_chart_i++;
      $yt_url = 'https://www.youtube.com/watch?v=' . $item['videoId'];
    ?>
    <a href="<?php echo esc_url( $yt_url ); ?>" class="mw-chart-item" target="_blank" rel="noopener">
      <div class="mw-chart-item__rank">
        <?php echo esc_html( $item['rank'] ); ?>
      </div>
      <div class="mw-chart-item__thumb">
        <?php if ( ! empty( $item['image'] ) ) : ?>
          <img src="<?php echo esc_url( $item['image'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" loading="lazy">
        <?php else : ?>
          <img src="https://img.youtube.com/vi/<?php echo esc_attr( $item['videoId'] ); ?>/mqdefault.jpg" alt="<?php echo esc_attr( $item['title'] ); ?>" loading="lazy">
        <?php endif; ?>
        <?php if ( ! empty( $item['isMV'] ) ) : ?>
          <span class="mw-chart-item__mv-badge">MV</span>
        <?php endif; ?>
      </div>
      <div class="mw-chart-item__body">
        <p class="mw-chart-item__title"><?php echo esc_html( $item['title'] ); ?></p>
        <p class="mw-chart-item__artist"><?php echo esc_html( $item['artist'] ); ?></p>
      </div>
      <div class="mw-chart-item__views">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
        <?php echo esc_html( mworago_format_views( $item['viewCount'] ) ); ?>
      </div>
    </a>
    <?php if ( $mw_chart_i % 10 === 0 ) mworago_ad( 'mworago_ad_grid' ); ?>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

</main>

<?php get_footer(); ?>

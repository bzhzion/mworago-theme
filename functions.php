<?php
/**
 * mworago 2026 — functions.php
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ── ENQUEUE ──────────────────────────────────────────────────────────────────
add_action( 'wp_enqueue_scripts', 'mworago_enqueue', 20 );

function mworago_enqueue() {
    // Désactiver CSS GeneratePress parent (on gère tout nous-mêmes)
    wp_dequeue_style( 'generatepress-style' );
    wp_deregister_style( 'generatepress-style' );

    // CSS principal — aucune dépendance externe
    wp_enqueue_style(
        'mworago-main',
        get_stylesheet_directory_uri() . '/assets/css/main.css',
        [],
        wp_get_theme()->get( 'Version' )
    );

    // Dark mode JS
    wp_enqueue_script(
        'mworago-theme',
        get_stylesheet_directory_uri() . '/assets/js/theme.js',
        [],
        wp_get_theme()->get( 'Version' ),
        [ 'strategy' => 'defer', 'in_footer' => true ]
    );

    wp_localize_script( 'mworago-theme', 'mworago', [
        'supportUrl' => esc_url( get_theme_mod( 'mworago_support_url', '' ) ),
    ] );

    wp_enqueue_script(
        'mworago-ads',
        get_stylesheet_directory_uri() . '/assets/js/ads.js',
        [],
        wp_get_theme()->get( 'Version' ),
        [ 'strategy' => 'async', 'in_footer' => false ]
    );
}

// Exclure ads.js d'Autoptimize pour conserver l'URL originale (détection adblock)
add_filter( 'autoptimize_filter_js_exclude', function( $exclude ) {
    return $exclude . ',assets/js/ads.js';
} );

// Preload DM Sans local — latin uniquement (fichier critique)
add_action( 'wp_head', function() {
    $uri = get_stylesheet_directory_uri();
    echo '<link rel="preload" as="font" type="font/woff2" href="' . esc_url( $uri . '/assets/fonts/dm-sans-normal-latin.woff2' ) . '" crossorigin>' . "\n";
}, 1 );

// Preload image hero — front-page uniquement (améliore LCP)
add_action( 'wp_head', function() {
    if ( ! is_front_page() ) return;
    $q = mworago_get_trending( 1 );
    if ( ! $q->have_posts() ) return;
    $q->the_post();
    $post_id  = get_the_ID();
    $thumb_id = get_post_thumbnail_id( $post_id );
    wp_reset_postdata();
    if ( ! $thumb_id ) return;
    $src = wp_get_attachment_image_src( $thumb_id, 'full' );
    if ( ! $src ) return;
    echo '<link rel="preload" as="image" href="' . esc_url( $src[0] ) . '" fetchpriority="high">' . "\n";
}, 2 );

// ── ADSENSE ───────────────────────────────────────────────────────────────────
/**
 * Affiche un bloc pub AdSense.
 * Configurer Publisher ID + Slots dans Apparence > Personnaliser > Publicités.
 */
function mworago_ad( $slot_mod = '', $extra_class = '' ) {
    $pub  = trim( get_theme_mod( 'mworago_adsense_pub', '' ) );
    if ( ! $pub ) return;
    $slot = trim( get_theme_mod( $slot_mod, '' ) );
    if ( ! $slot ) return;
    $support_url = esc_url( get_theme_mod( 'mworago_support_url', '' ) );
    $msg         = esc_html__( 'Pub bloquée — pour continuer à accéder gratuitement à ce contenu, désactivez votre bloqueur ou', 'mworago' );
    $link_text   = esc_html__( 'soutenez-nous', 'mworago' );
    $link        = $support_url
        ? '<a href="' . $support_url . '" target="_blank" rel="noopener">' . $link_text . '</a>'
        : $link_text;
    echo '<div class="mw-ad ' . esc_attr( $extra_class ) . '">';
    echo '<p class="mw-ad__label">' . esc_html__( 'Publicité', 'mworago' ) . '</p>';
    echo '<div class="mw-adblock"><span>' . $msg . ' ' . $link . '.</span></div>';
    echo '<ins class="adsbygoogle" style="display:block"';
    echo ' data-ad-client="' . esc_attr( $pub ) . '"';
    if ( $slot ) echo ' data-ad-slot="' . esc_attr( $slot ) . '"';
    echo ' data-ad-format="auto" data-full-width-responsive="true"></ins>';
    echo '<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>';
    echo '</div>';
}


add_action( 'customize_register', 'mworago_customizer_ads' );
function mworago_customizer_ads( $wp_customize ) {
    $wp_customize->add_section( 'mworago_ads', [
        'title' => 'Publicités AdSense', 'priority' => 160,
    ]);
    $fields = [
        'mworago_adsense_pub'      => 'Publisher ID (ca-pub-XXXXXXXXXXXXXXXX)',
        'mworago_ad_hero'          => 'Slot — Après hero (homepage)',
        'mworago_ad_grid'          => 'Slot — Dans grille après 3e article',
        'mworago_ad_sidebar'       => 'Slot — Sidebar desktop',
        'mworago_ad_before_footer' => 'Slot — Avant footer',
        'mworago_ad_article'       => 'Slot — Début corps article',
    ];
    foreach ( $fields as $key => $label ) {
        $wp_customize->add_setting( $key, [ 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ] );
        $wp_customize->add_control( $key, [ 'label' => $label, 'section' => 'mworago_ads', 'type' => 'text' ] );
    }
}

// ── SETUP ─────────────────────────────────────────────────────────────────────
add_action( 'after_setup_theme', 'mworago_setup' );

function mworago_setup() {
    // i18n — dossier languages/ dans le thème
    load_textdomain( 'mworago', get_template_directory() . '/languages/mworago-' . get_locale() . '.mo' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ] );
    add_theme_support( 'custom-logo' );

    // Menus
    register_nav_menus( [
        'primary'      => __( 'Menu principal (header)',       'mworago' ),
        'footer-nav'   => __( 'Footer - Navigation',          'mworago' ),
        'footer-about' => __( 'Footer - A propos',            'mworago' ),
    ] );
}

// ── CUSTOMIZER — Réseaux sociaux ─────────────────────────────────────────────
add_action( 'customize_register', 'mworago_customize_register' );

function mworago_customize_register( $wp_customize ) {
    // Section Liens
    $wp_customize->add_section( 'mworago_links', [
        'title'    => __( 'Liens du thème', 'mworago' ),
        'priority' => 110,
    ] );
    $wp_customize->add_setting( 'mworago_support_url', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ] );
    $wp_customize->add_control( 'mworago_support_url', [
        'label'       => __( 'Lien "Nous soutenir" — URL', 'mworago' ),
        'description' => __( 'URL du bouton dans le header et le footer.', 'mworago' ),
        'section'     => 'mworago_links',
        'type'        => 'url',
    ] );
    $wp_customize->add_setting( 'mworago_support_label', [
        'default'           => 'Nous soutenir',
        'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( 'mworago_support_label', [
        'label'       => __( 'Lien "Nous soutenir" — Texte', 'mworago' ),
        'description' => __( 'Texte affiché sur le bouton (ex: Faire un don, Nous supporter…)', 'mworago' ),
        'section'     => 'mworago_links',
        'type'        => 'text',
    ] );
    $wp_customize->add_setting( 'mworago_search_placeholder', [
        'default'           => 'BTS, aespa, IVE…',
        'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( 'mworago_search_placeholder', [
        'label'   => __( 'Placeholder de la recherche', 'mworago' ),
        'section' => 'mworago_links',
        'type'    => 'text',
    ] );

    // Section Réseaux sociaux
    $wp_customize->add_section( 'mworago_socials', [
        'title'    => __( 'Réseaux sociaux', 'mworago' ),
        'priority' => 120,
    ] );

    $socials = [
        'mworago_bluesky'   => 'Bluesky',
        'mworago_twitter'   => 'Twitter / X',
        'mworago_facebook'  => 'Facebook',
        'mworago_instagram' => 'Instagram',
        'mworago_youtube'   => 'YouTube',
        'mworago_tiktok'    => 'TikTok',
    ];

    foreach ( $socials as $key => $label ) {
        $wp_customize->add_setting( $key, [
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ] );
        $wp_customize->add_control( $key, [
            'label'   => $label,
            'section' => 'mworago_socials',
            'type'    => 'url',
        ] );
    }
}

// ── HELPERS ──────────────────────────────────────────────────────────────────

/**
 * Retourne les N articles les plus vus (à la une / trending).
 * Nécessite un plugin de comptage de vues (ex: Post Views Counter).
 * Fallback : articles récents si pas de plugin.
 */
function mworago_get_trending( $count = 5 ) {
    $args = [
        'posts_per_page' => $count,
        'post_status'    => 'publish',
        'meta_key'       => 'post_views_count', // Post Views Counter
        'orderby'        => 'meta_value_num',
        'order'          => 'DESC',
    ];

    $query = new WP_Query( $args );

    // Fallback si pas de vues enregistrées
    if ( ! $query->have_posts() ) {
        $args['meta_key'] = '';
        $args['orderby']  = 'date';
        $query = new WP_Query( $args );
    }

    return $query;
}

/**
 * Retourne les N derniers articles (chronologique).
 */
function mworago_get_latest( $count = 6, $offset = 0 ) {
    return new WP_Query( [
        'posts_per_page' => $count,
        'offset'         => $offset,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    ] );
}

/**
 * Temps de lecture estimé.
 */
function mworago_reading_time() {
    $content = get_post_field( 'post_content', get_the_ID() );
    $words   = str_word_count( wp_strip_all_tags( $content ) );
    $minutes = max( 1, round( $words / 200 ) );
    /* translators: %d = number of minutes to read an article */
    return sprintf( _n( '%d min read', '%d min read', $minutes, 'mworago' ), $minutes );
}

// ── HREFLANG — homepage uniquement ───────────────────────────────────────────

add_action( 'wp_head', 'mworago_hreflang', 1 );
function mworago_hreflang() {
    if ( ! is_front_page() ) return;
    $sites = [
        'x-default' => 'https://fr.mworago.com/',
        'fr'        => 'https://fr.mworago.com/',
        'es'        => 'https://es.mworago.com/home/',
        'de'        => 'https://de.mworago.com/startseite/',
        'it'        => 'https://it.mworago.com/home/',
        'ko'        => 'https://ko.mworago.com/home/',
        'en'        => 'https://en.mworago.com/home/',
        'tl'        => 'https://ph.mworago.com/home/',
        'pt'        => 'https://pt.mworago.com/inicio/',
        'ms'        => 'https://ms.mworago.com/laman-utama/',
        'ar'        => 'https://ar.mworago.com/home/',
        'vi'        => 'https://vi.mworago.com/trang-chu/',
        'th'        => 'https://th.mworago.com/home/',
        'id'        => 'https://id.mworago.com/beranda/',
    ];
    foreach ( $sites as $lang => $url ) {
        echo '<link rel="alternate" hreflang="' . esc_attr( $lang ) . '" href="' . esc_url( $url ) . '">' . "\n";
    }
}

// ── PRIVATE POSTS — Preview pour visiteurs non connectés ─────────────────────

/**
 * Rend les articles privés visibles sur leur URL publique.
 * Limité aux single posts — n'expose rien dans les archives ni la home.
 */
add_filter( 'private_title_format', fn() => '%s' );

// WP 6.1+ force ?p=ID pour les posts privés si non connecté — on reconstruit l'URL depuis le slug.
add_filter( 'post_link', function( $url, $post ) {
    if ( 'private' !== get_post_status( $post ) || empty( $post->post_name ) ) {
        return $url;
    }
    if ( ! str_contains( $url, '?p=' ) ) {
        return $url;
    }
    $structure = get_option( 'permalink_structure' );
    if ( ! $structure ) {
        return $url;
    }
    $ts   = strtotime( $post->post_date );
    $find = [ '%year%', '%monthnum%', '%day%', '%hour%', '%minute%', '%second%', '%postname%', '%post_id%' ];
    $repl = [ date( 'Y', $ts ), date( 'm', $ts ), date( 'd', $ts ), date( 'H', $ts ), date( 'i', $ts ), date( 's', $ts ), $post->post_name, $post->ID ];
    return user_trailingslashit( home_url( str_replace( $find, $repl, $structure ) ), 'single' );
}, 10, 2 );

add_action( 'pre_get_posts', 'mworago_allow_private_preview' );
function mworago_allow_private_preview( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) return;
    if ( $query->is_single || $query->is_home || $query->is_archive || $query->is_search ) {
        $query->set( 'post_status', [ 'publish', 'private' ] );
    }
}

/**
 * Génère le bloc paywall : aperçu tronqué + CTA login/register.
 */
function mworago_paywall_html() {
    global $post;

    // Aperçu : excerpt ou premiers 80 mots
    if ( ! empty( $post->post_excerpt ) ) {
        $preview = wpautop( wp_trim_words( $post->post_excerpt, 80 ) );
    } else {
        $words = preg_split( '/\s+/u', trim( wp_strip_all_tags( $post->post_content ) ) );
        $preview = wpautop( implode( ' ', array_slice( $words, 0, 80 ) ) . '…' );
    }

    $app_url   = home_url( '/app' );
    $login_url = wp_login_url( get_permalink() );

    ob_start();
    ?>
    <div class="mworago-preview">
        <?php echo $preview; // Already sanitized via wp_strip_all_tags + wpautop ?>
        <div class="mworago-preview__fade" aria-hidden="true"></div>
    </div>
    <div class="mworago-paywall" role="complementary">
        <div class="mworago-paywall__icon" aria-hidden="true">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
        </div>
        <p class="mworago-paywall__title">⭐ VIP</p>
        <p class="mworago-paywall__desc"><?php esc_html_e( 'You\'re so close to reading the rest! The mworago app gives you free unlimited access to all exclusive articles. Download it, or sign up for free.', 'mworago' ); ?></p>
        <div class="mworago-paywall__actions">
            <a href="<?php echo esc_url( $app_url ); ?>" class="mworago-paywall__btn mworago-paywall__btn--primary">
                <?php esc_html_e( 'Install the app', 'mworago' ); ?>
            </a>
            <a href="<?php echo esc_url( wp_registration_url() ); ?>" class="mworago-paywall__btn mworago-paywall__btn--secondary">
                <?php esc_html_e( 'Sign up for free', 'mworago' ); ?>
            </a>
        </div>
    </div>
    <?php
    return ob_get_clean();
}


// ── 9 articles par page (search, archives, tags, home) ───────────────────────

add_action( 'pre_get_posts', 'mworago_posts_per_page' );
function mworago_posts_per_page( $query ) {
    if ( ! is_admin() && $query->is_main_query() ) {
        if ( $query->is_search() || $query->is_archive() || $query->is_home() ) {
            $query->set( 'posts_per_page', 9 );
        }
    }
}

// ── PAGINATION ───────────────────────────────────────────────────────────────

function mworago_pagination() {
    $pages = paginate_links( [
        'type'      => 'array',
        'prev_text' => '&larr;',
        'next_text' => '&rarr;',
    ] );
    if ( ! $pages ) return;
    echo '<nav class="pagination" aria-label="' . esc_attr__( 'Navigation des pages', 'mworago' ) . '">';
    foreach ( $pages as $page ) {
        echo $page;
    }
    echo '</nav>';
}

// ── FORCER LOCALE FRONTEND ───────────────────────────────────────────────────
// WP utilise sinon la langue du navigateur ou du profil utilisateur.
add_filter( 'locale', function( $locale ) {
    if ( is_admin() ) return $locale;
    return get_option( 'WPLANG' ) ?: 'fr_FR';
} );

// ── COMMENTAIRES ─────────────────────────────────────────────────────────────

add_action( 'wp_enqueue_scripts', function() {
    if ( is_singular() && comments_open() ) {
        wp_enqueue_script( 'comment-reply' );
    }
} );

function mworago_comment( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    ?>
    <li id="comment-<?php comment_ID(); ?>" <?php comment_class( 'comment-item' ); ?>>
      <div class="comment-body">
        <div class="comment-avatar">
          <?php echo get_avatar( $comment, 44, '', get_comment_author( $comment ), [ 'class' => 'comment-avatar__img' ] ); ?>
        </div>
        <div class="comment-content">
          <div class="comment-meta">
            <span class="comment-author-name"><?php comment_author_link( $comment ); ?></span>
            <span class="comment-sep">·</span>
            <time class="comment-date" datetime="<?php comment_date( 'c', $comment ); ?>"><?php comment_date( '', $comment ); ?></time>
            <?php if ( '0' === $comment->comment_approved ) : ?>
              <span class="comment-awaiting"><?php esc_html_e( 'Awaiting moderation', 'mworago' ); ?></span>
            <?php endif; ?>
          </div>
          <div class="comment-text"><?php comment_text( $comment ); ?></div>
          <?php comment_reply_link( array_merge( $args, [
            'depth'      => $depth,
            'max_depth'  => $args['max_depth'],
            'reply_text' => esc_html__( 'Reply', 'mworago' ),
            'before'     => '<div class="comment-reply-link">',
            'after'      => '</div>',
          ] ) ); ?>
        </div>
      </div>
    <?php
    // No closing </li> — Walker_Comment::end_el() handles it
}

// ── NAV WALKER ───────────────────────────────────────────────────────────────

class mworago_Nav_Walker extends Walker_Nav_Menu {

    public function start_lvl( &$output, $depth = 0, $args = null ) {
        if ( 0 === $depth ) {
            $output .= '<div class="nav__dropdown">';
        }
    }

    public function end_lvl( &$output, $depth = 0, $args = null ) {
        if ( 0 === $depth ) {
            $output .= '</div>';
        }
    }

    public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
        $item         = $data_object;
        $classes      = empty( $item->classes ) ? [] : (array) $item->classes;
        $active       = in_array( 'current-menu-item', $classes ) ? ' is-active' : '';
        $has_children = in_array( 'menu-item-has-children', $classes );

        if ( 0 === $depth ) {
            $output .= '<div class="nav__item-wrap' . ( $has_children ? ' nav__item-wrap--has-children' : '' ) . '">';
            if ( $has_children ) {
                $output .= sprintf(
                    '<button class="nav__item nav__item--parent%s" type="button">%s<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg></button>',
                    esc_attr( $active ),
                    esc_html( $item->title )
                );
            } else {
                $output .= sprintf(
                    '<a href="%s" class="nav__item%s">%s</a>',
                    esc_url( $item->url ),
                    esc_attr( $active ),
                    esc_html( $item->title )
                );
            }
        } elseif ( 1 === $depth ) {
            $output .= sprintf(
                '<a href="%s" class="nav__dropdown-item%s">%s</a>',
                esc_url( $item->url ),
                esc_attr( $active ),
                esc_html( $item->title )
            );
        }
    }

    public function end_el( &$output, $data_object, $depth = 0, $args = null ) {
        if ( 0 === $depth ) {
            $output .= '</div>';
        }
    }
}

// ── AUTO-UPDATE — GitHub Releases ─────────────────────────────────────────────

add_filter( 'pre_set_site_transient_update_themes', 'mworago_check_theme_update' );
function mworago_check_theme_update( $transient ) {
    if ( empty( $transient->checked ) ) return $transient;

    $theme_slug    = get_option( 'stylesheet' );
    $current_ver   = wp_get_theme()->get( 'Version' );
    $transient_key = 'mworago_github_release';

    $release = get_transient( $transient_key );
    if ( false === $release ) {
        $response = wp_remote_get( 'https://api.github.com/repos/bzhzion/mworago-theme/releases/latest', [
            'timeout' => 10,
            'headers' => [
                'User-Agent'    => 'mworago-theme-updater',
                'Cache-Control' => 'no-cache, no-store',
                'Pragma'        => 'no-cache',
            ],
        ] );
        if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
            set_transient( $transient_key, [ 'tag_name' => $current_ver ], HOUR_IN_SECONDS );
            return $transient;
        }
        $release = json_decode( wp_remote_retrieve_body( $response ), true );
        set_transient( $transient_key, $release, HOUR_IN_SECONDS );
    }

    $latest_ver = ltrim( $release['tag_name'] ?? '', 'v' );
    if ( version_compare( $latest_ver, $current_ver, '>' ) ) {
        $transient->response[ $theme_slug ] = [
            'theme'       => $theme_slug,
            'new_version' => $latest_ver,
            'url'         => 'https://github.com/bzhzion/mworago-theme',
            'package'     => 'https://github.com/bzhzion/mworago-theme/releases/download/v' . $latest_ver . '/mworago-theme.zip',
        ];
    }

    return $transient;
}


<!-- NEWSLETTER -->
<section class="newsletter">
  <div class="newsletter__inner">
    <div class="newsletter__text">
      <h3><?php esc_html_e( 'Stay in the K-pop loop', 'mworago' ); ?></h3>
      <p><?php esc_html_e( 'Daily K-pop news in your inbox: comebacks, charts, drama, nothing slips through.', 'mworago' ); ?></p>
    </div>
    <form method="post" action="https://mailing.breizhzion.com/subscription/form" class="newsletter__form listmonk-form">
      <input type="hidden" name="nonce" />
      <input type="hidden" name="l" value="3e845a56-5677-49fa-86d3-6e31ed0588d5" />
      <input class="newsletter__input" type="email" name="email" required placeholder="<?php esc_attr_e( 'your@email.com', 'mworago' ); ?>" />
      <altcha-widget challengeurl="https://mailing.breizhzion.com/api/public/captcha/altcha" auto="onload" name="altcha"></altcha-widget>
      <script src="https://mailing.breizhzion.com/public/static/altcha.umd.js" async defer></script>
      <button type="submit" class="newsletter__btn"><?php esc_html_e( 'Subscribe', 'mworago' ); ?></button>
    </form>
  </div>
</section>

<!-- PUB — avant footer -->
<?php mworago_ad( 'mworago_ad_before_footer' ); ?>

<!-- FOOTER -->
<footer class="footer">
  <div class="footer__grid">

    <div>
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer__logo">
        <?php if ( has_custom_logo() ) :
          $logo_id  = get_theme_mod( 'custom_logo' );
          $logo_src = wp_get_attachment_image_url( $logo_id, 'full' );
        ?>
          <img src="<?php echo esc_url( $logo_src ); ?>" alt="<?php bloginfo( 'name' ); ?>" style="height:32px;width:auto;">
        <?php else : ?>
          mworago.
        <?php endif; ?>
      </a>
      <p class="footer__desc"><?php bloginfo( 'description' ); ?></p>
    </div>

    <div>
      <?php wp_nav_menu( [
        'theme_location' => 'footer-nav',
        'container'      => false,
        'menu_class'     => 'footer__links',
        'fallback_cb'    => false,
      ] ); ?>
    </div>

    <div>
      <?php wp_nav_menu( [
        'theme_location' => 'footer-about',
        'container'      => false,
        'menu_class'     => 'footer__links',
        'fallback_cb'    => false,
      ] ); ?>
    </div>

    <div>
      <div class="footer__socials">
        <?php
        $social_icons = [
          'Bluesky'   => [
            'url'  => get_theme_mod( 'mworago_bluesky',   '' ),
            'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8.5c-2-3.5-6-5.5-8-5.5 0 4 2 7 8 8.5C18 10 20 7 20 3c-2 0-6 2-8 5.5z"/><path d="M12 8.5c2 3.5 6 5.5 8 5.5 0 4-2 7-8 8.5C6 21 4 18 4 14c2 0 6-2 8-5.5z"/></svg>',
          ],
          'Twitter'   => [
            'url'  => get_theme_mod( 'mworago_twitter',   '' ),
            'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.73-8.835L1.254 2.25H8.08l4.259 5.627 5.905-5.627Zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
          ],
          'Facebook'  => [
            'url'  => get_theme_mod( 'mworago_facebook',  '' ),
            'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>',
          ],
          'Instagram' => [
            'url'  => get_theme_mod( 'mworago_instagram', '' ),
            'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><circle cx="12" cy="12" r="4.5"/><circle cx="17.5" cy="6.5" r="0.5" fill="currentColor" stroke="none"/></svg>',
          ],
          'YouTube'   => [
            'url'  => get_theme_mod( 'mworago_youtube',   '' ),
            'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 0 0 1.46 6.42 29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58 2.78 2.78 0 0 0 1.95 1.96C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.96A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" stroke="none" fill="currentColor"/></svg>',
          ],
          'TikTok'    => [
            'url'  => get_theme_mod( 'mworago_tiktok',    '' ),
            'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"/></svg>',
          ],
        ];
        foreach ( $social_icons as $name => $data ) :
          if ( $data['url'] ) : ?>
            <a href="<?php echo esc_url( $data['url'] ); ?>" class="social-btn" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( $name ); ?>">
              <?php echo $data['icon']; ?>
              <span><?php echo esc_html( $name ); ?></span>
            </a>
        <?php endif; endforeach; ?>
      </div>
    </div>

  </div>

  <div class="footer__bottom">
    <p class="footer__copy">
      &copy; 2013&ndash;<?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?> &mdash; <?php esc_html_e( 'All rights reserved', 'mworago' ); ?>
    </p>
    <?php
    $support_url   = get_theme_mod( 'mworago_support_url', '' ) ?: get_permalink( get_page_by_path( 'soutenir' ) );
    $support_label = get_theme_mod( 'mworago_support_label', __( 'Nous soutenir', 'mworago' ) );
    ?>
    <a href="<?php echo esc_url( $support_url ); ?>" class="footer__love">
      <?php echo esc_html( $support_label ); ?>&nbsp;♥
    </a>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>

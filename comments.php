<?php
/**
 * mworago 2026 — comments.php
 */

if ( post_password_required() ) return;

$commenter = wp_get_current_commenter();
?>

<section class="comments-wrap" id="comments">
  <div class="comments-inner">

    <?php if ( have_comments() ) : ?>
    <h2 class="comments-title">
      <?php
      printf(
        esc_html( _n( '%s comment', '%s comments', get_comments_number(), 'mworago' ) ),
        '<span>' . number_format_i18n( get_comments_number() ) . '</span>'
      );
      ?>
    </h2>

    <ol class="comment-list">
      <?php wp_list_comments( [
        'callback'    => 'mworago_comment',
        'style'       => 'ol',
        'short_ping'  => true,
        'avatar_size' => 44,
      ] ); ?>
    </ol>

    <?php the_comments_navigation( [
      'prev_text' => '&larr; ' . esc_html__( 'Older comments', 'mworago' ),
      'next_text' => esc_html__( 'Newer comments', 'mworago' ) . ' &rarr;',
    ] ); ?>

    <?php elseif ( comments_open() ) : ?>
    <p class="comments-none"><?php esc_html_e( 'No comments yet. Be the first!', 'mworago' ); ?></p>
    <?php endif; ?>

    <?php if ( comments_open() ) :
      comment_form( [
        'title_reply'          => esc_html__( 'Leave a comment', 'mworago' ),
        'title_reply_to'       => esc_html__( 'Reply to %s', 'mworago' ),
        'cancel_reply_link'    => esc_html__( 'Cancel reply', 'mworago' ),
        'label_submit'         => esc_html__( 'Post comment', 'mworago' ),
        'class_submit'         => 'btn-comment-submit',
        'submit_button'        => '<button name="%1$s" type="submit" id="%2$s" class="%3$s">%4$s <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m22 2-7 20-4-9-9-4 20-7z"/></svg></button>',
        'class_form'           => 'comment-form',
        'id_form'              => 'commentform',
        'comment_notes_before' => '',
        'comment_notes_after'  => '',
        'logged_in_as'         => '',
        'comment_field'        => '<div class="cf-field cf-field--full"><label for="comment">' . esc_html__( 'Comment', 'mworago' ) . ' <span aria-hidden="true">*</span></label><textarea id="comment" name="comment" rows="5" maxlength="65525" required></textarea></div>',
        'fields'               => [
          'author'  => '<div class="cf-field"><label for="author">' . esc_html__( 'Name', 'mworago' ) . ' <span aria-hidden="true">*</span></label><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" maxlength="245" autocomplete="name" required /></div>',
          'email'   => '<div class="cf-field"><label for="email">' . esc_html__( 'Email', 'mworago' ) . ' <span aria-hidden="true">*</span></label><input id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" maxlength="100" autocomplete="email" required /></div>',
          'cookies' => '',
        ],
      ] );
    endif; ?>

  </div>
</section>

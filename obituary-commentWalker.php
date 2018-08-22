<?php
class Obituary_Walker_Comment extends Walker_Comment {

    protected function html5_comment( $comment, $depth, $args ) {
        $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
        ?>
        <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $this->has_children ? 'parent' : '' ); ?>>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">

            <div class="comment-content">

                <?php $candle = get_comment_meta( get_comment_ID(), 'candle', true ) ?>
                <?php
                if( $candle ) { ?>
                    <div class="candle-img">
                        <img src="<?= get_stylesheet_directory_uri() . '/images/candles/' . $candle . '.png'; ?>" width="70" height="70" alt="A lit Candle">
                    </div>
                    <?php
                }
                ?>

                <div class="comment-author vcard">
                    <?php printf( __( '%s' ), sprintf( '<h3 class="fn">%s</h3>', get_comment_author() ) ); ?>
                </div><!-- .comment-author -->

                <?php if ( '0' == $comment->comment_approved ) : ?>
                    <p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ); ?></p>
                <?php endif; ?>

                <div class="comment-text">
                    <?php comment_text(); ?>
                </div>

                <div class="comment-metadata">

                    <div class="reply one-half first">
                    <?php
                    comment_reply_link( array_merge( $args, array(
                        'reply_text'    => __( 'Report as abuse' ),
                        'add_below' => 'div-comment',
                        'depth'     => $depth,
                        'max_depth' => $args['max_depth'],
                        'before'    => '',
                        'after'     => ''
                    ) ) );
                    ?>&nbsp;
                    </div>

                    <div class="date one-half">
                        <a href="<?php echo esc_url( get_comment_link( $comment->comment_ID, $args ) ); ?>">
                            <time datetime="<?php comment_time( 'c' ); ?>">
                                <?php printf( _x( '%1$s', 'translations' ), get_comment_date('d/m/Y') ); ?>
                            </time>
                        </a>
                    </div>

                </div><!-- .comment-metadata -->

            </div><!-- .comment-content -->

        </article><!-- .comment-body -->
    <?php
    }
}

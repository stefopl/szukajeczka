<?php

/**
 * Plugin Name: Recent Comment by KalmusDesign
 * Plugin URI: #
 * Description: This plugin show recent comments.
 * Version: 1.0.1
 * Author: Grzegorz Kalmus
 * Author URI: #
 */

/**
 * @Author: Grzegorz Kalmus
 * @Date: 2022-04-03 20:09:02
 * @Desc: Display Last Comment
 */

add_shortcode( 'last_comment_shortcode', 'last_comment' );
function last_comment() {
    global $post;
    $args                = array(
        'number'  => '2',
        'post_type'      => array( 'strona' ),
    );
    $comments = get_comments( $args );
    foreach ( $comments as $comment ) :
        $comment_ID = $comment->comment_ID;
        $comment_post_ID = $comment->comment_post_ID;
        $comment_author  = get_comment_author( $comment_ID );
        $comment_text    = get_comment_text( $comment_ID );
        $comment_url     = get_comment_link( $comment_ID);
        ?>
        <div class="txt">
            Autor: <?php echo $comment_author; ?><br>
            <a href="<?php echo $comment_url ; ?>"
               title="<?php echo $comment_author; ?> | <?php echo get_the_title( $comment_post_ID ); ?>">
                <?php echo $comment_text; ?>
            </a>
        </div>
    <?php endforeach;
}
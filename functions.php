<?php
add_action( 'wp_enqueue_scripts', 'colormag_child_enqueue_styles' );
function colormag_child_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' ); 
}

/**
 * Shows meta information of post, with support to PublishPress Authors.
 *
 * @param bool $full_post_meta Whether to display full post meta or not.
 */
function colormag_entry_meta( $full_post_meta = true ) {

    if ( 'post' == get_post_type() ) :

        echo '<div class="below-entry-meta">';
        ?>

        <?php
        // Displays the same published and updated date if the post is never updated.
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

        // Displays the different published and updated date if the post is updated.
        if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
            $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
        }

        $time_string = sprintf(
            $time_string,
            esc_attr( get_the_date( 'c' ) ),
            esc_html( get_the_date() ),
            esc_attr( get_the_modified_date( 'c' ) ),
            esc_html( get_the_modified_date() )
        );

        printf(
        /* Translators: 1. Post link, 2. Post time, 3. Post date */
            __( '<span class="posted-on"><a href="%1$s" title="%2$s" rel="bookmark"><i class="fa fa-calendar-o"></i> %3$s</a></span>', 'colormag' ),
            esc_url( get_permalink() ),
            esc_attr( get_the_time() ),
            $time_string
        ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
        ?>

        <span class="byline">
            <?php $authors = get_multiple_authors(); ?>
            <?php foreach ($authors as $author) : ?>
                <span class="author vcard">
                    <i class="fa fa-user"></i>
                    <a class="url fn n"
                        href="<?php echo $author->link; ?>"
                        title="<?php echo $author->display_name; ?>"
                    >
                        <?php echo $author->display_name; ?>
                    </a>
                </span>
            <?php endforeach; ?>
        </span>

        <?php if ( ! post_password_required() && comments_open() ) { ?>
            <span class="comments">
                    <?php
                    if ( $full_post_meta ) {
                        comments_popup_link(
                            __( '<i class="fa fa-comment"></i> 0 Comments', 'colormag' ),
                            __( '<i class="fa fa-comment"></i> 1 Comment', 'colormag' ),
                            __( '<i class="fa fa-comments"></i> % Comments', 'colormag' )
                        );
                    } else {
                        ?>
                        <i class="fa fa-comment"></i><?php comments_popup_link( '0', '1', '%' ); ?>
                        <?php
                    }
                    ?>
                </span>
            <?php
        }

        if ( $full_post_meta ) {
            $tags_list = get_the_tag_list( '<span class="tag-links"><i class="fa fa-tags"></i>', __( ', ', 'colormag' ), '</span>' );

            if ( $tags_list ) {
                echo $tags_list; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
            }
        }

        if ( $full_post_meta ) {
            edit_post_link( __( 'Edit', 'colormag' ), '<span class="edit-link"><i class="fa fa-edit"></i>', '</span>' );
        }

        echo '</div>';

    endif;

}
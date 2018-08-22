<?php

// Start the engine 
require_once(get_template_directory() . '/lib/init.php');

// Child theme (do not remove) 
define('CHILD_THEME_NAME', 'G Seller');
define('CHILD_THEME_URL', 'http://www.gseller.co.uk/');

// required files
require_once('lib/enqueue.php');
require_once('lib/widgets/child-pages-widget.php');
require_once('lib/comments/custom-walker-comment.php');
require_once('woocommerce/woo-functions.php');

require_once('includes/custom-post-types/obituaries.php');
require_once('includes/custom-post-types/family-history.php');
require_once('includes/custom-post-types/meet-the-team.php');

// Add Viewport meta tag for mobile browsers 
add_action('genesis_meta', 'add_viewport_meta_tag');
function add_viewport_meta_tag()
{
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0"/>';
}

add_action('genesis_meta', 'add_meta_data_to_all_pages');
function add_meta_data_to_all_pages()
{
    // @todo: horrible way to do this but its what they want, who am I to argue...
    if (!is_front_page()) {
        echo '<meta name="description" content="G Seller and Co Ltd are an independent, family-run Funeral Directors and Memorial Masons based in Hinckley and Newbold Verdon in Leicestershire, England, UK."/>';
        echo '<meta name="keywords" content="G Seller and Co Ltd, Funeral Directors, Memorial Masons, Independent, family firm, Barsby family, Hinckley, Newbold Verdon, Leicestershire, Leicester, UK, England, tradition, compassionate, attentive, professional, funeral service, floral tributes, catering, stonemasonry, funeral arrangements, hearse, memorials, memorial restoration, obituaries, pre-paid funeral plans"/>';
    }
}

add_theme_support('html5');

// add logo if one is uploaded
add_action('genesis_site_title', 'add_logo', 9);
function add_logo()
{
    remove_action('genesis_site_title', 'genesis_seo_site_title');
    remove_action('genesis_site_description', 'genesis_seo_site_description');
    ?>
    <a href="<?php bloginfo('url') ?>" title="&larr; Go to home"><img
            src="<?= get_stylesheet_directory_uri(); ?>/images/logo.png"
            alt="<?php bloginfo('name'); ?> Logo"/></a>
    <?php
}

add_action('genesis_header', 'add_social_icons_to_header', 0);
function add_social_icons_to_header()
{ ?>
    <div class="social-wrap">
        <div class="wrap">
            <div class="social">
                <a href="https://facebook.com/GSellerCo/"><img
                        src="<?= get_stylesheet_directory_uri(); ?>/images/social/facebook_icon.png" alt=""></a>
                <a href="https://twitter.com/gsellerco"><img
                        src="<?= get_stylesheet_directory_uri(); ?>/images/social/twitter_icon.png" alt=""></a>
            </div>
        </div>
    </div>
    <?php
}

// change default footer in genesis
remove_action('genesis_footer', 'genesis_do_footer');
add_action('genesis_footer', 'new_footer');
function new_footer()
{ ?>
    <div class="footer cf">

        <div class="logo">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/footer/NAFD_logo.jpg" alt="NAFD Logo">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/footer/NAMM_logo.jpg" alt="NAMM logo">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/footer/Golden_Charter_logo.jpg"
                 alt="Golden Charter logo">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/footer/SAIF_logo.jpg" alt="SAIF logo">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/footer/FBU_logo.jpg" alt="FBU logo">
        </div>

        <div class="info">
            <p>&copy; <?php echo date('Y'); ?> G Seller &amp; Co Ltd. All Rights Reserved.</p>
        </div>

    </div>
    <?php
}

// add in the slideshow for the homepage
add_action('genesis_before_content_sidebar_wrap', 'homepage_slider');
function homepage_slider()
{
    if (is_front_page())
        echo do_shortcode('[new_royalslider id="4"]');
}

// add in the featured image to the top of the page if it has one!
add_action('genesis_before_content_sidebar_wrap', 'featured_image_banner');
function featured_image_banner()
{
    if(is_page() && has_post_thumbnail())
        the_post_thumbnail();
}

// for the single obituary
add_image_size('260x260', 260, 260, true);

//* Remove the entry title (requires HTML5 theme support)
//remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

// add script to admin pages for obituaries
add_action('admin_enqueue_scripts', 'add_admin_scripts', 10, 1);
function add_admin_scripts($hook)
{
    global $post;

    if ($hook == 'post-new.php' || $hook == 'post.php') {
        if ('obituaries' === $post->post_type) {
            wp_enqueue_script('obituary_js', get_stylesheet_directory_uri() . '/includes/custom-post-types/javascript/obituary-just-giving-search.js', array(), 1.0, true);
        }
    }
}

// simple style for the just giving search feature
add_action('admin_head', 'admin_css');
function admin_css()
{ ?>
    <style>
        p.just-giving-search {
            cursor: pointer;
            color: #0073AA;
        }

        p.just-giving-search:hover {
            text-decoration: underline;
        }
    </style>
    <?php
}

function get_custom_comments($comment_type = '')
{
    if ($comment_type === '')
        $comment_type = 'comment';

    $comment_args = array(
        'walker' => new Obituary_Walker_Comment,
        'order' => 'DESC',
        'style' => 'div',
        'orderby' => 'comment_date_gmt',
        'status' => 'approve',
        'post_id' => get_the_ID(),
        'type' => $comment_type
    );

    $comments = get_comments($comment_args);
    if (count($comments) > 0) {
        wp_list_comments($comment_args, $comments);
    } else { ?>
        <div class="no-comments">
            <p>There is nothing to display for this type of comment.</p>
        </div>
        <?php
    }
}

add_action('comment_form_logged_in_after', 'add_comment_fields');
add_action('comment_form_after_fields', 'add_comment_fields');
function add_comment_fields()
{
    if (is_singular('obituaries')) {
        echo '<input id="comment_type" name="comment_type" type="hidden" value="">';

        $candles = array(
            'green-bg',
            'red-bg',
            'purple-bg',
            'green-candle',
            'red-candle',
            'purple-candle'
        );

        $first = true;
        echo '<div id="candles">';
        foreach ($candles as $candle) {
            if ($first) {
                $checked = 'checked';
                $class = 'first';
                $first = false;
            } else {
                $checked = '';
                $class = '';
            }

            echo '<div class="one-sixth ' . $class . '"><input ' . $checked . ' type="radio" name="candle" id="candle" value="' . $candle . '"/><img src="' . get_stylesheet_directory_uri() . '/images/candles/' . $candle . '.png"></div>';
        }
        echo '</div>';
    }
}

function get_attendance_count($comment_type)
{
    $comment_args = array(
        'post_id' => get_the_ID(),
        'type' => $comment_type
    );
    $comments = get_comments($comment_args);
    return count($comments);
}

add_action('comment_post', 'save_comment_meta_data');
function save_comment_meta_data($comment_id)
{

    if ((isset($_POST['candle'])) && ($_POST['candle'] != '') && ($_POST['comment_type'] === 'candle'))
        $candle = wp_filter_nohtml_kses($_POST['candle']);

    add_comment_meta($comment_id, 'candle', $candle);

}

add_filter('preprocess_comment', 'comment_types');
function comment_types($commentdata)
{
    $comment_type = (isset($_POST['comment_type'])) ? trim($_POST['comment_type']) : '';

    if ($comment_type !== 'candle' || $comment_type !== 'attendance' || $comment_type !== '')
        $commentdata['comment_type'] = $comment_type;

    return $commentdata;
}

add_filter('comment_form_defaults', 'sp_remove_comment_form_allowed_tags');
function sp_remove_comment_form_allowed_tags($defaults)
{
    $defaults['comment_notes_after'] = '';
    return $defaults;
}

/**
 * Get the ordinal suffix of an int (e.g. th, rd, st, etc.)
 *
 * @param int $n
 * @param bool $return_n Include $n in the string returned
 * @return string $n including its ordinal suffix
 */
function ordinal_suffix($n)
{
    $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
    if ((($n % 100) >= 11) && (($n % 100) <= 13))
        return $n . 'th';
    else
        return $n . $ends[$n % 10];
}

function create_date_format($date)
{
    if (!$date)
        return null;

    $date = explode('-', $date);
    $date = ordinal_suffix($date[0]) . ' ' . $date[1] . ' ' . $date[2];

    return $date;
}

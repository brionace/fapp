<?php
// Template Name: Obituaries

// hook into the query before it is executed
add_action('pre_get_posts', 'add_where_clause_to_wp_query');
function add_where_clause_to_wp_query($query)
{
    global $custom_where_string;
    global $wpdb;

    $first_name = $query->query_vars['first_name'];
    $last_name = $query->query_vars['last_name'];
    $date_of_death = $query->query_vars['date_of_death'] ? date("d-F-Y", strtotime($query->query_vars['date_of_death'])) : '';

    $custom_where_string = "";

    if (isset($first_name)) {
        $custom_where_string .= " AND $wpdb->posts.post_title like '%$first_name%'";
    }

    if (isset($last_name)) {
        $custom_where_string .= " AND $wpdb->posts.post_title like '%$last_name%'";
    }

    if (!empty($date_of_death)) {
        $custom_where_string .= " AND $wpdb->postmeta.meta_key = 'obituaries_died' AND $wpdb->postmeta.meta_value = '$date_of_death'";
    }

    add_filter('posts_join', 'add_join_to_wp_query');
    add_filter('posts_where', 'my_posts_where');
}

function my_posts_where($where)
{
    global $custom_where_string;

    //append our custom where expression(s)
    $where .= $custom_where_string;

    //clean up to avoid unexpected things on other queries
    remove_filter('posts_where', 'my_posts_where');
    $custom_where_string = '';

    return $where;
}

function add_join_to_wp_query($join)
{
    global $wpdb;

    if (!empty($_GET['date-of-death'])) {
        $join .= "LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id ";
    }

    return $join;
}


add_action('genesis_entry_content', 'add_projects', 9);
function add_projects()
{
    global $post, $wp_query;

    $post_type_singular = 'obituary';
    $post_type_plural = 'obituaries';

    $posts_per_page = $_GET['searching'] === 'true' && (!empty($_GET['first-name']) || !empty($_GET['last-name'])) ? -1 : 20;

    $args = array(
        'post_type' => 'obituaries',
        'posts_per_page' => $posts_per_page,
        'post_status' => 'publish',
        'order' => 'DESC',

        'first_name' => $_GET['first-name'],
        'last_name' => $_GET['last-name'],
        'date_of_death' => $_GET['date-of-death']
    );

    $wp_query = new WP_Query($args);
    ?>

    <div class="search-obituaries">
        <h2>Search</h2>

        <div class="search-fields">
            <form id="obituary-search" method="get" action="/obituaries">
                <input name="page_id" value="<?= $_GET['page_id']; ?>" type="hidden">

                <input name="searching" id="searching" value="false" type="hidden">

                <div class="one-third first">
                    <label for="first-name">First Name</label>
                    <input type="text" name="first-name" id="first-name" value="<?= $_GET['first-name']; ?>">
                </div>

                <div class="one-third">
                    <label for="last-name">Last Name</label>
                    <input type="text" name="last-name" id="last-name" value="<?= $_GET['last-name']; ?>">
                </div>

                <div class="one-third">
                    <label for="date-of-death">Date Passed Away</label>
                    <input type="date" name="date-of-death" id="date-of-death" value="<?= $_GET['date-of-death']; ?>">
                </div>

                <input type="submit" value="Search">
            </form>
        </div>
    </div>

    <?php if ($wp_query->post_count > 0) : ?>
    <div class="<?= $post_type_plural; ?> clearfix">
        <?php
        $i = 0;
        if (have_posts()) : while (have_posts()) : the_post();
            setup_postdata($post);
            $pos = ($i % 2 == 0) ? 'first' : '';

            $born_on = create_date_format(get_post_meta($post->ID, $post_type_plural . '_born_on', true));
            $died = create_date_format(get_post_meta($post->ID, $post_type_plural . '_died', true));

            $funeral_date = create_date_format(get_post_meta($post->ID, $post_type_plural . '_funeral_date', true)); ?>

            <div class="<?= $post_type_singular; ?> one-half <?= $pos; ?>">
                <?= has_post_thumbnail() ? get_the_post_thumbnail($post->ID, 'thumbnail') : '<img src="' . get_stylesheet_directory_uri() . '/images/' . $post_type_singular . '-holder150x150.png" class="attachment-thumbnail">'; ?>
                <h2><?= $post->post_title; ?></h2>
                <p>(<?= $born_on . ' - ' . $died; ?>)</p>

                <p>Funeral: <?= $funeral_date ? $funeral_date : 'No date set.'; ?></p>

                <p><a href="<?= get_the_permalink($post->ID); ?>" class="button">View Obituary</a></p>
            </div>

            <?php
            $i++;
        endwhile;
            do_action('genesis_after_endwhile');
        endif; ?>

    </div>

    <div class="alert alert-info">
        <p>If you can’t find the obituary you are looking for on this page please use the search fields above.</p>
    </div>
<?php else: ?>
    <p>Sorry no <?= $post_type_plural; ?> could be found for this search criteria.</p>
<?php endif; ?>

    <script type="text/javascript">

        jQuery(document).ready(function ($) {

            $('#first-name, #last-name').on('keyup', function () {
                if ($('#first-name').val() !== '' || $('#last-name').val() !== '') {
                    $('#searching').val('true');
                } else {
                    $('#searching').val('false');
                }
            });

        });

    </script>

    <?php
    wp_reset_query();

    add_action('genesis_loop', 'be_custom_loop');
    remove_action('genesis_loop', 'genesis_do_loop');

}

remove_action('genesis_after_endwhile', 'genesis_posts_nav');

genesis();

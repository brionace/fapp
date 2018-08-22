<?php

function obituary_post_type() {

    // Labels
    $singular = 'Obituary';
    $plural = 'Obituaries';

    $labels = array(
        'name' => _x("$plural", "post type general name"),
        'singular_name' => _x("$plural", "post type singular name"),
        'menu_name' => "$plural",
        'add_new' => _x("Add New", "$plural item"),
        'add_new_item' => __("Add New $singular"),
        'edit_item' => __("Edit $singular"),
        'new_item' => __("New $singular"),
        'view_item' => __("View $singular"),
        'search_items' => __("Search $plural"),
        'not_found' =>  __("No $plural Found"),
        'not_found_in_trash' => __("No $plural Found in Trash"),
        'parent_item_colon' => ''
    );

    // Register post type
    register_post_type("$plural" , array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => false,
        'menu_icon' => '',
        'menu_position' => 100,
        'rewrite' => false,
        'supports' => array( 'title', 'editor', 'genesis-layouts', 'genesis-seo', 'thumbnail', 'comments' )
    ) );
}
add_action( 'init', 'obituary_post_type', 0 );

// http://melchoyce.github.io/dashicons/
add_action( 'admin_head', 'add_menu_icons_styles_quotes' );
function add_menu_icons_styles_quotes() { ?>

    <style>
        #adminmenu .menu-icon-obituaries div.wp-menu-image:before {
            content: "\f110";
        }
    </style>

<?php
}

// https://github.com/rilwis/meta-box/blob/master/demo/demo.php
add_filter( 'rwmb_meta_boxes', 'your_prefix_register_meta_boxes' );
function your_prefix_register_meta_boxes( $meta_boxes )
{
    $prefix = 'obituaries_';
    $meta_boxes[] = array(
        'id'         => 'charity-information',
        'title'      => __( 'Charity Information', 'translations' ),
        'post_types' => array( 'obituaries' ),
        'context'    => 'normal',
        'priority'   => 'high',
        'autosave'   => true,
        'fields'     => array(
            array(
                'name' => __( 'No Donations Accepted', 'your-prefix' ),
                'id'   => "{$prefix}no_donations",
                'type' => 'checkbox',
                'desc' => 'If this is ticked no matter what is entered below will not display.',
                'std'  => 0,
            ),
            array(
                'name'       => __( 'Search for a Charity', 'translations' ),
                'id'         => "{$prefix}charity_name_one",
                'type'       => 'text',
                'desc'       => __( 'Search by Charity name', 'translations' ),
            ),
            array(
                'name'       => __( 'Charity 1 ID', 'translations' ),
                'id'         => "{$prefix}charity_id_one",
                'type'       => 'text',
                'desc'       => __( 'Do not delete', 'translations' ),
            ),
            array(
                'name'       => __( 'Search for another Charity', 'translations' ),
                'id'         => "{$prefix}charity_name_two",
                'type'       => 'text',
                'desc'       => __( 'Use this to show the second charity box', 'translations' ),
            ),
            array(
                'name'       => __( 'Charity 2 ID', 'translations' ),
                'id'         => "{$prefix}charity_id_two",
                'type'       => 'text',
                'desc'       => __( 'Do not delete', 'translations' ),
            )
        )
    );
    $meta_boxes[] = array(
        'id'         => 'personal-information',
        'title'      => __( 'Personal Information', 'translations' ),
        'post_types' => array( 'obituaries' ),
        'context'    => 'normal',
        'priority'   => 'high',
        'autosave'   => true,
        'fields'     => array(
            array(
                'name'       => __( 'Born on', 'translations' ),
                'id'         => "{$prefix}born_on",
                'type'       => 'date',
                // jQuery date picker options. See here http://api.jqueryui.com/datepicker
                'js_options' => array(
                    'appendText'      => __( '(dd-Month-yyyy)', 'translations' ),
                    'dateFormat'      => __( 'd-MM-yy', 'translations' ),
                    'changeMonth'     => true,
                    'changeYear'      => true,
                    'showButtonPanel' => false,
                ),
            ),
            array(
                'name'       => __( 'Died', 'translations' ),
                'id'         => "{$prefix}died",
                'type'       => 'date',
                // jQuery date picker options. See here http://api.jqueryui.com/datepicker
                'js_options' => array(
                    'appendText'      => __( '(dd-Month-yyyy)', 'translations' ),
                    'dateFormat'      => __( 'd-MM-yy', 'translations' ),
                    'changeMonth'     => true,
                    'changeYear'      => true,
                    'showButtonPanel' => false,
                ),
            ),
        )
    );
    $meta_boxes[] = array(
        'id'         => 'funeral',
        'title'      => __( 'Funeral Information', 'translations' ),
        'post_types' => array( 'obituaries' ),
        'context'    => 'normal',
        'priority'   => 'high',
        'autosave'   => true,
        'fields'     => array(
            array(
                'name'       => __( 'Funeral Date', 'translations' ),
                'id'         => "{$prefix}funeral_date",
                'type'       => 'date',
                // jQuery date picker options. See here http://api.jqueryui.com/datepicker
                'js_options' => array(
                    'appendText'      => __( '(dd-Month-yyyy)', 'translations' ),
                    'dateFormat'      => __( 'd-MM-yy', 'translations' ),
                    'changeMonth'     => true,
                    'changeYear'      => true,
                    'showButtonPanel' => false,
                ),
            ),
            array(
                'name'       => __( 'Funeral Time', 'translations' ),
                'id'         => "{$prefix}funeral_time",
                'type'       => 'time',
                // jQuery datetime picker options.
                // For date options, see here http://api.jqueryui.com/datepicker
                // For time options, see here http://trentrichardson.com/examples/timepicker/
                'js_options' => array(
                    'stepMinute' => 5,
                    'showSecond' => false,
                ),
            ),
            array(
                'name' => __( 'Funeral Location', 'translations' ),
                'desc' => __( 'The address of the funeral', 'translations' ),
                'id'   => "{$prefix}funeral_location",
                'type' => 'textarea',
                'cols' => 20,
                'rows' => 4,
            ),
            array(
                'name'  => __( 'Post Code', 'translations' ),
                'id'    => "{$prefix}funeral_postcode",
                'desc'  => __( 'Add the post code to generate a map', 'translations' ),
                'type'  => 'text',
                'clone' => false,
            )
        )
    );
    $meta_boxes[] = array(
        'id'         => 'cremation',
        'title'      => __( 'Cremation Information', 'translations' ),
        'post_types' => array( 'obituaries' ),
        'context'    => 'normal',
        'priority'   => 'high',
        'autosave'   => true,
        'fields'     => array(
            array(
                'name'       => __( 'Cremation Date', 'translations' ),
                'id'         => "{$prefix}cremation_date",
                'type'       => 'date',
                // jQuery date picker options. See here http://api.jqueryui.com/datepicker
                'js_options' => array(
                    'appendText'      => __( '(dd-Month-yyyy)', 'translations' ),
                    'dateFormat'      => __( 'd-MM-yy', 'translations' ),
                    'changeMonth'     => true,
                    'changeYear'      => true,
                    'showButtonPanel' => false,
                ),
            ),
            array(
                'name'       => __( 'Cremation Time', 'translations' ),
                'id'         => "{$prefix}cremation_time",
                'type'       => 'time',
                // jQuery datetime picker options.
                // For date options, see here http://api.jqueryui.com/datepicker
                // For time options, see here http://trentrichardson.com/examples/timepicker/
                'js_options' => array(
                    'stepMinute' => 5,
                    'showSecond' => false,
                ),
            ),
            array(
                'name' => __( 'Cremation Location', 'translations' ),
                'desc' => __( 'The address of the cremation', 'translations' ),
                'id'   => "{$prefix}cremation_location",
                'type' => 'textarea',
                'cols' => 20,
                'rows' => 4,
            ),
            array(
                'name'  => __( 'Post Code', 'translations' ),
                'id'    => "{$prefix}cremation_postcode",
                'desc'  => __( 'Add the post code to generate a map', 'translations' ),
                'type'  => 'text',
                'clone' => false,
            )
        )
    );
    $meta_boxes[] = array(
        'id'         => 'reception',
        'title'      => __( 'Reception Information', 'translations' ),
        'post_types' => array( 'obituaries' ),
        'context'    => 'normal',
        'priority'   => 'high',
        'autosave'   => true,
        'fields'     => array(
            array(
                'name'       => __( 'Reception Date', 'translations' ),
                'id'         => "{$prefix}reception_date",
                'type'       => 'date',
                // jQuery date picker options. See here http://api.jqueryui.com/datepicker
                'js_options' => array(
                    'appendText'      => __( '(dd-Month-yyyy)', 'translations' ),
                    'dateFormat'      => __( 'd-MM-yy', 'translations' ),
                    'changeMonth'     => true,
                    'changeYear'      => true,
                    'showButtonPanel' => false,
                ),
            ),
            array(
                'name'       => __( 'Reception Time', 'translations' ),
                'id'         => "{$prefix}reception_time",
                'type'       => 'time',
                // jQuery datetime picker options.
                // For date options, see here http://api.jqueryui.com/datepicker
                // For time options, see here http://trentrichardson.com/examples/timepicker/
                'js_options' => array(
                    'stepMinute' => 5,
                    'showSecond' => false,
                ),
            ),
            array(
                'name' => __( 'Reception Location', 'translations' ),
                'desc' => __( 'The address of the reception', 'translations' ),
                'id'   => "{$prefix}reception_location",
                'type' => 'textarea',
                'cols' => 20,
                'rows' => 4,
            ),
            array(
                'name'  => __( 'Post Code', 'translations' ),
                'id'    => "{$prefix}reception_postcode",
                'desc'  => __( 'Add the post code to generate a map', 'translations' ),
                'type'  => 'text',
                'clone' => false,
            )
        )
    );
    return $meta_boxes;
}

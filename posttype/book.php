<?php

add_action('init', 'cyan_init');
function cyan_init()
{
    $labels = array(
        'name' => __('books', 'cyan'),
        'singular_name' => __('books', 'cyan'),
        'add_new' => __('Add New', 'cyan'),
        'add_new_item' => __('Add New book', 'cyan'),
        'edit_item' => __('edit book', 'cyan'),
        'new_item' => __('New book', 'cyan'),
        'view_item' => __('View book', 'cyan'),
        'search_items' => __('Search books', 'cyan'),
        'not_found' => __('No book found', 'cyan'),
        'not_found_in_trash' => __('No book found in Trash', 'cyan'),
        'parent_item_colon' => '',
        'menu_name' => __('book', 'cyan')

    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array(
            'title',
            'editor',
            'author',
            'thumbnail',
            'excerpt',
            'comments',
            'post-formats'
        )
    );
    register_post_type('books', $args);

    $Publisher = array(
        'name' => __('Publishers', 'cyan'),
        'singular_name' => __('Publisher', 'cyan'),
        'search_items' => __('Search', 'cyan'),
        'all_items' => __('All Publisher', 'cyan'),
        'parent_item' => __('Parent Publisher', 'cyan'),
        'parent_item_colon' => __('Parent Publisher:', 'cyan'),
        'edit_item' => __('Edit Publishers', 'cyan'),
        'update_item' => __('Update Publisher', 'cyan'),
        'add_new_item' => __('Add New Publisher', 'cyan'),
        'new_item_name' => __('New Publisher Name', 'cyan')
    );
    register_taxonomy('publisher', array(
        'books'
    ), array(
        'hierarchical' => true,
        'labels' => $Publisher,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array(
            'slug' => 'books'
        )
    ));

    $Authors = array(
        'name' => __('Authors', 'cyan'),
        'singular_name' => __('Author', 'cyan'),
        'search_items' => __('Search', 'cyan'),
        'all_items' => __('All Author', 'cyan'),
        'parent_item' => __('Parent Author', 'cyan'),
        'parent_item_colon' => __('Parent Author:', 'cyan'),
        'edit_item' => __('Edit Authors', 'cyan'),
        'update_item' => __('Update Author', 'cyan'),
        'add_new_item' => __('Add New Author', 'cyan'),
        'new_item_name' => __('New Author Name', 'cyan')
    );
    register_taxonomy('authors', array(
        'books'
    ), array(
        'hierarchical' => true,
        'labels' => $Authors,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array(
            'slug' => 'books'
        )
    ));

}
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
register_activation_hook( __FILE__, 'cyan_flush_rewrites' );
function cyan_flush_rewrites() {
    cyan_post_types_registration();
    flush_rewrite_rules();
}


function cyan_meta_box_markup()
{
     global $wpdb;
     global $_GET;
     $post_id = $_GET['post'];
     $table_name = $wpdb->prefix . "books_info";
     $result = $wpdb->get_results("SELECT * FROM $table_name WHERE post_ID='$post_id'");

    echo '<label for="isbn">'. __('isbn', 'cyan').': </label><input name="isbn" type="text" value="';
    if(!empty($result[0]->isbn)) { echo $result[0]->isbn;};
    echo '">';
}

function add_cyan_meta_box()
{
    add_meta_box(
        "demo-meta-box",
        __('isbn', 'cyan'),
        "cyan_meta_box_markup",
        "books",
        "side",
        "high",
        null
    );
}

add_action("add_meta_boxes", "add_cyan_meta_box");
 function cyan_save_metabox( $post_id ) {
    global $wpdb;
    global $_POST;
    $isbn = $_POST['isbn'];
    $table_name = $wpdb->prefix . "books_info";
    $result = $wpdb->get_row("SELECT COUNT(*) FROM $table_name WHERE post_ID='$post_id'", $like);
    if($result !== 0){
        $sql = "UPDATE  ". $table_name ."  SET isbn='$isbn' WHERE post_ID='$post_id'";
        $wpdb->query($sql);
    }else{
        $sql = "INSERT INTO ". $table_name ." (post_id, isbn) VALUES ('$post_id', '$isbn')";
        $wpdb->query($sql);
    }

}
add_action( 'save_post', 'cyan_save_metabox' );











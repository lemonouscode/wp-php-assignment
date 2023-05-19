<?php
/**
* Plugin Name: Movies
* Plugin URI: 
* Description: Movies Plugin.
* Version: 0.1
* Author: Zlatko
* Author URI:
**/


// Registering custom post type
function my_custom_posttype(){
    register_post_type('Movie',     // Post type name
        array(
            'has_archive'=> true,      // Archive enabled
            'labels'=>array(           // Label options (Visual display of elements)
                "name"=>__('Movies'),
                "singular_name"=>__('Movie'),
                "add_new"=>__('Add New Movie'),
                "add_new_item"=>__('Add New Movie'),
                "edit_item"=>__('Edit Movie'),
                "search_items"=>__('Search Movie')
            ),
            'menu_position'=>200,       // Item position on admin bar (Left side of dashboard)
            'public'=>true,             
            'show_in_rest' => true,     // Rest enabled otherwise wont be able to access this post type via rest api
            'exclude_from_search'=>true,
            'register_meta_box_cb'=>'movie_metabox',    // Registering metabox, callback function
            'supports'=>array('')       // Title or editor not included
        )
    );
}

add_action('init', 'my_custom_posttype');

// Logic for adding metabox
function movie_metabox(){
    add_meta_box('movie_metabox_customfields', 'Movie Custom Field', 'movie_metabox_display','movie','normal');
}

add_action('add_meta_boxes', 'movie_metabox');

// Metabox logic and display options
function movie_metabox_display(){
    
    global $post;
    $movie_title = get_post_meta($post->ID, 'movie_title', true);   // Getting movie title meta from post
    $movie_genre = get_post_meta($post->ID, 'movie_genre', true);   // Getting movie genre meta from post
    $movie_description = get_post_meta($post->ID, 'movie_description', true);   // Getting movie description meta from post

    ?>
        <div>
            <label for="movie_title">Movie Title:</label>
        </div>
        <input type="text" id="movie_title" name="movie_title" value="<?php echo $movie_title ?>">


        <div>
            <label for="movie_genre">Movie Genre:</label>
        </div>
        <input type="text" id="movie_genre" name="movie_genre" value="<?php echo $movie_genre; ?>">

        <div>
            <label for="movie_description">Movie Description:</label>
        </div>

        <textarea name="movie_description" id="movie_description" cols="30" rows="10"><?php echo $movie_description; ?></textarea>

    <?php

}


function movie_posttype_save($post_id){

    // if autosave or revision return
    $is_autosave = wp_is_post_autosave($post_id);
    $is_revision = wp_is_post_revision($post_id);

    if($is_autosave || $is_revision){
        return;
    }

    // Get post object
    $post = get_post($post_id);

    // If post type is movie then update all the fields
    if($post->post_type == "movie"){
        if(array_key_exists('movie_title',$_POST)){
            update_post_meta($post_id, 'movie_title', $_POST['movie_title']);
            update_post_meta($post_id, 'movie_genre', $_POST['movie_genre']);
            update_post_meta($post_id, 'movie_description', $_POST['movie_description']);

            // Un-hooking from save post to prevent infinity loop
            remove_action('save_post', 'movie_posttype_save');
            

            // Since we dont have default title but rather custom meta field with movie title
            // In order to prevent default naming of posts to "Post Draft" we need to programatically set post name
            // So we take post name from movie title form($_POST) and update post name-slug
            $post_update = array(
                'ID'         => $post->ID,
                'post_title' => $_POST['movie_title'],  // Post title in admin dashboard
                'post_name' => $_POST['movie_title']    // Post slug-url
            );
            
            wp_update_post( $post_update );


            add_action('save_post', 'movie_posttype_save');
        }
    }

    
}

add_action('save_post', 'movie_posttype_save');




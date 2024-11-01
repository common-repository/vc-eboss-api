<?php namespace eBossApi\Controllers;

use Herbert\Framework\Models\Post;

class AdminController
{

    public function __construct()
    {
        add_action('wp_ajax_ajaxRegionAction', array($this, 'ajaxRegionAction'));
        add_action('wp_ajax_nopriv_ajaxRegionAction', array($this, 'ajaxRegionAction'));
    }

    public static function installPages(Post $post)
    {
        if (!$post->find('search-job')) {
            echo 'bingo';
        }
        $basicSearch = array(
            'post_content' => '[basicSearch]',
            'post_name' => 'search-job',
            'post_title' => 'Search',
            'post_type' => 'page',
            'post_author' => get_current_user(),
            'post_status' => 'publish',
            'comment_status' => 'closed',
        );

        wp_insert_post($basicSearch);

    }

    function ajaxRegionAction()
    {
        wp_send_json(array('bingo'));
        wp_die();
    }

}

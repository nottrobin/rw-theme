<?php
    /**
     * @package WordPress
     * @subpackage RW_theme
     */

    require('utilityFunctions.php');

    /**
     * Get all global page data
     */
    $blogData = getAllBlogInfo();
    $blogData = array_merge($blogData, array(
        'title'            => wp_title(' | ', false, 'right') . get_bloginfo('name'),
        'archiveLinksHTML' => wp_get_archives('type=monthly&limit=5&format=link&echo=0'),
        'bodyClasses'      => getEchoFunctionContents('body_class'),
        'posts'            => array()
    ));

    /**
     * Get posts data
     */
    while (have_posts()) {
        the_post();
        
        $postId     = get_the_ID();

        // Get category data
        $categories = get_the_category();
        $categoryNames = array();
        foreach($categories as $category) {
            array_push($categoryNames, $category->cat_name);
        }

        // Add all relevant post data to the posts array
        array_push(
            $blogData['posts'],
            array_merge(
                get_object_vars(get_post($postId)),
                array(
                    'classes'       => get_post_class(),
                    'comments'      => get_comments(array('post_id' => $postId)),
                    'custom'        => get_post_custom(),
                    'permalink'     => get_permalink(),
                    'categories'    => $categories,
                    'categoryNames' => $categoryNames,
                    'categoriesStr' => implode(',', $categoryNames)
                )
            )
        );
    }
    echo "<pre>";
    print_r($blogData);
?>


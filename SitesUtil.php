<?
class SitesUtil {
    static function getEchoFunctionContents($funcName, Array $params = array()) {
        $output = '';

        ob_start();
        call_user_func($funcName, $params);
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }

    static function getWordpressData() {
        /**
         * Get all global page data
         */
        $blogDataParams = array(
            'url', 'wpurl', 'description', 'rdf_url' , 'rss_url', 'rss2_url'
            , 'atom_url', 'comments_atom_url', 'comments_rss2_url', 'pingback_url'
            , 'stylesheet_url', 'stylesheet_directory', 'template_directory'
            , 'template_url', 'admin_email', 'charset', 'html_type', 'version'
            , 'language', 'text_direction', 'name'
        );

        $blogData = array();

        foreach($blogDataParams as $blogDataParam) {
            $blogData[self::toCamelCase($blogDataParam)] = get_bloginfo($blogDataParam);
        }

        $blogData = array_merge($blogData, array(
            'title'            => wp_title(' | ', false, 'right') . get_bloginfo('name'),
            'archiveLinksHTML' => wp_get_archives('type=monthly&limit=5&format=link&echo=0'),
            'bodyClasses'      => self::getEchoFunctionContents('body_class'),
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

        return $blogData;
    }

    /**
    * Translates a string with underscores into camel case (e.g. first_name -&gt; firstName)
    * @param    string   $str                     String in underscore format
    * @param    bool     $capitalise_first_char   If true, capitalise the first char in $str
    * @return   string                              $str translated into camel caps
    */
    static function toCamelCase($str, $capitalise_first_char = false) {
        if($capitalise_first_char) {
            $str[0] = strtoupper($str[0]);
        }
        $func = create_function('$c', 'return strtoupper($c[1]);');
        return preg_replace_callback('/_([a-z])/', $func, $str);
    }

    static function addIncludePath($path) {
        set_include_path(get_include_path() . PATH_SEPARATOR . $path);
    }
}
?>


<?
    function getEchoFunctionContents($funcName, Array $params = array()) {
        $output = '';

        ob_start();
        call_user_func($funcName, $params);
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }

    function getAllBlogInfo() {
        $blogInfoParams = array(
            'url', 'wpurl', 'description', 'rdf_url' , 'rss_url', 'rss2_url'
            , 'atom_url', 'comments_atom_url', 'comments_rss2_url', 'pingback_url'
            , 'stylesheet_url', 'stylesheet_directory', 'template_directory'
            , 'template_url', 'admin_email', 'charset', 'html_type', 'version'
            , 'language', 'text_direction', 'name'
        );

        $blogInfo = array();
        foreach($blogInfoParams as $blogInfoParam) {
            $blogInfo[toCamelCase($blogInfoParam)] = get_bloginfo($blogInfoParam);
        }
        
        return $blogInfo;
    }

    /**
    * Translates a string with underscores into camel case (e.g. first_name -&gt; firstName)
    * @param    string   $str                     String in underscore format
    * @param    bool     $capitalise_first_char   If true, capitalise the first char in $str
    * @return   string                              $str translated into camel caps
    */
    function toCamelCase($str, $capitalise_first_char = false) {
        if($capitalise_first_char) {
            $str[0] = strtoupper($str[0]);
        }
        $func = create_function('$c', 'return strtoupper($c[1]);');
        return preg_replace_callback('/_([a-z])/', $func, $str);
    }
?>


<?/**
 * MIT License
 * ===========
 *
 * Copyright (c) 2012 Akers Liang
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
 * CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @category   [ Category ]
 * @package    [ Package ]
 * @subpackage [ Subpackage ]
 * @author Akers Liang
 * @copyright  2012 
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 * @version    0.1
 * @link       http://www.akers.tk
 */
 
 
/**
 * A safe way of adding JavaScripts to a WordPress generated page.
 */
if (!is_admin())
    add_action('wp_enqueue_scripts', 'ak_responsive_js');

if (!function_exists('ak_responsive_js')) {
    function ak_responsive_js() {
		wp_enqueue_script('ak-responsive-scripts', str_replace("\\", '/', get_stylesheet_directory_uri()) . '/js-dev/ak-responsive-scripts.js', array('jquery', 'jquery-ui-core', 'jquery-effects-core'));
    }
}

//register post type
add_action( 'init', 'ak_register_post_type' );
if(!function_exists('ak_register_post_type')):
    function ak_register_post_type()
    {
        $work_type = get_option('ak_works_type_name', 'akworks');
        register_post_type($work_type, array(
            'labels'=>array(
                'name' => _x('个人作品', 'post type general name'),
                'singular_name' => _x('Works', 'post type singular name'),
                'add_new' => _x('添加新作品', 'Works'),
                'add_new_item' => __('添加新作品'),
                'edit_item' => __('修改作品'),
                'new_item' => __('添加作品'),
                'view_item' => __('详细信息'),
                'search_items' => __('查找作品'),
                'not_found' =>  __('没有可显示的作品'),
                'not_found_in_trash' => __('回收站中没有可显示的作品'),
                'parent_item_colon' => ''
            ),
            // 'add_new'=>'添加新作品',
            'public'=>true,
            'capability_type'=>'post',
            'show_ui' => true,
            'rewrite' => array('slug'=>$work_type), 
            'has_archive' => true,
            'publicly_queryable' => true,
            'hierarchical'=>true,
            'query_var'=>true,
            'supports'=>array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions', 'post-formats', 'page-attributes', 'custom-fields'),
            // 'show_in_nav_menus'=>true,
            'show_in_menu'=>true,
            'taxonomies' => array('cat', 'category', 'post_tag')
        ));
        
        $ak_work_taxonomys = array();

        //register Language taxonomy
        $ak_work_taxonomys[] = array('name'=> 'Language', 'title'=>'开发语言');

        //register License taxonomy
        $ak_work_taxonomys[] = array('name'=> 'License', 'title'=>'开源协议');

        //register Author taxonomy
        $ak_work_taxonomys[] = array('name'=> 'Author', 'title'=>'开发人员');


        //register Runtime taxonomy
        $ak_work_taxonomys[] = array('name'=> 'Runtime', 'title'=>'运行环境');
        
        //register Usage taxonomy
        $ak_work_taxonomys[] = array('name'=> 'Usage', 'title'=>'软件分类');

        update_option('ak_work_taxonomys', json_encode($ak_work_taxonomys) );
        foreach ($ak_work_taxonomys as $value) {
            register_taxonomy($value['name'],$work_type,array(
                'hierarchical' => true,
                'public' => true,
                'labels' => array(
                    'name' => _x( $value['name'], $value['title']),
                    'singular_name' => _x( $value['name'], $value['title']),
                    'search_items' =>  __( 'Search '.$value['name'] ),
                    'all_items' => __( 'All '.$value['name'] ),
                    'parent_item' => __( 'Parent '.$value['name'] ),
                    'parent_item_colon' => __( 'Parent '.$value['name'].':' ),
                    'edit_item' => __( 'Edit '.$value['name'] ),
                    'update_item' => __( 'Update '.$value['name'] ),
                    'add_new_item' => __( 'Add New '.$value['name'] ),
                    'new_item_name' => __( 'New '.$value['name'].' Name' ),
                    'menu_name' => __($value['name']),
                ),
            ));
        }
        
    }
endif;

//刷新重定向
add_action('admin_init', 'flush_rewrite_rules');

include('includes/akworks_meta_boxes.php');


//option menu:
add_action("admin_menu", "wp_akworks_menus");

if(!function_exists("wp_akworks_menus")):
    function wp_akworks_menus()
    {
        add_menu_page( "AkWorks Options", 'AkWorks Options', 6, 'AkWorksMainMenu', "wp_akworks_page_options", null, 58 );
    }
endif;
//Manager MainPage(Option) Page:
if(!function_exists('wp_akworks_page_options')):
    function wp_akworks_page_options()
    {
		require('includes/ak_option.php');
	}
endif;

require('includes/ak_bottom_widget.php');
/* AkHomeBottom Widget */
/** 在 widgets_init钩子中加入一些函数. **/
function load_ak_widgets() {
    register_widget( 'AkHomeBottom_Widget' );
}
add_action( 'widgets_init', 'load_ak_widgets' );

//ak funcs
function ak_list_taxes($taxonomys, $taxonomy_name='', $before='', $dep=',')
{
    if(!is_array($taxonomys))
        return '';

    $rst = array();
    foreach ($taxonomys as $term) {
        if($term->taxonomy == $taxonomy_name)
            $rst[] = '<a href="'.get_term_link($term).'">'.$term->name.'</a>';
    }

    return $before.implode($rst, $dep);
}
?>
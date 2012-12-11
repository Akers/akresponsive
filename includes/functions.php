<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Theme's Functions and Definitions
 *
 *
 * @file           functions.php
 * @package        Responsive 
 * @author         Emil Uzelac Akers
 * @copyright      2012 Akers
 * @license        license.txt
 * @version        Release: 0.0.1
 * @filesource     wp-content/themes/responsive/includes/functions.php
 * @link           http://codex.wordpress.org/Theme_Development#Functions_File
 * @since          available since Release 1.0
 */
?>
<?php
/**
 * Fire up the engines boys and girls let's start theme setup.
 */
add_action('after_setup_theme', 'responsive_ak_setup');

if (!function_exists('responsive_ak_setup')):

    function responsive_ak_setup() {

        global $content_width;

        /**
         * Global content width.
         */
        if (!isset($content_width))
            $content_width = 550;

        /**
         * Responsive is now available for translations.
         * Add your files into /languages/ directory.
		 * @see http://codex.wordpress.org/Function_Reference/load_theme_textdomain
         */
	    load_theme_textdomain('responsive', get_template_directory().'/languages');

            $locale = get_locale();
            $locale_file = get_template_directory().'/languages/$locale.php';
            if (is_readable( $locale_file))
	            require_once( $locale_file);
						
        /**
         * Add callback for custom TinyMCE editor stylesheets. (editor-style.css)
         * @see http://codex.wordpress.org/Function_Reference/add_editor_style
         */
        add_editor_style();

        /**
         * This feature enables post and comment RSS feed links to head.
         * @see http://codex.wordpress.org/Function_Reference/add_theme_support#Feed_Links
         */
        add_theme_support('automatic-feed-links');

        /**
         * This feature enables post-thumbnail support for a theme.
         * @see http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
         */
        add_theme_support('post-thumbnails');

        /**
         * This feature enables custom-menus support for a theme.
         * @see http://codex.wordpress.org/Function_Reference/register_nav_menus
         */	
        register_nav_menus(array(
			'top-menu'         => __('Top Menu', 'responsive'),
	        'header-menu'      => __('Header Menu', 'responsive'),
	        'sub-header-menu'  => __('Sub-Header Menu', 'responsive'),
			'footer-menu'      => __('Footer Menu', 'responsive')
		    )
	    );

		if ( function_exists('get_custom_header')) {
			
        add_theme_support('custom-background');
		
		} else {
		
		// < 3.4 Backward Compatibility
		
		/**
         * This feature allows users to use custom background for a theme.
         * @see http://codex.wordpress.org/Function_Reference/add_custom_background
         */
		
        add_custom_background();
		
		}

		// WordPress 3.4 >
		if (function_exists('get_custom_header')) {
			
		add_theme_support('custom-header', array (
	        // Header image default
	       'default-image'			=> get_template_directory_uri() . '/images/default-logo.png',
	        // Header text display default
	       'header-text'			=> false,
	        // Header image flex width
		   'flex-width'             => true,
	        // Header image width (in pixels)
	       'width'				    => 300,
		    // Header image flex height
		   'flex-height'            => true,
	        // Header image height (in pixels)
	       'height'			        => 100,
	        // Admin header style callback
	       'admin-head-callback'	=> 'responsive_admin_header_style'));
		   
		// gets included in the admin header
        function responsive_admin_header_style() {
            ?><style type="text/css">
                .appearance_page_custom-header #headimg {
					background-repeat:no-repeat;
					border:none;
				}
             </style><?php
        }		  
	   
	    } else {
		   
        // Backward Compatibility
		
		/**
         * This feature adds a callbacks for image header display.
		 * In our case we are using this to display logo.
         * @see http://codex.wordpress.org/Function_Reference/add_custom_image_header
         */
        define('HEADER_TEXTCOLOR', '');
        define('HEADER_IMAGE', '%s/images/default-logo.png'); // %s is the template dir uri
        define('HEADER_IMAGE_WIDTH', 300); // use width and height appropriate for your theme
        define('HEADER_IMAGE_HEIGHT', 100);
        define('NO_HEADER_TEXT', true);
		
		
		// gets included in the admin header
        function responsive_admin_header_style() {
            ?><style type="text/css">
                #headimg {
	                background-repeat:no-repeat;
                    border:none !important;
                    width:<?php echo HEADER_IMAGE_WIDTH; ?>px;
                    height:<?php echo HEADER_IMAGE_HEIGHT; ?>px;
                }
             </style><?php
         }
         
		 add_custom_image_header('', 'responsive_admin_header_style');
		
	    }
    }
endif;

/*
* AkWorks Manager
*/

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
        $labels = array(
            'name' => _x( 'Program Language', 'the programing language of a work' ),
            'singular_name' => _x( 'Program Language', 'taxonomy singular name' ),
            'search_items' =>  __( 'Search Language' ),
            'all_items' => __( 'All Language' ),
            'parent_item' => __( 'Parent Language' ),
            'parent_item_colon' => __( 'Parent Language:' ),
            'edit_item' => __( 'Edit Language' ),
            'update_item' => __( 'Update Language' ),
            'add_new_item' => __( 'Add New Language' ),
            'new_item_name' => __( 'New Language Name' ),
            'menu_name' => __('Languages', 'category', 'post_tag', 'cat'),
        );

        $ak_work_taxonomys[] = array('name'=> 'Language', 'title'=>'开发语言');

        // register_taxonomy('Language',$work_type,array(
        //     'hierarchical' => true,
        //     'public' => true,
        //     'labels' => $labels
        // ));

        //register License taxonomy
        $labels = array(
            'name' => _x( 'License', 'the open license' ),
            'singular_name' => _x( 'License', 'open license' ),
            'search_items' =>  __( 'Search License' ),
            'all_items' => __( 'All License' ),
            'parent_item' => __( 'Parent License' ),
            'parent_item_colon' => __( 'Parent License:' ),
            'edit_item' => __( 'Edit License' ),
            'update_item' => __( 'Update License' ),
            'add_new_item' => __( 'Add New License' ),
            'new_item_name' => __( 'New License Name' ),
            'menu_name' => __('License', 'category', 'post_tag', 'cat'),
        );
        $ak_work_taxonomys[] = array('name'=> 'License', 'title'=>'开源协议');
        // register_taxonomy('License',$work_type,array(
        //     'hierarchical' => true,
        //     'public' => true,
        //     'labels' => $labels
        // ));

        //register Author taxonomy
        $labels = array(
            'name' => _x( 'Author', 'the open license' ),
            'singular_name' => _x( 'Author', 'open license' ),
            'search_items' =>  __( 'Search Author' ),
            'all_items' => __( 'All Author' ),
            'parent_item' => __( 'Parent Author' ),
            'parent_item_colon' => __( 'Parent Author:' ),
            'edit_item' => __( 'Edit Author' ),
            'update_item' => __( 'Update Author' ),
            'add_new_item' => __( 'Add New Author' ),
            'new_item_name' => __( 'New Author Name' ),
            'menu_name' => __('Author', 'category', 'post_tag', 'cat'),
        );
        $ak_work_taxonomys[] = array('name'=> 'Author', 'title'=>'开发人员');
        // register_taxonomy('Author',$work_type,array(
        //     'hierarchical' => true,
        //     'public' => true,
        //     'labels' => $labels
        // ));

        //register Runtime taxonomy
        $labels = array(
            'name' => _x( 'Runtime', 'the open license' ),
            'singular_name' => _x( 'Runtime', 'open license' ),
            'search_items' =>  __( 'Search Runtime' ),
            'all_items' => __( 'All Runtime' ),
            'parent_item' => __( 'Parent Runtime' ),
            'parent_item_colon' => __( 'Parent Runtime:' ),
            'edit_item' => __( 'Edit Runtime' ),
            'update_item' => __( 'Update Runtime' ),
            'add_new_item' => __( 'Add New Runtime' ),
            'new_item_name' => __( 'New Runtime Name' ),
            'menu_name' => __('Runtime', 'category', 'post_tag', 'cat'),
        );
        $ak_work_taxonomys[] = array('name'=> 'Runtime', 'title'=>'运行环境');
        // register_taxonomy('Runtime',$work_type,array(
        //     'hierarchical' => true,
        //     'public' => true,
        //     'labels' => $labels
        // ));
        
        //register Usage taxonomy
        $labels = array(
            'name' => _x( 'Usage', 'the open license' ),
            'singular_name' => _x( 'Usage', 'open license' ),
            'search_items' =>  __( 'Search Usage' ),
            'all_items' => __( 'All Usage' ),
            'parent_item' => __( 'Parent Usage' ),
            'parent_item_colon' => __( 'Parent Usage:' ),
            'edit_item' => __( 'Edit Usage' ),
            'update_item' => __( 'Update Usage' ),
            'add_new_item' => __( 'Add New Usage' ),
            'new_item_name' => __( 'New Usage Name' ),
            'menu_name' => __('Usage', 'category', 'post_tag', 'cat'),
        );
        $ak_work_taxonomys[] = array('name'=> 'Usage', 'title'=>'软件分类');
        update_option('ak_work_taxonomys', json_encode($ak_work_taxonomys) );
        foreach ($ak_work_taxonomys as $value) {
            register_taxonomy($value['name'],$work_type,array(
                'hierarchical' => true,
                'public' => true,
                'labels' => $labels
            ));
        }
        
    }
endif;

//刷新重定向
add_action('admin_init', 'flush_rewrite_rules');

//create akworks meta box input boxes
add_action('admin_menu', 'create_akworks_meta_box');

if(!function_exists("new_akworks_meta_boxes")):
    function new_akworks_meta_boxes()
    {
        global $post, $new_input_boxes;
        //input boxes array
        $new_input_boxes = array(
            'version' => array(
                'name' => 'ak_works_version',
                'std' => trim($val = get_post_meta($post->ID, 'ak_works_version', true)) == '' ? '在此输入版本号' : $val,
                'title' => '版本号',
                'incircle' => true
            ),
            'size' => array(
                'name' => 'ak_works_size',
                'std' => trim($val = get_post_meta($post->ID, 'ak_works_size', true)) == '' ?'在此输入项目大小' : $val,
                'title' => '项目大小',
                'incircle' => true
            ),
            'links' => array(
                'name' => 'ak_works_links',
                'std' => get_post_meta($post->ID, 'ak_works_links', true),
                'title' => '编辑下载链接：',
                'incircle' => false
            ),
            'lastupdate' => array(
                'name' => 'ak_works_lastupdate',
                'std' => trim($val = get_post_meta($post->ID, 'ak_works_lastupdate', true)) == '' ? date("Y-m-d") : $val,
                'title' => '最后更新：',
                'incircle' => false
            )
        );


        wp_enqueue_style('akworks-admin', get_template_directory_uri() . '/includes/akworks-admin.css', false, '1.0');
        wp_enqueue_script('akworks-admin', get_template_directory_uri() . '/includes/akworks-admin.js', array('jquery', 'jquery-ui-datepicker'), '1.0');
        foreach($new_input_boxes as $meta_box) {
            if($meta_box['incircle'])
            {
                echo'<input type="hidden" name="'.$meta_box['name'].'_noncename" id="'.$meta_box['name'].'_noncename" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';
                // 自定义字段标题
                // echo '<h4>'.$meta_box['title'].'</h4>';
                echo '<label for="'.$meta_box['name'].'">'.$meta_box['title'].'：</label>';
                // 自定义字段输入框
                echo '<input type="text" name="'.$meta_box['name'].'_value" value='.$meta_box['std'].' /><br />';
            }
        }

        //Last update
        echo'<input type="hidden" name="'.$new_input_boxes['lastupdate']['name'].'_noncename" id="'.$new_input_boxes['lastupdate']['name'].'_noncename" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';
        echo '<label for="ak_works_lastupdate">'.$new_input_boxes['lastupdate']['title'].'</label>';
        echo '<input type="text" id="'.$new_input_boxes['lastupdate']['name'].'" name="'.$new_input_boxes['lastupdate']['name'].'_value" size="12" readonly="true" placeholder="点击选择日期" value="'.$new_input_boxes['lastupdate']['std'].'"/>';
        echo '<br />';
        //Links 
        echo'<input type="hidden" name="'.$new_input_boxes['links']['name'].'_noncename" id="'.$new_input_boxes['links']['name'].'_noncename" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';
        ?>

        <label for="ak_works_links"><?php echo $new_input_boxes['links']['title']?></label>
        <textarea type="hidden" style="display:none;" name="<?php echo $new_input_boxes['links']['name'];?>_value" id="<?php echo $new_input_boxes['links']['name'];?>">
            <?php echo $new_input_boxes['links']['std']?>
        </textarea>
        <div id="ak_works_links_div">
            <table id="ak_works_links_table">
                <tr>
                    <th><input id="ak_works_links_select_all" type="checkbox" title="全选" /></th>
                    <th class="txt">显示文本</th>
                    <th class="href">链接地址</th>
                </tr>
            </table>
        </div>
        <div id="ak_works_links_ctrl">
            <input type="text" size="20" id="ak_works_links_text" placeholder="输入链接文本" />
            <input type="text" size="50" id="ak_works_links_href" placeholder="输入链接地址"/>
            <input type="button" id="ak_works_links_add" value="添加"/>
            <input type="button" id="ak_works_links_del" value="删除选中" />
        </div>
        <br />
        <?php
    }
endif;

//create akworks meta box
if(!function_exists("create_akworks_meta_box")):
    function create_akworks_meta_box()
    {
        global $theme_name;

        if ( function_exists('add_meta_box') ) {
            add_meta_box( 'new-meta-boxes', '自定义模块', 'new_akworks_meta_boxes', 'akworks', 'normal', 'high' );
        }
    }
endif;

//save work metas
add_action('save_post', 'save_akworks_postdata');

if(!function_exists('save_akworks_postdata')):
    global $post, $new_input_boxes;

    function save_akworks_postdata($post_id)
    {
        global $post, $new_input_boxes;

        foreach($new_input_boxes as $meta_box) 
        {
            if ( !wp_verify_nonce( $_POST[$meta_box['name'].'_noncename'], plugin_basename(__FILE__) ))
                return $post_id;

            if ( 'page' == $_POST['post_type'] ) {
                if ( !current_user_can( 'edit_page', $post_id ))
                    return $post_id;
            } 
            else {
                if ( !current_user_can( 'edit_post', $post_id ))
                    return $post_id;
            }

            $data = trim(esc_html($_POST[$meta_box['name'].'_value']));

            if($data == "")
                delete_post_meta($post_id, $meta_box['name'], get_post_meta($post_id, $meta_box['name'], true));
            else
                update_post_meta($post_id, $meta_box['name'], $data);
                
        }
    }
endif;

if(!function_exists("wp_akworks_uninstall")):
    function wp_akworks_uninstall()
    {
        if($_POST['delete'] == 'del')
            echo "<div id='message' class='updated'>删除成功!</div>";
        ?>
        <h1 style='display:block;width:500px;background-color:#E83C62;border-radius:5px;padding:10px 5px;box-shadow:2px 3px 7px 0 #8A231E;'>
            <strong style="display:block;color:#FAF9F5;font-size:30px;line-height:30px;">警告：确认清除后将删除所有数据记录，而且数据清除后将无法恢复，请慎重操作！！！！！！！！！！</strong>
        </h1>
        <form method="post">
            <input type="hidden" name="delete" value="del" />
            <input type="submit" class="button-primary" value="确认清除"/>
        </form>
        <?php
    }
endif;

add_action("admin_menu", "wp_akworks_menus");

if(!function_exists("wp_akworks_menus")):
    function wp_akworks_menus()
    {
        add_menu_page( "AkWorks", 'AkWorks', 6, 'AkWorksMainMenu', "wp_akworks_page_options", null, 4 );
        add_submenu_page( 'AkWorksMainMenu', 'Options', '选项设置', 6, 'AkOptionsLst', 'wp_akworks_page_options' );
        add_submenu_page( 'AkWorksMainMenu', 'ManageWork', '管理作品', 6, 'AkWorksManager', 'wp_akworks_page_manage' );
        add_submenu_page( 'AkWorksMainMenu', 'AddWork', '发布作品', 6, 'AkWorksEditor', 'wp_akworks_edit' );
        add_submenu_page( 'AkWorksMainMenu', 'ClearWork', '清除数据', 6, 'AkWorksCls', 'wp_akworks_uninstall' );
    }
endif;
//Manager MainPage(Option) Page:
if(!function_exists('wp_akworks_page_options')):
    function wp_akworks_page_options()
    {
        //action:
        if($_POST['submited'] == 'y')
        {
            update_option('ak_works_root_cat_id', $_POST['root_cat_id'] );
            update_option('ak_works_auto_up_tag', esc_html($_POST['auto_up_tag']) );
            update_option('ak_works_type_name', esc_html($_POST['works_type_name']) );
            echo "<div id='message' class='updated'>保存成功!</div>";
        }

        //ui:
        ?>
            <h2>AkWorks 作品管理选项设置</h2>
            <form action="" method="post" id="my_plugin_test_form">
                <div id="set_root_category">
                    <input type="hidden" name="submited" value="y" />
                    <label for="cat_select">作品存放根目录：</label>
                    <select name="root_cat_id" id="">
                        <?php 
                            $cats = get_categories('hide_empty=0&parent=0');
                            $cur_cat_id = get_option('ak_works_root_cat_id');
                            foreach ($cats as $cat)
                            {
                                if($cat->cat_ID == $cur_cat_id):
                                    ?><option selected="selected" value=<?php echo $cat->cat_ID?>><?php echo $cat->cat_name;?></option><?php
                                else:
                                    ?><option value=<?php echo $cat->cat_ID?>><?php echo $cat->cat_name;?></option><?php
                                endif;
                            }
                            
                        ?>
                    </select>
                </div>
                <br />
                <div id="set_auto_up_tags">
                    <label for="auto_up_tag">自动标签：</label>
                    <input name="auto_up_tag" value="<?php echo get_option('ak_works_auto_up_tag', '' );?>"/>
                    <div style="color:#8AA3AA">
                        为每个作品都添加一个统一的父标签，留空则不添加
                    </div>
                </div>
                <br />
                <div id="set_works_type_name">
                    <label for="auto_up_tag">类型名：</label>
                    <input name="works_type_name" value="<?php echo get_option('ak_works_type_name', '' );?>"/>
                    <div style="color:#8AA3AA">
                        用于区分作品页以及别的文章，不推荐修改
                    </div>
                </div>
                <br />
                <input type="submit" class="button-primary" value="保存设置"/>
            </form>
        <?php
    }
endif;
//Manager Manage Page:
if(!function_exists("wp_akworks_page_manage")):
    function wp_akworks_page_manage()
    {
        $title = 'AkWorksManager 作品管理';
        require('work_list.php');
    }
endif;
//Display edit work page
if(!function_exists('wp_akworks_edit')):
    function ak_get_cat_children($cats_all, $parent_id)
    {
        $rst = array();
        foreach ($cats_all as $value) 
        {
            if($value->parent == $parent_id)
                $rst[] = array('id'=>$value->term_id, 'name'=>$value->name, 'children'=>'');
        }
        return $rst;
    }
    function wp_akworks_edit()
    {
        $submit_type = 'create';
        
        require("work-editor.php");
        // wp_add_dashboard_widget("workintrdiv", '作品介绍', '', null);
    }
endif;
//View work
if(!function_exists('wp_akworks_view_work')):
    function wp_akworks_view_work()
    {
        // $post_id = isset($_POST['post_id']) ? $_POST['post_id'] : NULL;
        // Show work form.
        $post = get_default_post_to_edit( get_option('ak_works_type_name', $default = 'post' ), true );
        // print_r($post);
        $post_ID = $post->ID;
        $post_type = $post->post_type;
        $submit_type = 'view';
        $root_cat_id = get_option("ak_works_root_cat_id", $default = false );
        //获取分类目录
        $cats_all = get_categories("child_of={$root_cat_id}");
        require("work-editor.php");
        // wp_add_dashboard_widget("workintrdiv", '作品介绍', '', null);
    }
endif;
//works actions
if(!function_exists('wp_akworks_actions')):
    function wp_akworks_actions($method)
    {
        require('works.php');
    }
endif;

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function responsive_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'responsive_page_menu_args' );

/**
 * Remove div from wp_page_menu() and replace with ul.
 */
function responsive_wp_page_menu ($page_markup) {
    preg_match('/^<div class=\"([a-z0-9-_]+)\">/i', $page_markup, $matches);
        $divclass = $matches[1];
        $replace = array('<div class="'.$divclass.'">', '</div>');
        $new_markup = str_replace($replace, '', $page_markup);
        $new_markup = preg_replace('/^<ul>/i', '<ul class="'.$divclass.'">', $new_markup);
        return $new_markup; }

add_filter('wp_page_menu', 'responsive_wp_page_menu');

/**
 * Filter 'get_comments_number'
 * 
 * Filter 'get_comments_number' to display correct 
 * number of comments (count only comments, not 
 * trackbacks/pingbacks)
 *
 * Courtesy of Chip Bennett
 */
function responsive_comment_count( $count ) {  
	if ( ! is_admin() ) {
		global $id;
		$comments_by_type = &separate_comments(get_comments('status=approve&post_id=' . $id));
		return count($comments_by_type['comment']);
	} else {
		return $count;
	}
}
add_filter('get_comments_number', 'responsive_comment_count', 0);

/**
 * wp_list_comments() Pings Callback
 * 
 * wp_list_comments() Callback function for 
 * Pings (Trackbacks/Pingbacks)
 */
function responsive_comment_list_pings( $comment ) {
	$GLOBALS['comment'] = $comment;
?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>"><?php echo comment_author_link(); ?></li>
<?php }

/**
 * Sets the post excerpt length to 40 characters.
 * Next few lines are adopted from Coraline
 */
function responsive_excerpt_length($length) {
    return 40;
}

add_filter('excerpt_length', 'responsive_excerpt_length');

/**
 * Returns a "Read more" link for excerpts
 */
function responsive_read_more() {
    return '<div class="read-more"><a href="' . get_permalink() . '">' . __('Read more &#8250;', 'responsive') . '</a></div><!-- end of .read-more -->';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and responsive_read_more_link().
 */
function responsive_auto_excerpt_more($more) {
    return '<span class="ellipsis">&hellip;</span>' . responsive_read_more();
}

add_filter('excerpt_more', 'responsive_auto_excerpt_more');

/**
 * Adds a pretty "Read more" link to custom post excerpts.
 */
function responsive_custom_excerpt_more($output) {
    if (has_excerpt() && !is_attachment()) {
        $output .= responsive_read_more();
    }
    return $output;
}

add_filter('get_the_excerpt', 'responsive_custom_excerpt_more');


/**
 * This function removes inline styles set by WordPress gallery.
 */
function responsive_remove_gallery_css($css) {
    return preg_replace("#<style type='text/css'>(.*?)</style>#s", '', $css);
}

add_filter('gallery_style', 'responsive_remove_gallery_css');


/**
 * This function removes default styles set by WordPress recent comments widget.
 */
function responsive_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}
add_action( 'widgets_init', 'responsive_remove_recent_comments_style' );


/**
 * Breadcrumb Lists
 * Allows visitors to quickly navigate back to a previous section or the root page.
 *
 * Courtesy of Dimox
 *
 * bbPress compatibility patch by Dan Smith
 */
function responsive_breadcrumb_lists () {
  
  $chevron = '<span class="chevron">&#8250;</span>';
  $home = __('Home','responsive'); // text for the 'Home' link
  $before = '<span class="breadcrumb-current">'; // tag before the current crumb
  $after = '</span>'; // tag after the current crumb
 
  if ( !is_home() && !is_front_page() || is_paged() ) {
 
    echo '<div class="breadcrumb-list">';
 
    global $post;
    $homeLink = home_url();
    echo '<a href="' . $homeLink . '">' . $home . '</a> ' . $chevron . ' ';
 
    if ( is_category() ) {
      global $wp_query;
      $cat_obj = $wp_query->get_queried_object();
      $thisCat = $cat_obj->term_id;
      $thisCat = get_category($thisCat);
      $parentCat = get_category($thisCat->parent);
      if ($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' ' . $chevron . ' '));
      echo $before . __('Archive for ','responsive') . single_cat_title('', false) . $after;
 
    } elseif ( is_day() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $chevron . ' ';
      echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $chevron . ' ';
      echo $before . get_the_time('d') . $after;
 
    } elseif ( is_month() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $chevron . ' ';
      echo $before . get_the_time('F') . $after;
 
    } elseif ( is_year() ) {
      echo $before . get_the_time('Y') . $after;
 
    } elseif ( is_single() && !is_attachment() ) {
      if ( get_post_type() != 'post' ) {
        $post_type = get_post_type_object(get_post_type());
        $slug = $post_type->rewrite;
        echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $chevron . ' ';
        echo $before . get_the_title() . $after;
      } else {
        $cat = get_the_category(); $cat = $cat[0];
        echo get_category_parents($cat, TRUE, ' ' . $chevron . ' ');
        echo $before . get_the_title() . $after;
      }
 
    } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
      $post_type = get_post_type_object(get_post_type());
      echo $before . $post_type->labels->singular_name . $after;
 
    } elseif ( is_attachment() ) {
      $parent = get_post($post->post_parent);
      $cat = get_the_category($parent->ID); $cat = $cat[0];
      echo get_category_parents($cat, TRUE, ' ' . $chevron . ' ');
      echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $chevron . ' ';
      echo $before . get_the_title() . $after;
 
    } elseif ( is_page() && !$post->post_parent ) {
      echo $before . get_the_title() . $after;
 
    } elseif ( is_page() && $post->post_parent ) {
      $parent_id  = $post->post_parent;
      $breadcrumbs = array();
      while ($parent_id) {
        $page = get_page($parent_id);
        $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
        $parent_id  = $page->post_parent;
      }
      $breadcrumbs = array_reverse($breadcrumbs);
      foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $chevron . ' ';
      echo $before . get_the_title() . $after;
 
    } elseif ( is_search() ) {
      echo $before . __('Search results for ','responsive') . get_search_query() . $after;
 
    } elseif ( is_tag() ) {
      echo $before . __('Posts tagged ','responsive') . single_tag_title('', false) . $after;
 
    } elseif ( is_author() ) {
       global $author;
      $userdata = get_userdata($author);
      echo $before . __('All posts by ','responsive') . $userdata->display_name . $after;
 
    } elseif ( is_404() ) {
      echo $before . __('Error 404 ','responsive') . $after;
    }
 
    if ( get_query_var('paged') ) {
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
      echo __('Page','responsive') . ' ' . get_query_var('paged');
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
    }
 
    echo '</div>';
  }
} 


    /**
     * A safe way of adding JavaScripts to a WordPress generated page.
     */
    if (!is_admin())
        add_action('wp_enqueue_scripts', 'responsive_js');

    if (!function_exists('responsive_js')) {

        function responsive_js() {
			// JS at the bottom for fast page loading. 
			// except for Modernizr which enables HTML5 elements & feature detects.
			wp_enqueue_script('modernizr', get_template_directory_uri() . '/js-dev/responsive-modernizr.js', array('jquery'), '2.5.3', false);
            wp_enqueue_script('responsive-scripts', get_template_directory_uri() . '/js-dev/responsive-scripts.js', array('jquery'), '1.2.0', true);
			wp_enqueue_script('responsive-plugins', get_template_directory_uri() . '/js-dev/responsive-plugins.js', array('jquery'), '1.1.0', true);
        }

    }

    /**
     * A comment reply.
     */
        function responsive_enqueue_comment_reply() {
    if ( is_singular() && comments_open() && get_option('thread_comments')) { 
            wp_enqueue_script('comment-reply'); 
        }
    }

    add_action( 'wp_enqueue_scripts', 'responsive_enqueue_comment_reply' );
	
    /**
     * Where the post has no post title, but must still display a link to the single-page post view.
     */
    add_filter('the_title', 'responsive_title');

    function responsive_title($title) {
        if ($title == '') {
            return __('Untitled','responsive');
        } else {
            return $title;
        }
    }

    /**
     * Theme Options Support and Information
     */	
    function responsive_theme_support () {
    ?>
    
    <div id="info-box-wrapper" class="grid col-940">
        <div class="info-box notice">
            <a class="blue button" href="<?php echo esc_url(__('http://themeid.com/support/','responsive')); ?>" title="<?php esc_attr_e('Theme Support', 'responsive'); ?>" target="_blank">
            <?php printf(__('Theme Support','responsive')); ?></a>
            
            <a class="gray button" href="<?php echo esc_url(__('http://themeid.com/themes/','responsive')); ?>" title="<?php esc_attr_e('More Themes', 'responsive'); ?>" target="_blank">
            <?php printf(__('More Themes','responsive')); ?></a>
            
            <a class="gray button" href="<?php echo esc_url(__('http://themeid.com/showcase/','responsive')); ?>" title="<?php esc_attr_e('Showcase', 'responsive'); ?>" target="_blank">
            <?php printf(__('Showcase','responsive')); ?></a>
            
            <a class="gold button" href="<?php echo esc_url(__('http://themeid.com/donate/','responsive')); ?>" title="<?php esc_attr_e('Donate Now', 'responsive'); ?>" target="_blank">
            <?php printf(__('Donate Now','responsive')); ?></a>
        </div>
    </div>

    <?php }
 
    add_action('responsive_theme_options','responsive_theme_support');

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

	 
    /**
     * WordPress Widgets start right here.
     */
    function responsive_widgets_init() {
        register_sidebar(array(
            'name' => __('Main Sidebar', 'responsive'),
            'description' => __('Area One - sidebar.php', 'responsive'),
            'id' => 'main-sidebar',
            'before_title' => '<div class="widget-title">',
            'after_title' => '</div>',
            'before_widget' => '<div id="%1$s" class="widget-wrapper %2$s">',
            'after_widget' => '</div>'));

        register_sidebar(array(
            'name' => __('Right Sidebar', 'responsive'),
            'description' => __('Area Two - sidebar-right.php', 'responsive'),
            'id' => 'right-sidebar',
            'before_title' => '<div class="widget-title">',
            'after_title' => '</div>',
            'before_widget' => '<div id="%1$s" class="widget-wrapper %2$s">',
            'after_widget' => '</div>'
        ));
                
        register_sidebar(array(
            'name' => __('Left Sidebar', 'responsive'),
            'description' => __('Area Three - sidebar-left.php', 'responsive'),
            'id' => 'left-sidebar',
            'before_title' => '<div class="widget-title">',
            'after_title' => '</div>',
            'before_widget' => '<div id="%1$s" class="widget-wrapper %2$s">',
            'after_widget' => '</div>'
        ));
        
        register_sidebar(array(
            'name' => __('Left Sidebar Half Page', 'responsive'),
            'description' => __('Area Four - sidebar-left-half.php', 'responsive'),
            'id' => 'left-sidebar-half',
            'before_title' => '<div class="widget-title">',
            'after_title' => '</div>',
            'before_widget' => '<div id="%1$s" class="widget-wrapper %2$s">',
            'after_widget' => '</div>'
        ));
        
        register_sidebar(array(
            'name' => __('Right Sidebar Half Page', 'responsive'),
            'description' => __('Area Five - sidebar-right-half.php', 'responsive'),
            'id' => 'right-sidebar-half',
            'before_title' => '<div class="widget-title">',
            'after_title' => '</div>',
            'before_widget' => '<div id="%1$s" class="widget-wrapper %2$s">',
            'after_widget' => '</div>'
        ));

        register_sidebar(array(
            'name' => __('Home Widget 1', 'responsive'),
            'description' => __('Area Six - sidebar-home.php', 'responsive'),
            'id' => 'home-widget-1',
            'before_title' => '<div id="widget-title-one" class="widget-title-home"><h3>',
            'after_title' => '</h3></div>',
            'before_widget' => '<div id="%1$s" class="widget-wrapper %2$s">',
            'after_widget' => '</div>'
        ));

        register_sidebar(array(
            'name' => __('Home Widget 2', 'responsive'),
            'description' => __('Area Seven - sidebar-home.php', 'responsive'),
            'id' => 'home-widget-2',
            'before_title' => '<div id="widget-title-two" class="widget-title-home"><h3>',
            'after_title' => '</h3></div>',
            'before_widget' => '<div id="%1$s" class="widget-wrapper %2$s">',
            'after_widget' => '</div>'
        ));

        register_sidebar(array(
            'name' => __('Home Widget 3', 'responsive'),
            'description' => __('Area Eight - sidebar-home.php', 'responsive'),
            'id' => 'home-widget-3',
            'before_title' => '<div id="widget-title-three" class="widget-title-home"><h3>',
            'after_title' => '</h3></div>',
            'before_widget' => '<div id="%1$s" class="widget-wrapper %2$s">',
            'after_widget' => '</div>'
        ));

        register_sidebar(array(
            'name' => __('Gallery Sidebar', 'responsive'),
            'description' => __('Area Nine - sidebar-gallery.php', 'responsive'),
            'id' => 'gallery-widget',
            'before_title' => '<div class="widget-title">',
            'after_title' => '</div>',
            'before_widget' => '<div id="%1$s" class="widget-wrapper %2$s">',
            'after_widget' => '</div>'
        ));
    }
	
    add_action('widgets_init', 'responsive_widgets_init');
?>
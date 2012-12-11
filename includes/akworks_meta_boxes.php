<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

$new_input_boxes = array(//std 默认内容使用aspls:开头表示将默认内容显示成占位符！
	'version' => array(
		'name' => 'ak_works_version',
//		'std' => trim($val = get_post_meta($post->ID, 'ak_works_version', true)) == '' ? 'aspls:在此输入版本号' : $val,
		'std' => 'aspls:在此输入版本号',
		'title' => '版本号',
		'incircle' => true,
	),
	'size' => array(
		'name' => 'ak_works_size',
//		'std' => trim($val = get_post_meta($post->ID, 'ak_works_size', true)) == '' ?'aspls:在此输入项目大小' : $val,
		'std' => 'aspls:在此输入项目大小',
		'title' => '项目大小',
		'incircle' => true
	),
	'links' => array(
		'name' => 'ak_works_links',
//		'std' => get_post_meta($post->ID, 'ak_works_links', true),
		'std' => '',
		'title' => '编辑下载链接：',
		'incircle' => false
	),
	'lastupdate' => array(
		'name' => 'ak_works_lastupdate',
//		'std' => trim($val = get_post_meta($post->ID, 'ak_works_lastupdate', true)) == '' ? date("Y-m-d") : $val,
		'title' => '最后更新：',
		'incircle' => false
	)
);
function akInputBoxes(){
	return $GLOBALS['new_input_boxes'];
}

//register akworks meta box input boxes action
add_action('admin_menu', 'create_akworks_meta_box');

//create akworks meta box
if(!function_exists("create_akworks_meta_box")):
    function create_akworks_meta_box()
    {
        global $theme_name;
        if ( function_exists('add_meta_box') ) {
            add_meta_box( 'new-meta-boxes', '自定义模块', 'akworks_meta_boxes', 'akworks', 'normal', 'high' );
        }
    }
endif;

//akworks meta box ui:
if(!function_exists("akworks_meta_boxes")):
    function akworks_meta_boxes()
    {
        global $post, $new_input_boxes;
//		print_r(akInputBoxes());
		
        wp_enqueue_style('akworks-admin', get_stylesheet_directory_uri() . '/includes/akworks-admin.css', false, '1.0');
        wp_enqueue_script('akworks-admin', get_stylesheet_directory_uri() . '/includes/akworks-admin.js', array('jquery', 'jquery-ui-datepicker'), '1.0');
        foreach($new_input_boxes as $meta_box) {
            if($meta_box['incircle'])
            {
				$std = trim($val = get_post_meta($post->ID, $meta_box['name'], true)) == '' ? 'aspls:在此输入版本号' : $val;
                echo'<input type="hidden" name="'.$meta_box['name'].'_noncename" id="'.$meta_box['name'].'_noncename" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';
                // 自定义字段标题
                // echo '<h4>'.$meta_box['title'].'</h4>';
                echo '<label for="'.$meta_box['name'].'">'.$meta_box['title'].'：</label>';
                // 自定义字段输入框
                echo '<input type="text" name="'.$meta_box['name'].'_value" ';
//				value='.$meta_box['std'].' /><br />';
				if(strpos($std, 'aspls:') === 0)
					echo 'placeholder="'.str_replace('aspls:', '', $std).'"';
				else
					echo 'value="'.$std.'"';
				echo "/>";
            }
        }

        //Last update
        echo'<input type="hidden" name="'.$new_input_boxes['lastupdate']['name'].'_noncename" id="'.$new_input_boxes['lastupdate']['name'].'_noncename" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';
        echo '<label for="ak_works_lastupdate">'.$new_input_boxes['lastupdate']['title'].'</label>';
        echo '<input type="text" id="'.$new_input_boxes['lastupdate']['name'].'" name="'.$new_input_boxes['lastupdate']['name'].'_value" size="12" readonly="true" placeholder="点击选择日期" value="'.(trim($val = get_post_meta($post->ID, 'ak_works_lastupdate', true)) == '' ? date("Y-m-d") : $val).'"/>';
        echo '<br />';
        //Links 
        echo'<input type="hidden" name="'.$new_input_boxes['links']['name'].'_noncename" id="'.$new_input_boxes['links']['name'].'_noncename" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';
        ?>

        <label for="ak_works_links"><?php echo $new_input_boxes['links']['title']?></label>
        <textarea type="hidden" style="display:none;" name="<?php echo $new_input_boxes['links']['name'];?>_value" id="<?php echo $new_input_boxes['links']['name'];?>">
            <?php echo get_post_meta($post->ID, 'ak_works_links', true)?>
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

//save work metas
add_action('save_post', 'save_akworks_postdata');
if(!function_exists('save_akworks_postdata')):
    function save_akworks_postdata($post_id)
    {
        global $post, $new_input_boxes;
		
//		die(print_r($new_input_boxes));
		die($_POST);
		
        if(isset($new_input_boxes) && is_array($new_input_boxes))
        {
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
    }
endif;

?>

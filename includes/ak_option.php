<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;


//action:
if($_POST['submited'] == 'y')
{
	update_option('ak_works_root_cat_id', $_POST['root_cat_id'] );
//	update_option('ak_works_auto_up_tag', esc_html($_POST['auto_up_tag']) );
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

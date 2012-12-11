<?php


/*** Define the Widget as an extension of WP_Widget **/
class AkHomeBottom_Widget extends WP_Widget 
{
    function AkHomeBottom_Widget() 
	{
        /* Widget settings. */
        $widget_ops = array( 'classname' => 'AkHomeBottom_Widget', 'description' => 'Lists the children of the current or parent page' );
 
        /* Widget control settings. */
        $control_ops = array( 'id_base' => 'akhomebottom-widget' );
 
        /* Create the widget. */
        $this->WP_Widget( 'akhomebottom-widget', 'AkHomeBottom Widget', $widget_ops, $control_ops );
    }
 
    function widget( $args, $instance ) 
	{
        extract( $args );
        $title = apply_filters( 'widget_title', $instance['title'] );
//		$content = apply_filters( 'widget_title', $instance['content'] );
		?>
		<div class="widget-wrapper widget_text">
			<img class="aligncenter iconwidget" src="<?php echo esc_html($instance['icon'])?>"/>
			<a href="<?php echo $instance['href']?>">
			<div class="textwidget">
				<h3 class="widget-title-home"><?php echo $title?></h3>
				<p><?php echo esc_html($instance['content'])?></p>
			</div></a>
		</div>
		<?php
		
    }
 
    function update( $new_instance, $old_instance ) 
	{
        $instance = $old_instance;
        /* Strip tags (if needed) and update the widget settings. */
        $instance['title'] = trim(strip_tags( $new_instance['title'] ));
		$instance['content'] = trim(esc_html( $new_instance['content'] ));
		$instance['icon'] = trim(esc_html( $new_instance['icon'] ));
		$instance['href'] = trim(esc_html( $new_instance['href'] ));
		
        return $instance;
	}
 
    function form( $instance ) 
	{
        /* Set up some default widget settings. */
        $defaults = array( 'title' => 'Subpages' );
        $instance = wp_parse_args( (array) $instance, $defaults ); ?>
        <p>
			<label for="<?php echo $this->get_field_id( 'icon' ); ?>">Icon:</label>
			<img src="<?php echo $instance['icon']; ?>"/>
			<select id="<?php echo $this->get_field_id( 'icon' ); ?>" name="<?php echo $this->get_field_name( 'icon' );?>">
				<option value="<?php echo get_stylesheet_directory_uri()?>/images/folder.png">folder.png</option>
				<option value="<?php echo get_stylesheet_directory_uri()?>/images/page.png">page.png</option>
				<option value="<?php echo get_stylesheet_directory_uri()?>/images/user.png">user.png</option>
			</select>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
			<label for="<?php echo $this->get_field_id( 'content' ); ?>">Content:</label>
			<textarea id="<?php echo $this->get_field_id( 'content' ); ?>" name="<?php echo $this->get_field_name( 'content' ); ?>" cols="34" rows="5">
				<?php echo trim($instance['content']); ?>
			</textarea>
			<label for="<?php echo $this->get_field_id( 'href' ); ?>">Href:</label>
			<input id="<?php echo $this->get_field_id( 'href' ); ?>" name="<?php echo $this->get_field_name( 'href' ); ?>" value="<?php echo $instance['href']; ?>" />
		</p>
        <?php
    }
}

?>

<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Home Page
 *
 * Note: You can overwrite home.php as well as any other Template in Child Theme.
 * Create the same file (name) include in /responsive-child-theme/ and you're all set to go!
 * @see            http://codex.wordpress.org/Child_Themes
 *
 * @file           home.php
 * @package        Responsive 
 * @author         Emil Uzelac 
 * @copyright      2003 - 2012 ThemeID
 * @license        license.txt
 * @version        Release: 1.0
 * @filesource     wp-content/themes/responsive/home.php
 * @link           http://codex.wordpress.org/Template_Hierarchy
 * @since          available since Release 1.0
 */
?>
<?php get_header(); ?>

        <div id="featured" class="grid col-940">
			<a class="slid next" href="javascript:void(0)" title="下一个">下一个</a>
			<a class="slid pre" href="javascript:void(0)" title="上一个">上一个</a>
			<div>
			<div class="banner-content">
				<div class="grid col-380">
				<?php $options = get_option('responsive_theme_options');
				// First let's check if headline was set
					if ($options['home_headline']) {
						echo '<h1 class="featured-title">'; 
						echo $options['home_headline'];
						echo '</h1>'; 
				// If not display dummy headline for preview purposes
					  } else { 
						echo '<h1 class="featured-title">';
						echo __('Hello, World!','responsive');
						echo '</h1>';
					  }
				?>

				<?php $options = get_option('responsive_theme_options');
				// First let's check if headline was set
					if ($options['home_subheadline']) {
						echo '<h2 class="featured-subtitle">'; 
						echo $options['home_subheadline'];
						echo '</h2>'; 
				// If not display dummy headline for preview purposes
					  } else { 
						echo '<h2 class="featured-subtitle">';
						echo __('Your H2 subheadline here','responsive');
						echo '</h2>';
					  }
				?>

				<?php $options = get_option('responsive_theme_options');
				// First let's check if content is in place
					if ($options['home_content_area']) {
						echo '<p>'; 
						echo $options['home_content_area'];
						echo '</p>'; 
				// If not let's show dummy content for demo purposes
					  } else { 
						echo '<p>';
						echo __('Your title, subtitle and this very content is editable from Theme Option. Call to Action button and its destination link as well. Image on your right can be an image or even YouTube video if you like.','responsive');
						echo '</p>';
					  }
				?>

				<?php $options = get_option('responsive_theme_options'); ?>
				<?php if ($options['cta_button'] == 0): ?>     
				<div class="call-to-action">

				<?php $options = get_option('responsive_theme_options');
				// First let's check if headline was set
					if (!empty($options['cta_url']) && $options['cta_text']) {
						echo '<a href="'.$options['cta_url'].'" class="blue button">'; 
						echo $options['cta_text'];
						echo '</a>';
				// If not display dummy headline for preview purposes
					  } else { 
						echo '<a href="#nogo" class="blue button">'; 
						echo __('Call to Action','responsive');
						echo '</a>';
					  }
				?>  

				</div><!-- end of .call-to-action -->
				<?php endif; ?>

				</div><!-- end of .col-460 -->

				<div id="featured-image" class="grid col-540 fit"> 

					<?php $options = get_option('responsive_theme_options');
					// First let's check if image was set
						if (!empty($options['featured_content'])) {
							echo $options['featured_content'];
					// If not display dummy image for preview purposes
						  } else {             
							echo '<img class="aligncenter" src="'.get_stylesheet_directory_uri().'/images/featured-image.png" width="440" height="300" alt="" />'; 
						  }
					?> 

				</div><!-- end of #featured-image --> 
			</div><!-- end of .banner-content -->
		
			<?php //4 newest post here:
				query_posts(array(
					'post_type' => get_option('ak_works_type_name', "post"),
					'orderby' => 'date',
					'order' => 'DESC',
					'showposts' => 4,
				));
			?>
			<?php while(have_posts()) : the_post()?>
				<div class="banner-content">
					<div class="grid col-380">
						<h1 class="featured-title">最新发布：</h1>
						<h2 class="featured-subtitle"><?php echo the_title();?></h2>
						<p><?php echo the_content();?></p>
					</div>
					<div id="featured-image" class="grid col-540 fit">
						<?php if(has_post_thumbnail()):?>
							<?php echo the_post_thumbnail(array(440, 360), array('class'=>'aligncenter'))?>
						<?php else:?>
							<img class="aligncenter" src="<?php echo get_stylesheet_directory_uri()?>/images/aknoimgbg.png" width="440" height="300" alt="" />
						<?php endif;?>
					</div>
				</div>
			<?php endwhile;?>
			</div>
        </div><!-- end of #featured -->
               
<?php get_sidebar('home'); ?>
<?php get_footer(); ?>
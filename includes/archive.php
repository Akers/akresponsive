<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Archive Template
 *
 *
 * @file           archive.php
 * @package        Responsive 
 * @author         Emil Uzelac 
 * @copyright      2003 - 2012 ThemeID
 * @license        license.txt
 * @version        Release: 1.1
 * @filesource     wp-content/themes/responsive/archive.php
 * @link           http://codex.wordpress.org/Theme_Development#Archive_.28archive.php.29
 * @since          available since Release 1.0
 */
?>
<?php 
    get_header();
    if ( get_query_var('paged') )
        $paged = get_query_var('paged');
    elseif ( get_query_var('page') ) 
        $paged = get_query_var('page');
    else
        $paged = 1;

    //to display works!!!
    //if query by cat, check if cat of works in:
    $is_work_cat = false;
    if(($cat = get_query_var('cat')))
    {
        $root_cat_id = get_option("ak_works_root_cat_id");
        $cats_all = get_categories("hide_empty=0&child_of={$root_cat_id}");
        
        if(intval($cat) == intval($root_cat_id))
            $is_work_cat = true;
        else
        {
            foreach ($cats_all as $key => $value) 
            {
                if( intval($root_cat_id) == intval($cat) || $value->term_id == intval($cat))
                {
                    $is_work_cat = true;
                    break;
                }
            }
        }
    }

    //check the querying taxonomys
    $is_ak_tax = false;

    //get taxonomy names:
    if(isset($_SESSION['ak_work_taxonomys']))
        $taxonomys = $_SESSION['ak_work_taxonomys'];
    else
    {
        $taxonomys = json_decode(get_option( 'ak_work_taxonomys', ''));
        $_SESSION['ak_work_taxonomys'] = $taxonomys;
    }

    $term_names = array();

//print_r($taxonomys);

    foreach ($taxonomys as $v)
    {
        $term_names[] = $v->name;
        if(get_query_var(strtolower($v->name)))
            $is_ak_tax = true;
    }
    
//echo $is_ak_tax ? 'yse' : 'no!!!!';

    //query posts
    if($is_ak_tax || $is_work_cat)
    {
        global $query_string;
        query_posts("post_type=akworks&{$query_string}");

        // print_r(get_terms($term_names,'hide_empty=0&orderby=none'));
        $terms = get_terms($term_names,'hide_empty=0&orderby=none');
    }

?>

        <div id="content-archive" class="grid col-620">

<?php if (have_posts()) : ?>
        
        <?php $options = get_option('responsive_theme_options'); ?>
		<?php if ($options['breadcrumb'] == 0): ?>
		<?php echo responsive_breadcrumb_lists(); ?>
        <?php endif; ?>
        
		    <h6>
			    <?php if ( is_day() ) : ?>
				    <?php printf( __( 'Daily Archives: %s', 'responsive' ), '<span>' . get_the_date() . '</span>' ); ?>
				<?php elseif ( is_month() ) : ?>
					<?php printf( __( 'Monthly Archives: %s', 'responsive' ), '<span>' . get_the_date( 'F Y' ) . '</span>' ); ?>
				<?php elseif ( is_year() ) : ?>
					<?php printf( __( 'Yearly Archives: %s', 'responsive' ), '<span>' . get_the_date( 'Y' ) . '</span>' ); ?>
				<?php else : ?>
					<?php _e( 'Blog Archives', 'responsive' ); ?>
				<?php endif; ?>
			</h6>
            <?php if($is_ak_tax || $is_work_cat):?>
                <div class="work_guider">
                    <span><strong class="title">作品分类：</strong><a href="<?php echo get_category_link($root_cat_id); ?>">全部</a> </span>
                    <?php foreach($taxonomys as $tax):?>
                        <span><?php echo ak_list_taxes($terms, $tax->name, $tax->title.': ' , ' | ');?></span>
                    <?php endforeach;?>
                </div>
            <?php endif;?>         
        <?php while (have_posts()) : the_post(); ?>
            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <h1 class="post-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s', 'responsive'), the_title_attribute('echo=0')); ?>"><?php the_title(); ?></a></h1>
                
                <div class="post-meta">
                <?php 
                    printf( __( '<span class="%1$s">Posted on</span> %2$s by %3$s', 'responsive' ),'meta-prep meta-prep-author',
		            sprintf( '<a href="%1$s" title="%2$s" rel="bookmark">%3$s</a>',
			            get_permalink(),
			            esc_attr( get_the_time() ),
			            get_the_date()
		            ),
		            sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
			            get_author_posts_url( get_the_author_meta( 'ID' ) ),
			        sprintf( esc_attr__( 'View all posts by %s', 'responsive' ), get_the_author() ),
			            get_the_author()
		                )
			        );
		        ?>
				    <?php if ( comments_open() ) : ?>
                        <span class="comments-link">
                        <span class="mdash">&mdash;</span>
                    <?php comments_popup_link(__('No Comments &darr;', 'responsive'), __('1 Comment &darr;', 'responsive'), __('% Comments &darr;', 'responsive')); ?>
                        </span>
                    <?php endif; ?> 
                </div><!-- end of .post-meta -->

                <div class="post-entry">
                    <?php if ( has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
                    <?php the_post_thumbnail('thumbnail', array('class' => 'alignleft')); ?>
                        </a>
                    <?php endif; ?>
                    <?php if($post->post_type == get_option('ak_works_type_name', 'akworks')):?>
                        <div class="akwork-attr">
                            <span class="languages"><?php echo  get_the_term_list(get_the_ID(), 'Language', "开发语言: ", ',' )?></span>
                            <span class="size">项目大小：<?php echo get_post_meta(get_the_ID(), 'ak_works_size', true)?></span>
                            <span class="runtime"><?php echo  get_the_term_list(get_the_ID(), 'Runtime', "运行环境: ", ',' )?></span>
                            <span class="license"><?php echo  get_the_term_list(get_the_ID(), 'License', "授权协议: ", ',' )?></span>
                        </div><!-- end of akwork-attr -->
                        <p style="margin-bottom:-20px;margin-top:5px;text-weight:blod;"></p>
                    <?php endif;?>
                    <?php the_excerpt(); ?>
                    <?php wp_link_pages(array('before' => '<div class="pagination">' . __('Pages:', 'responsive'), 'after' => '</div>')); ?>
                </div><!-- end of .post-entry -->
                
                <div class="post-data">
				    <?php the_tags(__('Tagged with:', 'responsive') . ' ', ', ', '<br />'); ?> 
					<?php printf(__('Posted in %s', 'responsive'), get_the_category_list(', ')); ?>
                </div><!-- end of .post-data -->             

            <div class="post-edit"><?php edit_post_link(__('Edit', 'responsive')); ?></div>             
            </div><!-- end of #post-<?php the_ID(); ?> -->
            
            <?php comments_template( '', true ); ?>
            
        <?php endwhile; ?> 
        
        <?php if (  $wp_query->max_num_pages > 1 ) : ?>
        <div class="navigation">
			<div class="previous"><?php next_posts_link( __( '&#8249; Older posts', 'responsive' ) ); ?></div>
            <div class="next"><?php previous_posts_link( __( 'Newer posts &#8250;', 'responsive' ) ); ?></div>
		</div><!-- end of .navigation -->
        <?php endif; ?>

	    <?php else : ?>

        <h1 class="title-404"><?php _e('404 &#8212; Fancy meeting you here!', 'responsive'); ?></h1>
        <p><?php _e('Don&#39;t panic, we&#39;ll get through this together. Let&#39;s explore our options here.', 'responsive'); ?></p>
        <h6><?php _e( 'You can return', 'responsive' ); ?> <a href="<?php echo home_url(); ?>/" title="<?php esc_attr_e( 'Home', 'responsive' ); ?>"><?php _e( '&larr; Home', 'responsive' ); ?></a> <?php _e( 'or search for the page you were looking for', 'responsive' ); ?></h6>
        <?php get_search_form(); ?>

<?php endif; ?>  
      
        </div><!-- end of #content-archive -->
        
<?php get_sidebar(); ?>
<?php get_footer(); ?>
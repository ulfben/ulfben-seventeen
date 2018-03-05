<?php
/**
 * Template part for displaying posts
 * removes featured image 
 */
if(!function_exists('categories_and_tags')) {
	function categories_and_tags(){
		$result = '';
		if('post' !== get_post_type()){
			return $result;
		}
		$separate_meta = __( ', ', 'twentyseventeen' );    
		$categories_list = get_the_category_list( $separate_meta );
		$tags_list = get_the_tag_list( '', $separate_meta );           
		if(($categories_list && twentyseventeen_categorized_blog() ) || $tags_list){
			$result .= '<span class="cat-tags-links">';    
			if($categories_list && twentyseventeen_categorized_blog()){
				$result .= '<br />&nbsp;<span class="cat-links">' . twentyseventeen_get_svg( array( 'icon' => 'folder-open' ) ) . '<span class="screen-reader-text">' . __( 'Categories', 'twentyseventeen' ) . '</span>' . $categories_list . '</span>';
			}
			if($tags_list && !is_wp_error($tags_list)) {
				$result .= '<br />&nbsp;<span class="tags-links">' . twentyseventeen_get_svg( array( 'icon' => 'hashtag' ) ) . '<span class="screen-reader-text">' . __( 'Tags', 'twentyseventeen' ) . '</span>' . $tags_list . '</span>';
			}
			$result .= '&nbsp;</span>';
		}
		return $result;
	}
} 
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	if ( is_sticky() && is_home() ) :
		echo twentyseventeen_get_svg( array( 'icon' => 'thumb-tack' ) );
	endif;
	?>
	<header class="entry-header">
		<?php
		if ( 'post' === get_post_type() ) {
			echo '<div class="entry-meta">';
				if ( is_single() ) {
					twentyseventeen_posted_on(); 
					echo ' - '; comments_popup_link(); echo ' '; twentyseventeen_edit_link();
                    echo categories_and_tags();    
				} else {
					echo twentyseventeen_time_link();
					echo ' - '; comments_popup_link();  echo ' '; twentyseventeen_edit_link();
                    echo categories_and_tags();  
				};
			echo '</div><!-- .entry-meta -->';
		};

		if ( is_single() ) {
			the_title( '<h1 class="entry-title">', '</h1>' );
		} elseif ( is_front_page() && is_home() ) {
			the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
		} else {
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		}
		?>
	</header><!-- .entry-header -->

	

	<div class="entry-content">
		<?php
		/* translators: %s: Name of current post */
		the_content( sprintf(
			__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentyseventeen' ),
			get_the_title()
		) );

		wp_link_pages( array(
			'before'      => '<div class="page-links">' . __( 'Pages:', 'twentyseventeen' ),
			'after'       => '</div>',
			'link_before' => '<span class="page-number">',
			'link_after'  => '</span>',
		) );
		?>
	</div><!-- .entry-content -->

	<?php
	if ( is_single() ) {
		twentyseventeen_entry_footer();
	}
	?>

</article><!-- #post-## -->

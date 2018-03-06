<?php
function ulfben_seventeen_enqueue_styles() {
    $parent_style = 'parent-style';
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array($parent_style));
}
add_action('wp_enqueue_scripts', 'ulfben_seventeen_enqueue_styles');

//add facebook analytics
function hook_fb_analytics() {
?>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '', //TODO
      xfbml      : true,
      version    : 'v2.12'
    });  
    FB.AppEvents.logPageView();  
  };
  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "https://connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>
<?php
}
//add_action('wp_head', 'hook_fb_analytics'); //TODO

//add .current-tag to all tags related to the current post / view, to highlight the relevant parts of the tag cloud.
add_filter('wp_tag_cloud', 'tag_cloud_current_tag_highlight');
function tag_cloud_current_tag_highlight( $return ) {
	$post_tags = array();
	if(is_single()) {
		global $post;
		$post_tags = get_the_terms($post->id,'post_tag');
	}
	if(is_tag()) {
		$tags = explode( '+', get_query_var('tag') );
		foreach( $tags as $tag ) { $post_tags[] = get_term_by('slug',$tag,'post_tag'); }
	}
	if($post_tags) {
		foreach ($post_tags as $pt) {
			/*$pt: object(WP_Term)#1091 (10) { ["term_id"]=> int(145) ["name"]=> string(9) "inclusion" ["slug"]=> string(9) "inclusion" ["term_group"]=> int(0) ["term_taxonomy_id"]=> int(149) ["taxonomy"]=> string(8) "post_tag" ["description"]=> string(0) "" ["parent"]=> int(0) ["count"]=> int(7) ["filter"]=> string(3) "raw" } */			
			$tag = $pt->term_id;			
			$return = str_replace("tag-link-{$tag}", "tag-link-{$tag} current-tag", $return);
		}
	}
	return $return;
}

//used instead of twentyseventeen_entry_footer in /inc/template-tags.php
//adds comment-link to the entry footers.
function ulfbenseventeen_entry_footer() {
	/* translators: used between list items, there is a space after the comma */
	$separate_meta = __( ', ', 'twentyseventeen' );

	// Get Categories for posts.
	$categories_list = get_the_category_list( $separate_meta );

	// Get Tags for posts.
	$tags_list = get_the_tag_list( '', $separate_meta );

	// We don't want to output .entry-footer if it will be empty, so make sure its not.
	if ( ( ( twentyseventeen_categorized_blog() && $categories_list ) || $tags_list ) || get_edit_post_link() ) {
		echo '<footer class="entry-footer">';
			if ( 'post' === get_post_type() ) {
				if ( ( $categories_list && twentyseventeen_categorized_blog() ) || $tags_list ) {
					echo '<span class="cat-tags-links">';
						// Make sure there's more than one category before displaying.
						if ( $categories_list && twentyseventeen_categorized_blog() ) {
							echo '<span class="cat-links">' . twentyseventeen_get_svg( array( 'icon' => 'folder-open' ) ) . '<span class="screen-reader-text">' . __( 'Categories', 'twentyseventeen' ) . '</span>' . $categories_list . '</span>';
						}
						if ( $tags_list && ! is_wp_error( $tags_list ) ) {
							echo '<span class="tags-links">' . twentyseventeen_get_svg( array( 'icon' => 'hashtag' ) ) . '<span class="screen-reader-text">' . __( 'Tags', 'twentyseventeen' ) . '</span>' . $tags_list . '</span>';
						}
					echo '</span>';
				}
			}
			if(!is_single()){
				echo '<span class="comments-popup-link">';
					comments_popup_link();
				echo '</span>';
			}						
		echo '</footer> <!-- .entry-footer -->';
	}
}

//SVG icons functions and filters.
include_once( get_stylesheet_directory() . '/inc/social-menu-links.php' );

?>
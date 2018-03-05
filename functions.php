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

//SVG icons functions and filters.
include_once( get_stylesheet_directory() . '/inc/social-menu-links.php' );
?>
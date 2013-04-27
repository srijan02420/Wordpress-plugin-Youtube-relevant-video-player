<?php
/*
Plugin Name: WP Youtube Relevant Video Player
Version: 0.2
Description: Automatically searches video from youtube relevant for your blog by use of your blog title!! Also have option to display the youtube video of your choice just by coping the url!!Now can resize the video.
Author: Srijan Gupta
Author URI: srijan@www.bouncyballz.in
*/

// Author email -- srijan02420@gmail.com
// for any suggestions please feel free to contact me

global $wp_version;
$exit_msg='WP Youtube relevant Video Player requires WordPress 2.6 or newer.
<a href="http://codex.wordpress.org/Upgrading_WordPress">Please
update!</a>';
if (version_compare($wp_version,"2.3","<"))
	{
	exit ($exit_msg);
	}

$wp_youtube_plugin_url = trailingslashit( WP_PLUGIN_URL.'/'.dirname( plugin_basename(__FILE__)));

/** 
* function to get title and relevnt video
* use youtube api to get video 
* @get_option via function wp_youtube from wp-youtube-widget-control.php 
* @WP_get_title() extracts title of blog
* @newurl youtube api for title
**/
function WPYOUTUBE_Widget($args = array())
	{
		// extract the parameters
		extract($args);
		
		// get our options
		$options=get_option('wp_youtube');
		$title=$options['youtube_title'];
		$link = $options['youtube_link'];
		$size = $options['youtube_size'];
		
		if($size=='auto')
			{
			$width = 210;
			$height = 230;
			}
		else if($size=='long')
			{
			$width = 210;
			$height = 300;
			}
		else if($size=='wide')
			{
			$width = 263;
			$height = 300;
			}
		$url = parse_url($link);
			parse_str($url['query'], $query);	//getting id from url

		$id = $query['v'];
		
		// print the theme compatibility code
		echo $before_widget;
		echo $before_title . $title. $after_title;

		echo '<html><body>';	
		$search = WP_get_title();
					
		$newsearch = str_replace(" ", "%20", $search);	//replacing spaces with %20
		
		$newurl = 'http://gdata.youtube.com/feeds/api/videos?q='.$newsearch.'&max-results=1&v=2&alt=jsonc';	//calling youtube api
		$string = file_get_contents($newurl);
		$data = $json_a=json_decode($string,true);	//extracting id
		
		$v = $data["data"];
		$newid  = $v["items"][0]['id'];
		if ($id == null)
			{
				$id  = $v["items"][0]['id'];
			}
			
		echo '<iframe width="'.$width.'px" height="'.$height.'px" src="http://www.youtube.com/embed/'.$id.'" frameborder="0" allowfullscreen></iframe>';	//embedding video
		echo '</body></html>';

		echo $after_widget;
	}

/** 
* function to put it in sidebar
* @register_sidebar_widget registers widget in sidebar
* @WP_get_title() extracts title of blog
* @register_widget_control registers widget control
**/
function WPYOUTUBE_Init()
	{
		// register widget
		register_sidebar_widget('WP Youtube relevant Video Player', 'WPYOUTUBE_Widget');

		// register widget control
		register_widget_control('WP Youtube relevant Video Player', 'WPYOUTUBE_WidgetControl');
	}
	
	//performing initialization of widget
	add_action('init', 'WPYOUTUBE_Init');

	
/** 
* function for getting title for widget and url for video of your own choice
* @get_option via function wp_youtube from wp-youtube-widget-control.php 
**/
function WPYOUTUBE_WidgetControl()
	{
		// get saved options
		$options = get_option('wp_youtube');
		
		// handle user input
		if ( $_POST["youtube_submit"] )
			{
			$options['youtube_title'] = strip_tags( stripslashes($_POST["youtube_title"] ) );
			$options['youtube_link'] = strip_tags( stripslashes($_POST["youtube_link"] ) );
			$options['youtube_size'] = strip_tags( stripslashes($_POST["youtube_size"] ) );
			update_option('wp_youtube', $options);
			}
			
		$title = $options['youtube_title'];
		$link = $options['youtube_link'];
		$size = $options['youtube_size'];
		
		// print out the widget control
		include('wp-youtube-widget-control.php');
	}

/** 
* function to get title of the blog
* @bloginfo('name') gives title of blog
**/
function WP_get_title()
	{
		global $blogtitle;

		// get the page title
		$blogtitle=get_bloginfo('name');

		return $blogtitle;
	}

?>
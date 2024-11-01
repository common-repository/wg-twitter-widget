<?php
/**
 * Plugin Name: WG Twitter Widget
 * Plugin URI: http://marbu.org/weblog/2009/07/03/twitter-widget-per-wordpress-28/
 * Description: A multiple widget for twitter
 * Version: 1.0
 * Author: Marco Buttarini
 * Author URI: http://marbu.org
 */

  /*
   * Developed starting from the existing widget of Sarah Isaacson, 
   * using the example widget of Justin Tadlock
   */

add_action( 'widgets_init', 'twitter_wg_load_widgets' );

/**
 * Register our widget.
 * 'Twitter_wg_Widget' is the widget class used below.
 *
 * @since 0.1
 */
function twitter_wg_load_widgets() {
	register_widget( 'Twitter_wg_Widget' );
}

/**
 * Twitter_wg Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 * @since 0.1
 */
class Twitter_wg_Widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function Twitter_wg_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'twitter_wg', 'description' => __('Widget that displays a twitter account', 'twitter_wg') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'twitter_wg-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'twitter_wg-widget', __('WG Twitter Widget', 'twitter_wg'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$name = $instance['name'];
		$numberoftwit = $instance['numberoftwit'];
		$textbefore = $instance['textbefore'];
		$showimg = $instance['showimg'];
		$showimgonlyfirst = $instance['showimgonlyfirst'];
		$showdate = $instance['showdate'];
		$showtimefirst = $instance['showtimefirst'];

		$labelabout = $instance['labelabout'];
		$lessthen = $instance['lessthen'];
		$minuteago = $instance['minuteago'];
		$minutesago = $instance['minutesago'];
		$hourago = $instance['hourago'];
		$hoursago = $instance['hoursago'];
		$dayago = $instance['dayago'];
		$daysago = $instance['daysago'];

		//	$linkdate = $instance['linkdate'];

		/* Before widget (defined by themes). */
		echo $before_widget;


		if ( $title )
			echo $before_title . $title . $after_title;



		/*		if ( $name )
		 echo '<b>'.$name.'</b> ';*/
		$twitter_url = 'http://twitter.com/';	
		$my_twitter_feed_url = $twitter_url . 'statuses/user_timeline/' . $name;
		$my_twitter_feed_url.= '.json?callback=twitterCallback&amp;count='.$numberoftwit.'&amp;named_obj';
		$my_twitter = $twitter_url . $name . '/';
		$setcount =  $numberoftwit; 

		$stf=$showtimefirst;
		if($stf=="")$stf=0;
		$timetext = (' ');
?>

<script type="text/javascript" src="<?php echo $my_twitter_feed_url; ?>"></script>
   <div id="twitter-box-<? echo $name; ?>" class="wg-twitter" style="overflow: hidden; /* to hide the overflow of long urls */"><?
   	if ( $textbefore )
   	  echo '<p>'.$textbefore.'</p> ';
?>
 </div>
   <script type="text/javascript"><!--
   
   function relative_created_at(time_value) {  // thanks to Lionel of rarsh.com for pointing out that Twitter changed their code, and this is the fix which will work in IE
		  var created_at_time = Date.parse(time_value.replace(" +0000",""));
		  var relative_time = ( arguments.length > 1 ) ? arguments[1] : new Date();
		  //		  alert(relative_time);
		  var wordy_time = parseInt(( relative_time.getTime() - created_at_time ) / 1000) + (relative_time.getTimezoneOffset()*60);
		  
		  if ( wordy_time < 59 ) {
		    return '<? echo $lessthen; ?> 1 <? echo $minuteago; ?>';
		  } 
		  else if ( wordy_time < 119 ) {       // changed because otherwise you get 30 seconds of 1 minutes ago  
		    return '<? echo $labelabout; ?> 1 <? echo $minuteago; ?>';
		  } 
		  else if ( wordy_time < 3000 ) {         // < 50 minutes ago
		    return ( parseInt( wordy_time / 60 )).toString() + ' <? echo $minutesago; ?>';
		  } 
		  else if ( wordy_time < 5340 ) {         // < 89 minutes ago
		    return '<? echo $labelabout; ?> 1 <? echo $hourago; ?>';
		  } 
		  else if ( wordy_time < 9000 ) {          // < 150 minutes ago
		    return '<? echo $labelabout; ?> 2 <? echo $hoursago; ?>';  
		  }
		  else if ( wordy_time < 82800 ) {         // < 23 hours ago
		    return '<? echo $labelabout; ?> ' + ( parseInt( wordy_time / 3600 )).toString() + ' <? echo $hoursago; ?>';
		  } 
		  else if ( wordy_time < 129600 ) {       //  < 36 hours
		    return '1 <? echo $dayago; ?>';
		  }
		  else if ( wordy_time < 172800 ) {       // < 48 hours
		    return '<? echo $labelabout; ?> 2 <? echo $daysago; ?> ';
		  }
		  else {
		    return ( parseInt(wordy_time / 86400)).toString() + ' <? echo $daysago; ?>';
		  }
		}

		var ul = document.createElement('ul');
		for (var i=0; i < <?php echo $setcount ; ?> && i < Twitter.posts.length; i++) {
     var post = Twitter.posts[i]; 
     //     alert(post.user.profile_image_url);
     var li = document.createElement('li');

     var showTimeFirst = <?php echo $stf; ?>;
       if ( showTimeFirst == 1) {
          li.appendChild(document.createTextNode('<?php echo $timetext; ?>'));
          var a = document.createElement('a');
          a.setAttribute('href', '<?php echo $my_twitter; ?>' + 'statuses/' + post.id);
          a.setAttribute('title', 'Permalink a questa twitterata (id ' + post.id + ') su Twitter.com');
          a.appendChild(document.createTextNode(relative_created_at(post.created_at)));
          li.appendChild(a);
          li.appendChild(document.createTextNode('<?php echo $aftertimetext; ?>'));
          var br = document.createElement('br');
          li.appendChild(br);
          }

       <? if($showimg){ ?>
       <? if($showimgonlyfirst){ ?>
	 if(i==0){
	<? } ?>
	   var img = document.createElement('img');
	   img.setAttribute('src', post.user.profile_image_url);
	   li.appendChild(img);
       <? if($showimgonlyfirst){ ?>
	 }
     <? } ?>
     <? } ?>
     li.appendChild(document.createTextNode(post.text));
     if ( showTimeFirst == 0 ) {
     li.appendChild(document.createTextNode('<?php echo $timetext; ?>')); 
     
     var a = document.createElement('a');
     a.setAttribute('href', '<?php echo $my_twitter; ?>' + 'statuses/' + post.id);
     a.setAttribute('title', 'Permalink to this twitter (id ' + post.id + ') at Twitter.com');
     a.appendChild(document.createTextNode(relative_created_at(post.created_at)));
     li.appendChild(a); 
     
     li.appendChild(document.createTextNode('<?php echo $aftertimetext; ?>')); 
     }
     ul.appendChild(li);
     }
  ul.setAttribute('id', 'twitter-list');
  document.getElementById('twitter-box-<? echo $name; ?>').appendChild(ul);
-->
</script>
<?


		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['name'] = strip_tags( $new_instance['name'] );
		$instance['numberoftwit'] = $new_instance['numberoftwit'];
		$instance['textbefore'] = strip_tags($new_instance['textbefore']);
		$instance['showimg'] = $new_instance['showimg'];
		$instance['showimgonlyfirst'] = $new_instance['showimgonlyfirst'];
		$instance['showdate'] = $new_instance['showdate'];
		$instance['showtimefirst'] = $new_instance['showtimefirst'];
		//		$instance['linkdate'] = $new_instance['linkdate'];


		$instance['labelabout'] = $new_instance['labelabout'];
		$instance['lessthen'] = $new_instance['lessthen'];
		$instance['minuteago'] = $new_instance['minuteago'];
		$instance['minutesago'] = $new_instance['minutesago'];
		$instance['hourago'] = $new_instance['hourago'];
		$instance['hoursago'] = $new_instance['hoursago'];
		$instance['dayago'] = $new_instance['dayago'];
		$instance['daysago'] = $new_instance['daysago'];

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('Twitter', 'twitter_wg'), 'name' => __('Buttarini', 'twitter_wg'), 'numberoftwit' => '1', 'textbefore' => '', 'labelabout' => 'about', 'lessthen' => 'less then', 'minuteago' => 'minute ago', 'minutesago' => 'minutes ago', 'hourago' => 'hour ago', 'hoursago' => 'hours ago', 'dayago' => 'day ago', 'daysago' => 'days ago' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

	<!-- Widget Title: Text Input -->
	<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'twitter_wg'); ?></label>
	<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
	</p>

	<p>
	<label for="<?php echo $this->get_field_id( 'name' ); ?>"><?php _e('TwitterName:', 'twitter_wg'); ?></label>
	<input id="<?php echo $this->get_field_id( 'name' ); ?>" name="<?php echo $this->get_field_name( 'name' ); ?>" value="<?php echo $instance['name']; ?>" style="width:100%;" />
	</p>

	<p>
	<label for="<?php echo $this->get_field_id( 'numberoftwit' ); ?>"><?php _e('Twit number:', 'twitter_wg'); ?></label>
	<input id="<?php echo $this->get_field_id( 'numberoftwit' ); ?>" name="<?php echo $this->get_field_name( 'numberoftwit' ); ?>" value="<?php echo $instance['numberoftwit']; ?>" style="width:100%;" />
	</p>

	<p>
	<label for="<?php echo $this->get_field_id( 'textbefore' ); ?>"><?php _e('Text before:', 'twitter_wg'); ?></label>
	<input id="<?php echo $this->get_field_id( 'textbefore' ); ?>" name="<?php echo $this->get_field_name( 'textbefore' ); ?>" value="<?php echo $instance['textbefore']; ?>" style="width:100%;" />
	</p>

	<p>
	<label for="<?php echo $this->get_field_id( 'showimg' ); ?>"><?php _e('Show Image:', 'twitter_wg'); ?></label>
														  <input id="<?php echo $this->get_field_id( 'showimg' ); ?>" name="<?php echo $this->get_field_name( 'showimg' ); ?>" value="1" <?php if($instance['showimg']) echo "checked"; ?> style="width:100%;" type="checkbox" />
	</p>


	<p>
	<label for="<?php echo $this->get_field_id( 'showimgonlyfirst' ); ?>"><?php _e('Show Image only on first Twit:', 'twitter_wg'); ?></label>
	<input id="<?php echo $this->get_field_id( 'showimgonlyfirst' ); ?>" name="<?php echo $this->get_field_name( 'showimgonlyfirst' ); ?>" value="1" <?php if($instance['showimgonlyfirst']) echo "checked"; ?> style="width:100%;" type="checkbox" />
	</p>


	<p>
	<label for="<?php echo $this->get_field_id( 'showdate' ); ?>"><?php _e('Show Time:', 'twitter_wg'); ?></label>
        <input id="<?php echo $this->get_field_id( 'showdate' ); ?>" name="<?php echo $this->get_field_name( 'showdate' ); ?>" value="1" <?php if($instance['showdate']) echo "checked"; ?> style="width:100%;" type="checkbox" />
	</p>


	<p>
	<label for="<?php echo $this->get_field_id( 'showtimefirst' ); ?>"><?php _e('Show Time Before Twit:', 'twitter_wg'); ?></label>
        <input id="<?php echo $this->get_field_id( 'showtimefirst' ); ?>" name="<?php echo $this->get_field_name( 'showtimefirst' ); ?>" value="1" <?php if($instance['showtimefirst']) echo "checked"; ?> style="width:100%;" type="checkbox" />
	</p>
<!--
	<p>
	<label for="<?php echo $this->get_field_id( 'linkdate' ); ?>"><?php _e('Show link to twitter on Time:', 'twitter_wg'); ?></label>
        <input id="<?php echo $this->get_field_id( 'linkdate' ); ?>" name="<?php echo $this->get_field_name( 'linkdate' ); ?>" value="1" <?php if($instance['linkdate']) echo "checked"; ?> style="width:100%;" type="checkbox" />
	</p>
-->
<hr />
	<p>
	<label for="<?php echo $this->get_field_id( 'labelabout' ); ?>"><?php _e('Label for "about":', 'twitter_wg'); ?></label>
        <input id="<?php echo $this->get_field_id( 'labelabout' ); ?>" name="<?php echo $this->get_field_name( 'labelabout' ); ?>" value="<?php echo $instance['labelabout']; ?>" style="width:100%;" type="text" />
	</p>

	<p>
	<label for="<?php echo $this->get_field_id( 'lessthen' ); ?>"><?php _e('Label for "less then":', 'twitter_wg'); ?></label>
        <input id="<?php echo $this->get_field_id( 'lessthen' ); ?>" name="<?php echo $this->get_field_name( 'lessthen' ); ?>" value="<?php echo $instance['lessthen']; ?>" style="width:100%;" type="text" />
	</p>


	<p>
	<label for="<?php echo $this->get_field_id( 'minuteago' ); ?>"><?php _e('Label for "minute ago":', 'twitter_wg'); ?></label>
        <input id="<?php echo $this->get_field_id( 'minuteago' ); ?>" name="<?php echo $this->get_field_name( 'minuteago' ); ?>" value="<?php echo $instance['minuteago']; ?>" style="width:100%;" type="text" />
	</p>


	<p>
	<label for="<?php echo $this->get_field_id( 'minutesago' ); ?>"><?php _e('Label for "minutes ago":', 'twitter_wg'); ?></label>
        <input id="<?php echo $this->get_field_id( 'minutesago' ); ?>" name="<?php echo $this->get_field_name( 'minutesago' ); ?>" value="<?php echo $instance['minutesago']; ?>" style="width:100%;" type="text" />
	</p>


	<p>
	<label for="<?php echo $this->get_field_id( 'hourago' ); ?>"><?php _e('Label for "hour ago":', 'twitter_wg'); ?></label>
        <input id="<?php echo $this->get_field_id( 'hourago' ); ?>" name="<?php echo $this->get_field_name( 'hourago' ); ?>" value="<?php echo $instance['hourago']; ?>" style="width:100%;" type="text" />
	</p>


	<p>
	<label for="<?php echo $this->get_field_id( 'hoursago' ); ?>"><?php _e('Label for "hours ago":', 'twitter_wg'); ?></label>
        <input id="<?php echo $this->get_field_id( 'hoursago' ); ?>" name="<?php echo $this->get_field_name( 'hoursago' ); ?>" value="<?php echo $instance['hoursago']; ?>" style="width:100%;" type="text" />
	</p>


	<p>
	<label for="<?php echo $this->get_field_id( 'dayago' ); ?>"><?php _e('Label for "day ago":', 'twitter_wg'); ?></label>
        <input id="<?php echo $this->get_field_id( 'dayago' ); ?>" name="<?php echo $this->get_field_name( 'dayago' ); ?>" value="<?php echo $instance['dayago']; ?>" style="width:100%;" type="text" />
	</p>



	<p>
	<label for="<?php echo $this->get_field_id( 'daysago' ); ?>"><?php _e('Label for "days ago":', 'twitter_wg'); ?></label>
        <input id="<?php echo $this->get_field_id( 'daysago' ); ?>" name="<?php echo $this->get_field_name( 'daysago' ); ?>" value="<?php echo $instance['daysago']; ?>" style="width:100%;" type="text" />
	</p>



	<?php
	}
}

?>

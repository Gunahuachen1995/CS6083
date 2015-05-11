<?php
/**
* twitter_display.php
* Deliver a complete set of tweets including the supporting HTML elements
* @author Hongye Gong
*/

// Get constants for tweet display
require_once("twitter_display_config.php");
// Get the HTML structure 
$tweet_page = file_get_contents('tweet_list_template.txt');

// Fill in the most recent individual tweets
$tweet_page = str_replace( '[tweets]', 
  require_once('get_tweet_list.php'), $tweet_page); 
		
// Fill in the constants and strings needed by site.js after the page loads
$tweet_page = str_replace( '[new_count_refresh]', 
  NEW_COUNT_REFRESH, $tweet_page); 
$tweet_page = str_replace( '[ajax_url]', 
  AJAX_URL, $tweet_page); 
$tweet_page = str_replace('[ajax_tweet_url]', AJAX_TWEET_URL, $tweet_page);
$tweet_page = str_replace( '[more_button]', 
  MORE_BUTTON, $tweet_page); 

// Return the results as HTML
print $tweet_page;
?>
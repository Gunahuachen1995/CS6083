<?php 
/**
* get_tweet_list.php
* Return a list of the most recent tweets as HTML
* Older tweets are requested with the query of last=[tweet_id] by site.js
* 
* @author Adam Green <140dev@gmail.com>
* @license GNU Public License
* @version BETA 0.30
*/
require_once ('autoloader.php');// won't include it again in the following examples
require_once('twitter_display_config.php' );
require_once('display_lib.php');
require_once('../../db/db_lib.php' ); 
use NlpTools\Tokenizers\WhitespaceAndPunctuationTokenizer;

$oDB = new db;

$query = 'SELECT profile_image_url, created_at, screen_name, 
  name, tweet_text, tweet_id
  FROM tweets ';

// Query string of last=[tweet_id] means that this script was called by site.js
// when the More Tweets button was clicked
if (isset($_GET['last'])) {  
  $query .= 'WHERE tweet_id < "' . $_GET['last'] . '" ';
}
if(isset($_GET['search'])) {
  $query .= "WHERE tweet_text LIKE '%" . $_GET['search'] . "%'";
}

$query .= 'ORDER BY tweet_id DESC LIMIT ' . TWEET_DISPLAY_COUNT;
$result = $oDB->select($query);

// Use the text file tweet_template.txt to construct each tweet in the list
$tweet_template = file_get_contents('tweet_template.txt');
$tweet_list = '';
$tweets_found = 0;
$tok = new WhitespaceAndPunctuationTokenizer();
while (($row = mysqli_fetch_assoc($result))
  &&($tweets_found < TWEET_DISPLAY_COUNT)) { 
  $wordarr = array();
  ++$tweets_found; 
	
  // create a fresh copy of the empty template
  $current_tweet = $tweet_template;
	
  // Fill in the template with the current tweet
  $current_tweet = str_replace( '[profile_image_url]', 
    $row['profile_image_url'], $current_tweet);
  $current_tweet = str_replace( '[created_at]', 
    twitter_time($row['created_at']), $current_tweet);    		
  $current_tweet = str_replace( '[screen_name]', 
	  $row['screen_name'], $current_tweet);  
  $current_tweet = str_replace( '[name]', 
    $row['name'], $current_tweet);    
  $current_tweet = str_replace( '[user_mention_title]', 
    USER_MENTION_TITLE . ' ' . $row['screen_name'] . ' (' . $row['name'] . ')', 
    $current_tweet);  
  $current_tweet = str_replace( '[tweet_display_title]', 
    TWEET_DISPLAY_TITLE, $current_tweet);  
  $current_tweet = str_replace( '[mapid]', 
    $row['tweet_id'], $current_tweet);
  $current_tweet = str_replace( '[nlp_link]', 
    $row['tweet_text'], $current_tweet);
  $keys = $tok->tokenize($row['tweet_text']);
  foreach ($keys as $key) {   
    if (ctype_alpha($key)) {
      $query = "SELECT cityname FROM city where cityname = '" . $key . "'";
      $qresult = $oDB->select($query);
      if(mysqli_fetch_assoc($qresult)){
        $wordarr[] = $key;
      }   
    }
  }
  if(count($wordarr) > 0){
    $array = array();
    for($i = 0; $i < 2 && count($wordarr) > 0;  $i++){
      $lengths = array_map('strlen', $wordarr);
      $maxLength = max($lengths);
      $index = array_search($maxLength, $lengths);
      $array[] = $wordarr[$index];
      unset($wordarr[$index]);
      $wordarr = array_values($wordarr);
    }
    $symbol_separated = implode("+", $array);
    $current_tweet = str_replace( '[address]', $symbol_separated, $current_tweet);
  } else {
    $current_tweet = str_replace( '[address]', 'United States', $current_tweet);
  }

  $current_tweet = str_replace( '[tweet_text]', 
    linkify($row['tweet_text']), $current_tweet);  
		
  // Include each tweet's id so site.js can request older or newer tweets
  $current_tweet = str_replace( '[tweet_id]', 
    $row['tweet_id'], $current_tweet); 
		
  // Add this tweet to the list
  $tweet_list .= $current_tweet;
  unset($wordarr);
}

if (!$tweets_found) {
  if (isset($_GET['last'])) {
    $tweet_list = '<strong>No more tweets found</strong><br />';
  } else {
    $tweet_list = '<strong>No tweets found</strong><br />';	
  }	
}

if (isset($_GET['last']) || isset($_GET['search'])) {
  // Called by site.js with , so print HTML to the browser
  print $tweet_list;
} else {
  // Called by twitter_display.php with require(), so return the value
  return $tweet_list;
}

?>
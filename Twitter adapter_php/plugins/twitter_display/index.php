<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
<head>
<title>Twitter display adapter</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="default.css" type="text/css" />
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js">
</script>
<script type="text/javascript" src="site.js">
</script>
</head>
<body>
 <div id="tfheader">
 		<h2 style="margin-top: 0px;">Twitter display adapter</h2>
		<div id="tfnewsearch">
		        <input type="text" class="tftextinput" name="q" size="21" maxlength="120">
		        <input type="submit" value="search" class="tfbutton">
		</div>
	<div class="tfclear"></div>
 </div>
 <div id="tweetslist">
<?php require('twitter_display.php'); ?>
</div>
</body>
</html>

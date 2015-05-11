<?php 
/**
* db_test.php
* Use this script to test your database installation 
* @author Hongye Gong
*/
require_once('db_lib.php');
$oDB = new db;
print '<strong>Twitter Database Tables</strong><br />';
$result = $oDB->select('SHOW TABLES');
while ($row = mysqli_fetch_row($result)) {
  print $row[0] . '<br />';
}
?>
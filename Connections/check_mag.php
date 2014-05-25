<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_check_mag = "localhost";
$database_check_mag = "php_test";
$username_check_mag = "phptestuser";
$password_check_mag = "1qaz2wsx";
$check_mag = mysql_pconnect($hostname_check_mag, $username_check_mag, $password_check_mag) or trigger_error(mysql_error(),E_USER_ERROR); 
?>
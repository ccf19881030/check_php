<?php require_once('../Connections/check_mag.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_getPosts = 10;
$pageNum_getPosts = 0;
if (isset($_GET['pageNum_getPosts'])) {
  $pageNum_getPosts = $_GET['pageNum_getPosts'];
}
$startRow_getPosts = $pageNum_getPosts * $maxRows_getPosts;

mysql_select_db($database_check_mag, $check_mag);
$query_getPosts = "SELECT post_id, title, updated FROM news ORDER BY updated DESC";
$query_limit_getPosts = sprintf("%s LIMIT %d, %d", $query_getPosts, $startRow_getPosts, $maxRows_getPosts);
$getPosts = mysql_query($query_limit_getPosts, $check_mag) or die(mysql_error());
$row_getPosts = mysql_fetch_assoc($getPosts);

if (isset($_GET['totalRows_getPosts'])) {
  $totalRows_getPosts = $_GET['totalRows_getPosts'];
} else {
  $all_getPosts = mysql_query($query_getPosts);
  $totalRows_getPosts = mysql_num_rows($all_getPosts);
}
$totalPages_getPosts = ceil($totalRows_getPosts/$maxRows_getPosts)-1;

$queryString_getPosts = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_getPosts") == false && 
        stristr($param, "totalRows_getPosts") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_getPosts = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_getPosts = sprintf("&totalRows_getPosts=%d%s", $totalRows_getPosts, $queryString_getPosts);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Manage Posts</title>
<link href="../styles/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Manage Posts</h1>
<p><a href="index.php">Admin menu</a></p>
<p><strong><a href="add_post.php">Add new post</a></strong></p>
<table width="800" border="1">
  <tr>
    <th scope="col">Date</th>
    <th scope="col">Title</th>
    <th scope="col">&nbsp;</th>
    <th scope="col">&nbsp;</th>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_getPosts['updated']; ?></td>
      <td><?php echo $row_getPosts['title']; ?></td>
      <td><a href="update_post.php?<?php echo $row_getPosts['post_id']; ?>=">EDIT</a></td>
      <td><a href="delete_post.php?<?php echo $row_getPosts['post_id']; ?>=">DELETE</a></td>
    </tr>
    <?php } while ($row_getPosts = mysql_fetch_assoc($getPosts)); ?>
</table>
<table border="0">
  <tr>
    <td><?php if ($pageNum_getPosts > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_getPosts=%d%s", $currentPage, 0, $queryString_getPosts); ?>">First</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_getPosts > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_getPosts=%d%s", $currentPage, max(0, $pageNum_getPosts - 1), $queryString_getPosts); ?>">Previous</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_getPosts < $totalPages_getPosts) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_getPosts=%d%s", $currentPage, min($totalPages_getPosts, $pageNum_getPosts + 1), $queryString_getPosts); ?>">Next</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_getPosts < $totalPages_getPosts) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_getPosts=%d%s", $currentPage, $totalPages_getPosts, $queryString_getPosts); ?>">Last</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($getPosts);
?>

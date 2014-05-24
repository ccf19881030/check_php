<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Update Post</title>
<link href="../styles/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>what's the Buzz?</h1>
<p><a href="index.php">Add Menu</a></p>
<form id="form1" name="form1" method="post" action="">
  <p>
    <label for="title">Title:</label>
    <input name="title" type="text" class="textfields" id="title" maxlength="150" />
    <label for="blog_entry">Post:</label>
    <textarea name="blog_entry" id="blog_entry" cols="45" rows="5"></textarea>
  </p>
  <p>
    <input type="submit" name="update" id="update" value="Update Post" />
  </p>
</form>
</body>
</html>
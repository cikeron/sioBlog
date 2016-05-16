<?php
if (file_exists("object_blog/object_blog.php")) {
	require("object_blog/object_blog.php");
	$blog=new object_blog();
	$blog->create();
} else {
  echo "CAGADA Y MUUUU GORDA.....";
}

?>

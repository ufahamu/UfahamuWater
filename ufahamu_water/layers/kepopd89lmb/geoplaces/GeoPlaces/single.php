<?php
if($post->post_type == CUSTOM_POST_TYPE1)
{
	require_once (TEMPLATEPATH . '/single-place.php');
}
else if($post->post_type == CUSTOM_POST_TYPE2)
{
	require_once (TEMPLATEPATH . '/single-event.php');
}
else
{
	require_once (TEMPLATEPATH . '/single-blog.php');
}
?>
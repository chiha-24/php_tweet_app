<?php
if(isset($search)){
  $counted = count($search);
  echo "<p>ユーザーが $counted 件見つかりました</p>";
  foreach ($search as $user){
  echo "<img src=$user->profile_image_url></img>";
  echo "<h1> $user->name</h1>";
  echo "<p> @$user->screen_name</p>";
  echo "<p>$user->description</p>";
  }
} else {
  echo "<p>ユーザーが見つかりませんでした</p>";
}
?>

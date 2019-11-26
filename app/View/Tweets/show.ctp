<div class="show-container content-wrapper">
  <?php if (isset($userDetail)) :?>
    <div class="show-user-contents">
      <div class="show-user-header">
        <div class="show-user-header-left">
          <img src=<?php echo $userDetail->profile_image_url; ?> class='show-user-image'></img>
        </div>
        <div class="show-user-header-right">
          <h1 class="show-user-name"><?php echo $userDetail->name; ?></h1>
          <p class="show-user-screen-name"><?php echo "@".$userDetail->screen_name; ?></p>
          <div class="show-user-ff-count">
            <p class="show-user-follow"><?php echo $userDetail->friends_count; ?><span class="show-user-ff-count-span">フォロー中</span></p>
            <p class="show-user-follow"><?php echo $userDetail->followers_count; ?><span class="show-user-ff-count-span">フォロワー</span></p>
          </div>
        </div>
      </div>
      <div class="show-user-body">
        <?php if(isset($userDetail->description)):?>
          <p class="show-user-description"><?php echo $userDetail->description;?></p>
        <? endif ?>
        <?php if(isset($userDetail->url)) :?>
          <p class="show-user-url">url: <a href=<?php echo $userDetail->url;?>><?php echo $userDetail->url; ?></a></p>
        <?php endif; ?>
      </div>
      <div class="show-user-footer">
        <button class="btn show-user-img-btn btn-reverse">このアカウントの画像を取得する！</button>
      </div>
    </div>
  <?php else :?>
    <p class="result-error-message">ユーザーが取得できませんでした</p>
  <?php endif; ?>
</div> 
<button type="button" onclick="history.back()" class="btn back-btn">戻る</button>
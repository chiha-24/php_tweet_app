<!-- ページタイトル -->
<?php
  $this->assign('title', '画像ツイート');
?>
<div class="tweet-image-container content-wrapper">
  <div class="tweet-image-header">
    <h1 class="tweet-image-title">@<?php echo $userName ?>さんの画像ツイート</h1>
  </div>
  <div class="tweet-image-body">
    <!-- データがあった時は表示 -->
    <?php if(count($viewDataArray) != 0) : ?>
      <?php foreach($viewDataArray as $tweetData):?>
        <div class="tweet-data-container">
          <div class="tweet-data-header">
            <div class="tweet-data-header-left">
              <img class="tweet-data-profile-image" src=<?php echo $tweetData['profile_image']; ?>>
            </div>
            <div class="tweet-data-header-right">
              <h3 class="tweet-data-name"><?php echo $tweetData['name']; ?></h3> 
              <p class="tweet-data-screen-name">@<?php echo $tweetData['sc_name']; ?></p>
            </div>
          </div>
          <div class="tweet-data-body">
            <p class="tweet-data-text"><?php echo $tweetData['text']?></p>
            <div class="tweet-data-images-container">
              <?php foreach($tweetData['image_urls'] as $imageUrl) : ?>
                <!-- webroot/img/uploadsに保存した画像を表示 -->
                <?php echo $this->Html->image($imageUrl, array('class' => 'tweet-data-image')); ?>
              <?php endforeach; ?>
            </div>
            <p class="tweet-data-date"><?php echo $tweetData['date']?></p>
          </div>
        </div>
      <?php endforeach;?>
    <?php else : ?>
      <!-- 画像ツイートが取得できなかった時・画像ツイートがなかった時のメッセージ -->
      <p class="result-error-message">画像ツイートが取得できませんでした</p>
    <?php endif; ?>
  </div>
</div>
<div class="tweet-image-container content-wrapper">
  <div class="tweet-image-header">
    <h1 class="tweet-image-title">@<?php echo $userName ?>さんの画像ツイート</h1>
  </div>
  <div class="tweet-image-body">
    <?php if(isset($viewDataArray)) : ?>
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
                <?php echo $this->Html->image($imageUrl, array('class' => 'tweet-data-image')); ?>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      <?php endforeach;?>
    <?php else : ?>
      <p class="result-error-message">画像ツイートが取得できませんでした</p>
    <?php endif; ?>
  </div>
</div>
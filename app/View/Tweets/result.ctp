<?php if(isset($result)): ?>

  <!-- 検索ユーザーの件数を表示 -->
  <p>ユーザーが<?php echo $counted; ?>件見つかりました。</p>
  <p>詳細を開くユーザーをクリックしてください。</p>

  <!-- ユーザーを繰り返し処理で表示 -->
  <?php foreach ($result as $user): ?>
    <div class='result-user-wrapper'>
      <?php echo "<a href='/tweets/show?screen_name=$user->screen_name' class='result-user-link'>
                    <img src=$user->profile_image_url class='result-user-image'></img>
                    <h2 class='result-user-name'> $user->name </h2>
                    <p class='result-user-screen-name'> @$user->screen_name </p>
                    <p class='result-user-description'> $user->description</p> 
                  </a>";
      ?>
    </div>
  <?php endforeach ?>
<!-- 見つからなかった場合-->
<?php else : ?>
  <p>ユーザーが見つかりませんでした</p>
<?php endif ?>
<?= $this->Form->button('戻る', ['onclick' => 'history.back()', 'type' => 'button']) ?>

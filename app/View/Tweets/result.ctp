<div class="result-container content-wrapper">
  <div class="result-contents">
    <?php if(isset($result)): ?>
      <div class="result-contents-header">
        <h1>ユーザー選択</h1>
        <!-- ヒット数を出力 -->
        <?php if(isset($counted)): ?>
          <p>ユーザーが <?php echo $counted; ?>件見つかりました。</br>詳細を開くユーザーをクリックしてください。</p>
        <?php endif; ?>
      </div>
      <!-- ユーザーを繰り返し処理で表示 -->
      <div class='result-users-container'>
        <?php foreach ($result as $user): ?>
            <!-- /tweets/showの末尾にscreen_name=〇〇を付けてリクエストする（/tweets/showでのユーザー取得に使う） -->
            <a href='/tweets/show?screen_name=<?php echo $user->screen_name; ?>' class='result-user-link'>
              <div class='result-user-wrapper'>
                <div class='result-user-contents'>
                  <div class='result-user-left'>
                    <img src=<?php echo $user->profile_image_url; ?> class='result-user-image'></img>
                  </div>
                  <div class='result-user-right'>
                    <h2 class='result-user-name'><?php echo $user->name; ?></h2>
                    <p class='result-user-screen-name'> @<?php echo $user->screen_name; ?></p>
                  </div>
                </div>
              </div>
            </a>
        <?php endforeach; ?>
      </div>

    <!-- 見つからなかった場合-->
    <?php else : ?>
      <p class="result-error-message">ユーザーが見つかりませんでした。</p>
    <?php endif; ?>
  </div>
</div>
<button type="button" onclick="history.back()" class="btn back-btn">戻る</button>

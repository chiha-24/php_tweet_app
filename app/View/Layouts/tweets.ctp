<!DOCTYPE html>
<html lang="ja">
<head>
  <?php echo $this->Html->charset(); ?>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, maximum-scale=1.0, minimum-scale=0.5,user-scalable=yes,initial-scale=1.0" />
  <title>Twimg</title>
  <?php
  echo $this->Html->css('reset.css');
  echo $this->Html->css('tweets.css');
  echo $this->fetch('css');
  ?>
</head>
<body>
  <header>
    <div class='header-container'>
      <a href='/' class='header-title'>Twimg</a>
    </div>
  </header>
  <div class='main-container'>
  <p class="app-discription">TwimgはTwitterユーザー検索・画像ツイート取得アプリケーションです。</br>①キーワード検索 ②ユーザー選択 ③ユーザー詳細 ④画像ツイート取得 の４ステップで画像を取得します。</p>
    <?= $this->fetch('content') ?>
  </div>
  <footer>
    <p class='footer-title'>Twimg</p>
  </footer>
</body>
</html>
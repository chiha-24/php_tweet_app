<div class="index-container content-wrapper">
  <div class="index-form">
    <h1>キーワード検索</h1>
    <p>キーワードにヒットしたユーザーを最大２０件表示します。</br>TwitterID（@~）だと確実です。マイページのURLでも可。</p>
    <!-- postでキーワードをtweets/resultに送信 -->
    <form url="/tweets/result" action="tweets/result" method="post">
      <input type="text" name="keyWord" class="index-keyword-input" placeholder="キーワードを入力">
      <input type="submit" class="index-submit-btn btn" value="検索">
    </form>
</div>
</div>
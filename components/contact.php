<?php
session_start();
?>

<?php include 'layout/header.php'; ?>

<section class="contact">
  <div class="contactContainer">
    <h1>お問合せ</h1>
    <p>※は必須項目です。</p>
  </div>

  <div class="formContainer">
    <form action="sendmail.php" method="POST">

      <div class="eachForm">
        <label for="company">御社名 <span class="required">※</span></label>
        <input type="text" name="company" id="company" required>
      </div>

      <div class="eachForm">
        <label for="fullname">氏名 <span class="required">※</span></label>
        <input type="text" name="fullname" id="fullname" required>
      </div>

      <div class="eachForm">
        <label for="fullname_kana">氏名（カタカナ） <span class="required">※</span></label>
        <input type="text" name="fullname_kana" id="fullname_kana" required>
      </div>

      <div class="eachForm">
        <label for="email">Eメールアドレス <span class="required">※</span></label>
        <input type="email" name="email" id="email" required>
      </div>

      <div class="eachForm">
        <label for="job">職種 <span class="required">※</span></label>
        <input type="text" name="job" id="job" required>
      </div>

      <div class="eachForm">
        <label for="role">役職 <span class="required">※</span></label>
        <div class="selectWrapper">
          <select name="role" id="role" required>
            <option value="代表">代表</option>
            <option value="担当者">部長・課長</option>
            <option value="主任">リーダー</option>
            <option value="その他">その他</option>
          </select>
        </div>
      </div>

      <div class="eachForm">
        <label for="inquiry">相談されたい内容</label>
        <div class="selectWrapper">
          <select name="inquiry" id="inquiry">
            <option value="広告費">広告費</option>
            <option value="広告費">代理店の変更を検討</option>
            <option value="広告費">向いている広告</option>
            <option value="その他">その他</option>
          </select>
        </div>
      </div>

      <div class="eachForm">
        <button name="submitContact" type="submit">送信</button>
      </div>

    </form>
  </div>
</section>

<script>
  const msgText = "<?php echo $_SESSION['status'] ?? ''; ?>";
  if (msgText != '') {
    alert(msgText);
    <?php unset($_SESSION['status']); ?>
  }
</script>
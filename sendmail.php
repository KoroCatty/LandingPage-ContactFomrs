<?php
session_start();

// Send Email both to ADMIN and USER (auto-reply)

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if (isset($_POST['submitContact'])) {
  // Collect form data
  $company = $_POST['company'];
  $fullname = $_POST['fullname'];
  $fullname_kana = $_POST['fullname_kana'];
  $email = $_POST['email'];
  $job = $_POST['job'];
  $role = $_POST['role'];
  $inquiry = $_POST['inquiry'];

  // Create PHPMailer instance
  $mail = new PHPMailer(true);

  try {
    // Server settings
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                       // Set the SMTP server
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'yourEmailAddress';                  // SMTP username
    $mail->Password   = 'secret';                               // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption
    $mail->Port       = 587;                                    // TCP port to connect to

    // エンコーディングをUTF-8に設定 (メール本文の文字化け対策)
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';  // 日本語のエンコーディングは通常base64を使用

    // Recipients
    $mail->setFrom('yourEmailAddress', 'ACMEE');
    $mail->addAddress('yourEmailAddress');                   // Add your email as recipient

    // Content
    $mail->isHTML(true);                                        // Set email format to HTML
    $mail->Subject = 'お問い合わせ';
    $mail->Body    = '<h2>新しいお問合せがありました</h2>
      <p><strong>御社名:</strong> ' . $company . '</p>
      <p><strong>氏名:</strong> ' . $fullname . '</p>
      <p><strong>氏名（カタカナ）:</strong> ' . $fullname_kana . '</p>
      <p><strong>e-mailアドレス:</strong> ' . $email . '</p>
      <p><strong>職種:</strong> ' . $job . '</p>
      <p><strong>役職:</strong> ' . $role . '</p>
      <p><strong>相談されたい内容:</strong> ' . $inquiry . '</p>';

    // Send email to admin
    if ($mail->send()) {

      // Clear previous recipient and add the user for auto-reply
      $mail->clearAddresses();  // Clear all recipients for the next message
      $mail->addAddress($email); // Add user's email

      // Create auto-reply content
      $mail->Subject = mb_encode_mimeheader("【{$fullname} 様】ACMEEへのお問い合わせありがとうございます", 'UTF-8');
      $mail->Body    = <<<EOT
      {$fullname} 様

      この度はACMEEへお問い合わせを頂きまして、誠にありがとうございました。

      内容を確認の上、改めて担当者からご連絡させていただきます。

      ※2営業日経っても連絡がない場合は、メールアドレスの記入もれやシステム障害の可能性があるため、改めてご連絡いただけますと幸いです。
      EOT;

      // Send auto-reply to user
      $mail->send();

      $_SESSION['status'] = "お問い合わせありがとうございます。後ほどご連絡させていただきます。"; // Success message
      header('Location: ' . $_SERVER['HTTP_REFERER']); // Redirect to previous page
      exit();
    } else {
      $_SESSION['status'] = "メールを送信できませんでした😅Mailer Error: {$mail->ErrorInfo}"; // Error message
      header('Location: ' . $_SERVER['HTTP_REFERER']); // Redirect on error
      exit();
    }
  } catch (Exception $e) {
    $_SESSION['status'] = "メールを送信できませんでした😅。Mailer Error: {$mail->ErrorInfo}";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
  }
} else {
  header('Location: index.php'); // Redirect if form not submitted
  exit();
}


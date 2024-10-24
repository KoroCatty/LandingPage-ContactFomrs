<?php
session_start();

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
    $mail->Username   = 'myaddress@gmail.com';                  // SMTP username
    $mail->Password   = 'secret';                               // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption
    $mail->Port       = 587;                                    // TCP port to connect to

     // エンコーディングをUTF-8に設定
     $mail->CharSet = 'UTF-8';
     $mail->Encoding = 'base64';  // 日本語のエンコーディングは通常base64を使用
 

    // Recipients
    $mail->setFrom('myaddress@gmail.com', 'ACMEE');
    $mail->addAddress('myaddress@gmail.com');                   // Add your email as recipient

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

    // Send email
    if ($mail->send()) {
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

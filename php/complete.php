<?php

session_start();

require 'libs/functions.php';

require 'libs/variables.php';


date_default_timezone_set('Asia/Tokyo');

$_POST = checkInput($_POST);

//固定トークンを確認（CSRF対策）
if (isset($_POST['ticket'], $_SESSION['ticket'])) {
    $ticket = $_POST['ticket'];
    if ($ticket !== $_SESSION['ticket']) {
        die('Access denied');
    }
} else {

    $dirname = dirname($_SERVER['SCRIPT_NAME']);
    $dirname = $dirname == DIRECTORY_SEPARATOR ? '' : $dirname;

    if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) and $_SERVER['HTTP_X_FORWARDED_PROTO'] === "https") {
        $_SERVER['HTTPS'] = 'on';
    }
    $url = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['SERVER_NAME'] . '/portfolio';  // デプロイ時に変更
    header('HTTP/1.1 303 See Other');
    header('location: ' . $url);
    exit;
}

$name = h($_SESSION['name']);
$email = h($_SESSION['email']);
$message = h($_SESSION['message']);
$subject = "問い合わせ";


$mail_body = 'コンタクトページからのお問い合わせ' . "\n\n";
$mail_body .=  date("Y年m月d日 H時i分") . "\n\n";
$mail_body .=  "お名前： " . $name . "\n";
$mail_body .=  "Email： " . $email . "\n";
$mail_body .=  "＜お問い合わせ内容＞" . "\n" . $message;

// 本番用
$mailTo = mb_encode_mimeheader(MAIL_TO_NAME) . "<" . MAIL_TO . ">";
// MailHog用
// $mailTo = MAIL_TO_NAME . "<" . MAIL_TO . ">";

//Return-Pathに指定するメールアドレス
$returnMail = MAIL_RETURN_PATH; //

//mbstringの日本語設定
mb_language('ja');
mb_internal_encoding('UTF-8');

// 送信者情報（From ヘッダー）の設定 本番用
$header = "From: " . mb_encode_mimeheader($name) . "<" . $email . ">\n";
$header .= "Cc: " . mb_encode_mimeheader(MAIL_CC_NAME) . "<" . MAIL_CC . ">\n";
$header .= "Bcc: <" . MAIL_BCC . ">";

// MailHog用設定
// $header = ['From'=>$name . '<' . $email . '>', 'Cc'=>MAIL_CC_NAME . '<' . MAIL_CC . '>', 'Bcc'=>'<' . MAIL_BCC . '>', 'Content-Type'=>'text/plain; charset=UTF-8', 'Content-Transfer-Encoding'=>'8bit'];

//メールの送信（結果を変数 $result に格納）
if (ini_get('safe_mode')) {
    //セーフモードがOnの場合は第5引数が使えない
    $result = mb_send_mail($mailTo, $subject, $mail_body, $header);
} else {
    $result = mb_send_mail($mailTo, $subject, $mail_body, $header, '-f' . $returnMail);
}

//メール送信の結果判定
if ($result) {

    $_SESSION = array();
    session_destroy();
} else {
    //送信失敗時（もしあれば）
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>コンタクトフォーム（完了）</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <h2>お問い合わせフォーム</h2>
        <?php if ($result) : ?>
            <h3>送信完了!</h3>
            <p>お問い合わせいただきありがとうございます。</p>
            <p>送信完了いたしました。</p>
        <?php else : ?>
            <p>申し訳ございませんが、送信に失敗しました。</p>
            <p>しばらくしてもう一度お試しになるか、メールにてご連絡ください。</p>
            <p>ご迷惑をおかけして誠に申し訳ございません。</p>
        <?php endif; ?>
        <a href="../" class="btn btn-info">サイトへ戻る</a>
    </div>
</body>

</html>

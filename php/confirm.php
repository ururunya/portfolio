<?php

session_start();

require 'libs/functions.php';

$_POST = checkInput($_POST);
//固定トークンを確認（CSRF対策）
if (isset($_POST['ticket'], $_SESSION['ticket'])) {
    $ticket = $_POST['ticket'];
    if ($ticket !== $_SESSION['ticket']) {
        die('Access Denied!');
    }
} else {

    die('Access Denied（直接このページにはアクセスできません）');
}

$name = trim(filter_input(INPUT_POST, 'name'));
$email = trim(filter_input(INPUT_POST, 'email'));
$email_check = trim(filter_input(INPUT_POST, 'email_check'));
$tel = trim(filter_input(INPUT_POST, 'tel'));
$subject = trim(filter_input(INPUT_POST, 'subject'));
$message = trim(filter_input(INPUT_POST, 'message'));

$error = array();

if ($name == '') {
    $error['name'] = '*お名前は必須項目です。';
    //制御文字でないことと文字数をチェック
} else if (preg_match('/\A[[:^cntrl:]]{1,30}\z/u', $name) == 0) {
    $error['name'] = '*お名前は30文字以内でお願いします。';
}
if ($email == '') {
    $error['email'] = '*メールアドレスは必須です。';
} else {
    $pattern = '/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/uiD';
    if (!preg_match($pattern, $email)) {
        $error['email'] = '*メールアドレスの形式が正しくありません。';
    }
}
if ($message == '') {
    $error['message'] = '*内容は必須項目です。';
    //制御文字（タブ、復帰、改行を除く）でないことと文字数をチェック
} else if (preg_match('/\A[\r\n\t[:^cntrl:]]{1,1050}\z/u', $message) == 0) {
    $error['message'] = '*内容は1000文字以内でお願いします。';
}

$_SESSION['name'] = $name;
$_SESSION['email'] = $email;
$_SESSION['message'] = $message;
$_SESSION['error'] = $error;

if (count($error) > 0) {

    //サーバー変数 $_SERVER['HTTPS'] が取得出来ない環境用（オプション）
    if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) and $_SERVER['HTTP_X_FORWARDED_PROTO'] === "https") {
        $_SERVER['HTTPS'] = 'on';
    }
    //入力画面（index.php）の URL
    $url = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['SERVER_NAME'] . '/portfolio';  // デプロイ時に変更
    header('HTTP/1.1 303 See Other');
    header('location: ' . $url);
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>コンタクトフォーム（確認）</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
</head>

<body>
    <div class="container">
        <h2>お問い合わせ確認画面</h2>
        <p>以下の内容でよろしければ「送信する」をクリックしてください。<br>
            内容を変更する場合は「戻る」をクリックして入力画面にお戻りください。</p>
        <div class="table-responsive confirm_table">
            <table class="table table-bordered">
                <caption>ご入力内容</caption>
                <tr>
                    <th>お名前</th>
                    <td><?php echo h($name); ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo h($email); ?></td>
                </tr>
                <tr>
                    <th>お問い合わせ内容</th>
                    <td><?php echo nl2br(h($message)); ?></td>
                </tr>
            </table>
        </div>
        <form action="../#contact" method="post" class="confirm">
            <button type="submit" class="btn btn-secondary">戻る</button>
        </form>
        <form action="complete.php" method="post" class="confirm">
            <!-- 完了ページへ渡すトークンの隠しフィールド -->
            <input type="hidden" name="ticket" value="<?php echo h($ticket); ?>">
            <button type="submit" class="btn btn-success" disabled>送信する</button>
        </form>
    </div>

</body>

</html>

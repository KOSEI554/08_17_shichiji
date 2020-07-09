<?php
session_start();
include("funcs.php");

// DB接続
$pdo = db_connect();

// データ取得SQL作成
$sql = 'SELECT * FROM user_table';

// SQL準備&実行
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

// データ登録処理後
if ($status == false) {
  // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
  $error = $stmt->errorInfo();
  echo json_encode(["error_msg" => "{$error[2]}"]);
  exit();
} else {
  // 正常にSQLが実行された場合は入力ページファイルに移動し，入力ページの処理を実行する
  // fetchAll()関数でSQLで取得したレコードを配列で取得できる
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);  // データの出力用変数（初期値は空文字）を設定
  $output = "";
  // <tr><td>deadline</td><td>todo</td><tr>の形になるようにforeachで順番に$outputへデータを追加
  // `.=`は後ろに文字列を追加する，の意味
  foreach ($result as $record) {
    $output .= "<tr>";
    $output .= "<td>{$record["name"]}</td>";
    $output .= "<td>{$record["usern"]}</td>";
    $output .= "<td>{$record["mail"]}</td>";
    $output .= "<td>{$record["pass"]}</td>";
    $output .= "<td>{$record["old"]}</td>";
    $output .= "<td>{$record["prefecture"]}</td>";
    $output .= "<td>{$record["message"]}</td>";
    // edit deleteリンクを追加
    $output .= "<td><a href='manage_edit.php?id={$record["id"]}'>編集</a></td>";
    $output .= "<td><a href='delete.php?id={$record["id"]}'>削除</a></td>";
    $output .= "</tr>";
  }
  // $valueの参照を解除する．解除しないと，再度foreachした場合に最初からループしない
  // 今回は以降foreachしないので影響なし
  unset($value);
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="">
  <title>ユーザー管理画面</title>
</head>

<body>
  <fieldset>
    <legend>ユーザーリスト</legend>
    <a href="logout.php">管理者ログアウト</a>
    <a href="user_add.php">ユーザー追加画面</a>
    <table>
      <thead>
        <tr>
          <th>名前</th>
          <th>ユーザー名</th>
          <th>メールアドレス</th>
          <th>パスワード</th>
          <th>年齢</th>
          <th>居住地</th>
          <th>ひとこと</th>
        </tr>
      </thead>
      <tbody>
        <!-- ここに<tr><td>deadline</td><td>todo</td><tr>の形でデータが入る -->
        <?= $output ?>
      </tbody>
    </table>
  </fieldset>
</body>

</html>
<?php
session_start();
include("funcs.php");
$pdo = db_connect();
// var_dump($_POST);
// exit();

$mail = $_POST["mail"];
$pass = $_POST["pass"];


// $dbn  = "mysql:dbname=gsf_d06_db17;charset=utf8;port=3306;host=localhost";
// $user = "root";
// $pwd = "";


// POSTされたメールアドレス検索
  try{
      $stmt = $pdo->prepare("SELECT * from management_table where mail = :mail AND pass = :pass");
      $stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
      $stmt->bindValue(':pass', $pass, PDO::PARAM_STR);
      $status = $stmt->execute(); 
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      // $value = $row["mail"];
      // var_dump($row);
      // exit();
      
      
      if ($row["id"] != "") {
        $_SESSION['chk_ssid'] = session_id();
        $_SESSION['id'] = $row['id'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['mail'] = $row['mail'];
        $_SESSION['pass'] = $row['pass'];//消すとこ
        header('Location: management.php');
        exit();
        }else{
        echo "メールアドレスもしくはパスワードが間違っています。";
          }
        
    } catch(PDOException $e){
      echo json_encode(["db error" => "{$e->getMessage()}"]);
      // echo "できてない";
      return false;
    }
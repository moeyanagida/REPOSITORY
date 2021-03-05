<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission3-5.php</title>
</head>
<body>
    
    掲示板<br>

<?php
  //フォームの値の確認
  var_dump($_POST);
  echo "<hr>";
  
  //データベースに接続
$dsn = 'mysql:dbname=tb220320db;host=localhost';
$user = 'tb-220320';
$password = 'rKhdAHtchD';
//new PDO：データベースに接続するための関数
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//日付け
$date = date("Y/m/d H:i:s");

//2.テーブルを作成
//"mission5"というテーブルがすでに存在していなければ(テーブル名を変更)
$sql = "CREATE TABLE IF NOT EXISTS mission5"
." ("  //登録する項目を指定
. "id INT AUTO_INCREMENT PRIMARY KEY,"  // １ずつ増加する整数を自動的に指定
. "name char(32)," //文字列 半角英数で32文字
. "comment TEXT,"
. "date char(32),"//日付けを項目に追加
. "pass1 char(32)"//パスワードを項目に追加
.");";             

$stmt = $pdo -> query($sql);  //query

if(!empty($_POST["str"])){
      //名前
      $name = $_POST["str"];
}

if(!empty($_POST["str2"])){
      //コメント
      $comment = $_POST["str2"];
}

if(!empty($_POST["edit2"])){
      //編集番号
      $edit2 = $_POST["edit2"];
}

if(!empty($_POST["delete"])){
      //削除番号
      $delete = $_POST["delete"];
}

if(!empty($_POST["edit"])){
      //編集番号
      $edit = $_POST["edit"];
}

if(!empty($_POST["edit2"])){
      //編集番号（フォーム）
      $edit2 = $_POST["edit2"];
}

if(!empty($_POST["pass1"])){
      //投稿パスワード
      $pass1 = $_POST["pass1"];
}

if(!empty($_POST["pass2"])){
      //削除パスワード
      $pass2 = $_POST["pass2"];
}

if(!empty($_POST["pass3"])){
      //編集パスワード
      $pass3 = $_POST["pass3"];
}

      
           //新規投稿の条件分岐
      if(!empty($name) && !empty($comment) && empty($edit2) 
      && !empty($pass1)){

      $sql = $pdo -> prepare("INSERT INTO mission5 (name,comment,date,pass1)
      VALUES (:name, :comment, :date,:pass1)");
      
      $sql -> bindParam(':name', $name, PDO::PARAM_STR);
      $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
      $sql -> bindParam(':date', $date, PDO::PARAM_STR);
      $sql -> bindParam(':pass1', $pass1, PDO::PARAM_STR);
      $sql -> execute();
      echo "新規投稿ループ<br>";
      
  }else{
      
      echo "投稿してください<br>";
  }
  
 
  //投稿削除の条件分岐
  if(!empty($delete)){
      
     $sql = 'SELECT * FROM mission5';
     $stmt = $pdo -> query($sql);
     $results = $stmt -> fetchAll(); 
     
      foreach($results as $row){
          
          if($row['pass1'] == $pass2){
      //8.入力したデータレコードを削除
      //削除するidを指定
             $id = $delete;
            
             $sql = 'delete from mission5 where id=:id';
             $stmt = $pdo -> prepare($sql);
             $stmt -> bindParam(':id', $id, PDO::PARAM_INT);

             $stmt -> execute();
            
          }
      }
  }
  
  //フォームへの編集投稿,番号の表示
  if(!empty($edit)){
      
      $sql = 'SELECT * FROM mission5';
      $stmt = $pdo -> query($sql);
      $results = $stmt -> fetchAll();
      
      foreach($results as $row){
          
          if($row['id'] == $edit && $row['pass1'] == $pass3){
              
              $editname = $row['name'];
              $editcomment = $row['comment'];
              $editnum = $edit;
          }
          
      }
  }
  
 
  //編集した投稿の差し替え
  if(!empty($name) && !empty($comment) && !empty($edit2)){
      
      $id = $edit2;
      $sql = 'UPDATE mission5 SET name=:name,comment=:comment WHERE id=:id';
      $stmt = $pdo->prepare($sql);

      $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
      $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
      $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
      
      $stmt -> execute();
  }
  
?>

    1.name____________2.comment_______________
    <!--名前のフォーム-->
    <form action="" method="post">
    <input type="text" name="str" value = "<?php if(!empty($edit)
    && !empty($editname)){
    
      echo $editname;}?>"
      >
    
    <!--コメントのフォーム-->
    <form action="" method="post">
    <input type="text" name="str2" value = "<?php if(!empty($edit)
    && !empty($editcomment)){
    
      echo $editcomment;}?>"
      >
    
    <!--編集番号を取得-->
    <form action="" method="post">
    <input type="hidden" name= edit2 value = "<?php if(!empty($edit)
    && !empty($editnum)){
    
      echo $editnum;}?>"
      >
    
    <!--投稿のパスワード--> 
    <form action="" method="post">
    <input type="password" name="pass1">
    
    <input type="submit" name="submit" value="投稿">
    </form>
    
    <!--投稿削除のフォーム-->
    <form action="" method="post">
    <input type="number" name=delete>
    
    <!--投稿削除のパスワード--> 
    <form action="" method="post">
    <input type="password" name="pass2">
    
    <input type="submit" name="submit2" value="削除">
    </form>
    
    <!--投稿編集のフォーム-->
    <form action="" method="post">
    <input type="number" name="edit">
    
    <!--投稿編集のパスワード--> 
    <form action="" method="post">
    <input type="password" name="pass3">
    
    <input type="submit" name="submit3" value="編集"><br>
    
<?php
//表示処理
//6.入力したデータレコードを抽出し、表示
$sql = 'SELECT * FROM mission5';

$stmt = $pdo -> query($sql);
$results = $stmt -> fetchAll();

//ループ
  foreach ($results as $row){
    
	//$rowの中にはテーブルのカラム名が入る
	  echo $row['id'].',';
	  echo $row['name'].',';
	  echo $row['comment'].',';
	  echo $row['date'];
	
    echo "<hr>";//"<hr>"：水平線を入れる。
  }

 
?>

</body>
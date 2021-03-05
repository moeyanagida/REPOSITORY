<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission3-5.php</title>
</head>
<body>
    
    掲示板<br>
____＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿__________________________<br>
<?php
  //フォームの値の確認
  var_dump($_POST);
  echo "<hr>";
  //名前
  $name = $_POST["str"];
  //コメント
  $comment = $_POST["str2"];
  //削除番号
  $num2 = $_POST["num2"];
  //編集番号
  $num3 = $_POST["num3"];
  //投稿のパスワード
  $pass1 = $_POST["pass1"];
  //投稿削除のパスワード
  $pass2 = $_POST["pass2"];
  //投稿編集のパスワード
  $pass3 = $_POST["pass3"];
  //ファイル名
  $filename = "aaa.txt";
//投稿番号
  $num = count(file($filename)) + 1;
  
//ファイル作成（追記モード）
  $fp = fopen($filename,"a");
  
//日付作成
  $date = date("Y/m/d H:i:s");
  //テキスト
  $text = "<>".$name."<>".$comment."<>".$date."<>".$pass1."<>".PHP_EOL;
  
//投稿処理
  
  //投稿フォームの条件分岐（名前とコメントが空でない場合 + 編集番号が空である場合）
    if(!empty($name) && !empty($comment) && empty($_POST["editnum"]) && !empty($pass1)){
  //投稿フォームの書き込み処理 （投稿をtxtファイルに書き込む）   
      fwrite($fp,$num.$text);
  //ファイルを閉じる
      fclose($fp);
      
  }
 
//削除処理
//削除処理の条件分岐
    if(file_exists($filename) && empty($name) && empty($comment) && !empty($num2)){
        //ファイルを配列にして変数に格納
        $lines = file($filename);
        //初期化して追記モードに戻す
        $fp = fopen($filename,"w");
        fclose($fp);
        $fp = fopen($filename,"a");
      
        //ループ
        foreach($lines as $line){
           
            //投稿を分割
            $explode = explode("<>",$line);
            
            //パスワードの判定
            if($explode[4] == $pass2){
            
                //投稿番号が削除番号と一致していれば
                if($explode[0] != $num2){
                    
                    //元の投稿を書き込む
                    fwrite($fp,$line);
                    
                //一致していなければ
                }elseif($explode[0] == $num2){
                
                    //代わりの文章を代入
                    fwrite($fp,"投稿".$num2."を削除しました".PHP_EOL);
                    
                }
                //パスワードが一致していなければ
            }else{
                
                fwrite($fp,$line);
            }
        }
    
    }
    //ファイルを閉じる
      fclose($fp);
      
//フォームに表示する値の取得

      //ファイルが存在して編集番号が書き込まれていれば
      if(file_exists($filename) && !empty($num3)){
          //ファイルを配列にして変数に格納
          $lines = file($filename);
          //ファイルを追記モードで開く
          $fp = fopen($filename,"a");
          //ループ
          foreach($lines as $line){
              //投稿の分割
              $explode = explode("<>",$line); 
              //投稿番号と編集番号が同じ + パスワードが同じなら
              if($explode[0] == $num3){
                  //投稿番号を取得
                  $editnum = $explode[0];
                  //名前を取得
                  $editname = $explode[1];
                  //コメントを取得
                  $editcomment = $explode[2];
                  
                 
              }
              
          }
        //ファイルを閉じる  
      }fclose($fp);
      
//投稿内容の差し替え
      
      //ファイルが存在して、取得した編集番号が空でなければ
      if(file_exists($filename) && !empty($name) && !empty($comment) 
      && !empty($_POST["editnum"])){
          //ファイルを配列に格納し、変数に代入
          $lines = file($filename);
          $fp = fopen($filename,"w");
          fclose($fp);
          $fp = fopen($filename,"a");
          //ループ
          foreach($lines as $line){
              //投稿を分割
              $explode = explode("<>",$line);
              
              //パスワードの判定
              if($explode[4] == $pass1){
                 
              //投稿番号が編集番号を一致していれば
                if($explode[0] == $_POST["editnum"]){
                  //テキストファイルに編集した値を書き込む
                    fwrite($fp,$_POST["editnum"]."<>".$_POST["str"]."<>".$_POST["str2"]."<>".$date.PHP_EOL);
                    
              //違うなら
                }elseif($explode[0] != $_POST["editnum"]){
               
                    //元の投稿を書き込む
                    fwrite($fp,$line);
                    
                }
              }else{
                  
                  fwrite($fp,$line);
              }
              //ファイルを閉じる
          }fclose($fp);
          
//新規投稿かどうかの判定

        //ファイルを追記モードで開く
        $fp = fopen($filename,"a");
         //ファイルが存在し、編集番号を取得していなければ
      }elseif(file_exists($filename) && !empty($name) && !empty($comment) 
      && empty($_POST["editnum"])){
          //投稿を新規投稿する
          fwrite($fp,$num.$text);
         
      }
      //ファイルを閉じる
      fclose($fp);
      
//表示処理
    
      //ファイルを読み取りモードで開く
      $fp = fopen($filename,"r");
      
          //ファイルを配列にして格納
        $lines = file($filename);
          //ループ処理
        foreach($lines as $line){
            $explode = explode("<>",$line);
              //ブラウザに表示
            echo $explode[0]."<>".$explode[1]."<>".$explode[2]."<>".$explode[3]."<br>";
        }
        //ファイルを閉じる
      fclose($fp);
      
      
     
?>
___________________________________________________________________<br>

    1.name____________2.comment_______________
    <!--名前のフォーム-->
    <form action="" method="post">
    <input type="text" name="str" value = "<?php if(!empty($num3)){
    
      echo $editname;}?>">
    
    <!--コメントのフォーム-->
    <form action="" method="post">
    <input type="text" name="str2" value= "<?php if(!empty($num3)){
        
      echo $editcomment;}?>">
      
    
    <form>
    <!--編集番号を表示する-->
    <form action="" method="post">
    <input type="hidden" name="editnum" value= "<?php if(!empty($num3)){
      
      echo $editnum;} ?>">
    <!--投稿のパスワード-->  
    <input type="password" name="pass1">
    <input type="submit" name="submit" value="投稿">
    </form>
    <!--削除フォーム-->
    <form action="" method="post">
    <input type="number" name="num2" placeholder="削除番号を入力してください" value=" ">
    <!--投稿削除のパスワード-->
    <input type="password" name="pass2">
    <input type="submit" name="submit2" value="削除">
    </form>
    
    <!--編集フォーム-->
    <form action="" method="post">
    <input type="number" name="num3" placeholder="編集番号を入力してください" value=" ">
    <!--投稿編集のパスワード-->
    <input type="password" name="pass3">
    <input type="submit" name="submit3" value="編集">
    </form>
    
</body>

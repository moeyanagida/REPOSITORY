<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8"> 
    <title>mission1-27</title>
</head>
<body>
    <form action=""method="post">
    <input type = "num" name = "num">
    <input type = "submit" name = "submit">
    </form>
<?php    
    $number = $_POST["num"];
    $file="mission_1-27.txt";
    
    $fp = fopen($file,"a");
    //ファイルで改行を行う処理をする。
    $num = $number.PHP_EOL;
    //改行処理を行った数字をファイルに記入
    fwrite($fp,$num);
    //閉じる
    fclose($fp);
    
    echo "書き込み完了！<br>";
    
    if(file_exists($file)){
        
    $lines = file($file,FILE_IGNORE_NEW_LINES);
    
    foreach($lines as $line){
        
        if($line % 3 == 0 && $line % 5 == 0){
            echo "FizzBuzz<br>";
            
        }elseif($line % 3 == 0){
            echo "Fizz<br>";
            
        }elseif($line % 5 == 0){
            echo "Buzz<br>";
            
        }else{
            echo $line."<br>";
        }
    }
    
}
    
?>
 </body>
<?php
	if (!empty($_POST["edit"])){
		$edit = $_POST["edit"];
	}
	if (!empty($_POST["pass3"])){
		$pass3 = $_POST["pass3"];
	}
?>
<html>
<head>
	<meta charset="UTF-8">
</head>
<body>
<form method="post" action="<?php $_SERVER['PHP_SELF'] ?>" target="_self">
	<p>　　名前:
	<input type="text" name="name"  ><br></p>
	<p>コメント:
	<input type="text" name="comment"  ></p>
	<p>パスワード:
	<input type="text" name="pass1"></p>
	<input type="hidden" name="edit2" value="<?php echo $edit; ?>">
	<input type="hidden" name="pass3_2" value="<?php echo $pass3; ?>">
	<input type="submit" value ="送信"><br><br>
	
	<p>削除対象番号:
	<input type="text" name="number"  ></p>
	<p>パスワード:
	<input type="text" name="pass2"  ></p>
	<input type="submit" value ="削除"><br>
	
</form>
<form method="post" action="<?php $_SERVER['PHP_SELF'] ?>" target="_self">
	<p>編集対象番号:
	<input type="text" name="edit"  ></p>
	<p>パスワード:
	<input type="text" name="pass3"  ></p>
	<input type="submit" value ="編集"><br>
</form>
<?php
	$dsn = 'mysql:dbname=ユーザ名;host=localhost';
	$user = 'ユーザ名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));//データベースアクセス
	$sql = "CREATE TABLE IF NOT EXISTS table_ms"//4-2テーブル作成
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "date TEXT,"
	. "pass char(32)"
	.");";
	$stmt = $pdo->query($sql);
	
	date_default_timezone_set('Asia/Tokyo');
	header('Content-Type:text/html; charset=UTF-8');
	
	if (!empty($_POST["comment"])){//新規書き込み
		$comment = $_POST["comment"];
		$name = $_POST["name"];
		$pass1 = $_POST["pass1"];//入力読み込み
		
		if(isset($_POST["edit2"])){
			$edit2 = $_POST["edit2"];
		}
		if(isset($_POST["pass3_2"])){
			$pass3_2 = $_POST["pass3_2"];
		}
		if($comment == "passcheck"){
			$sql = 'SELECT * FROM table_ms';//4-6
			$stmt = $pdo->query($sql);
			$results = $stmt->fetchAll();
			foreach ($results as $row){
				//$rowの中にはテーブルのカラム名が入る
				echo $row['id'].'<>';
				echo $row['name'].'<>';
				echo $row['comment'].'<>';
				echo $row['date'],'<>';
				echo $row['pass'],'<br>';
				echo "<hr>";
			}
		}
		if($comment == "" || $name ==""){//無記入処理
		//	print("noting\n");
		}else{//正しく入力されているとき
			if($edit2 > 0){//編集する
				if($pass3_2 = ""){
					print("please input pass<br />");
				}else{
				$pass3_2 = $_POST["pass3_2"];
				$id = $_POST["edit2"];
				$sql = 'SELECT * FROM table_ms';
				$stmt = $pdo->query($sql);
				$results = $stmt->fetchAll();
				foreach ($results as $row){
					if($row['id']==$id && $row['pass']==$pass3_2){
						print("edit success"."<br>");
				 		//変更する投稿番号//4-7//変更したい名前、変更したいコメントは自分で決めること
						$sql = 'update table_ms set name=:name,comment=:comment,date=:date,pass=:pass where id=:id';
						$stmt = $pdo->prepare($sql);
						$stmt->bindParam(':name', $name, PDO::PARAM_STR);
						$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
						$stmt->bindParam(':date',$date, PDO::PARAM_STR);
						$stmt->bindParam(':pass',$pass3_2, PDO::PARAM_STR);
						$stmt->bindParam(':id', $id, PDO::PARAM_INT);
						$date = date("Y/m/d H:i:s");
						$stmt->execute();
					}
				}
				$sql = 'SELECT * FROM table_ms';//4-6
				$stmt = $pdo->query($sql);
				$results = $stmt->fetchAll();
				foreach ($results as $row){
					//$rowの中にはテーブルのカラム名が入る
					echo $row['id'].'<>';
					echo $row['name'].'<>';
					echo $row['comment'].'<>';
					echo $row['date'],'<br>';
					echo "<hr>";
				}
				}
			}else{//編集なし新規書き込み
				$sql = $pdo -> prepare("INSERT INTO table_ms(name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
				$sql->bindParam(':name',$name, PDO::PARAM_STR);
				$sql->bindParam(':comment',$comment, PDO::PARAM_STR);
				$sql->bindParam(':date',$date, PDO::PARAM_STR);
				$sql->bindParam(':pass',$pass1, PDO::PARAM_STR);
				$date = date("Y/m/d H:i:s");
				$sql->execute();
				$sql = 'SELECT * FROM table_ms';
				$stmt = $pdo->query($sql);
				$results = $stmt->fetchAll();
				foreach ($results as $row){
					echo $row['id'].'<>';
					echo $row['name'].'<>';
					echo $row['comment'].'<>';
					echo $row['date'],'<br>';
					echo "<hr>";
				}
				
			}
		}
	}
	if (!empty($_POST["number"])){//delete
		$line = array();
		$pass2 = $_POST["pass2"];
		$id = $_POST["number"];
		
		$sql = 'SELECT * FROM table_ms';
		$stmt = $pdo->query($sql);
		$results = $stmt->fetchAll();
		foreach ($results as $row){
			if($row['id']==$id && $row['pass']==$pass2){
				print("delete success"."<br>");
				$sql = 'delete from table_ms where id=:id';
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(':id', $id, PDO::PARAM_INT);
				$stmt->execute();
			}
		}
		$sql = 'SELECT * FROM table_ms';
		$stmt = $pdo->query($sql);
		$results = $stmt->fetchAll();
		foreach ($results as $row){
			echo $row['id'].'<>';
			echo $row['name'].'<>';
			echo $row['comment'].'<>';
			echo $row['date'],'<br>';
			echo "<hr>";
		}
	

	}	
	
	
	if(empty($_POST["comment"]) && empty($_POST["number"])){
		$sql = 'SELECT * FROM table_ms';//4-6
		$stmt = $pdo->query($sql);
		$results = $stmt->fetchAll();
		foreach ($results as $row){
			echo $row['id'].'<>';
			echo $row['name'].'<>';
			echo $row['comment'].'<>';
			echo $row['date'],'<br>';
			echo "<hr>";
		}
	}
?>
</body>
</html>
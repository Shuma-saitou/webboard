<body>
<?php
    $delete=0;
    $edit=0;
    $editname="";
    $editcomment="";
    $editnumber="";
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード名';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    $sql = "CREATE TABLE IF NOT EXISTS webboard"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "dat TEXT,"
    . "pass TEXT"
    .");";
    $stmt = $pdo->query($sql);
    if(!empty($_POST["str"]) and empty($_POST["index"])){
        if($_POST["passsub"] === ""){
        }else{
            $sql = $pdo -> prepare("INSERT INTO webboard (name, comment, dat, pass) VALUES (:name, :comment, :dat, :pass)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':dat', $dat, PDO::PARAM_STR);
            $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
            $name = $_POST["name"];
            $comment = $_POST["str"];
            $dat = date("Y/m/d H:i:s");
            $pass = $_POST["passsub"];
            $sql -> execute();
        }
    }
    if(!empty($_POST["delete"])){
        $id = $_POST["delete"];
        $passdel = $_POST["passdel"];
        $sql='SELECT * FROM webboard WHERE id=:id';
	    $stmt=$pdo->prepare($sql);
	    $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll();
        foreach ($result as $row){
            if($row['pass']==$passdel){
                $sql = 'DELETE FROM webboard WHERE id=:id';    
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $sql = 'UPDATE webboard SET id=id-1 WHERE id>:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $sql = 'ALTER TABLE webboard AUTO_INCREMENT = 1';
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
            }
        }
    }
    if(!empty($_POST["edit"])){
        $edit = $_POST["edit"];
        $passedit = $_POST["passedit"];
        $sql = 'SELECT * FROM webboard WHERE id=:edit and pass = :passedit';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':edit', $edit, PDO::PARAM_INT);
        $stmt->bindParam(':passedit', $passedit, PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            $editnumber = $row['id'];
            $editname = $row['name'];
            $editcomment = $row['comment'];
        }
    }
    if(!empty($_POST["index"])){
        $id = $_POST["index"];
        $name = $_POST["name"];
        $comment = $_POST["str"]; 
        $passsub = $_POST["passsub"];
        $sql = 'UPDATE webboard SET name=:name,comment=:comment,pass=:passsub WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':passsub', $passsub, PDO::PARAM_STR);
        $stmt->execute();
    }
?>
<form action="" method="post">
        <input type="hidden" name="index"value="<?php echo $editnumber ?>">
        <input type="text" name="name" placeholder="名前入力欄" value="<?php echo $editname?>" >
        <input type="text" name="str" placeholder="コメント" value="<?php echo $editcomment?>">
        <input type="text" name="passsub" placeholder="パスワード">
        <input type="submit" name="submit" />
        <br>
        <input type="text" name="delete" placeholder="削除番号">
        <input type="text" name="passdel" placeholder="パスワード">
        <input type="submit" value="削除" />
        <br>
        <input type="text" name="edit" placeholder="編集番号">
        <input type="text" name="passedit" placeholder="パスワード">
        <input type="submit" value="編集" />
</form>
<?php
    $sql = 'SELECT * FROM webboard';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['dat'];
        echo '<br>';
        echo "<hr>";
    }
?>
</body>
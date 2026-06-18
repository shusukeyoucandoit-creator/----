<?php

$dsn='mysql:dbname=tb280093db;host=localhost';
$user='tb-280093';
$password='dRE8EpXXby';

$pdo=
new PDO(
$dsn,
$user,
$password,
array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING)
);
$sql=
"CREATE TABLE IF NOT EXISTS drink
(
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(100)
)";

$pdo->query($sql);
$sql=
"CREATE TABLE IF NOT EXISTS drink_post
(
id INT AUTO_INCREMENT PRIMARY KEY,
drink VARCHAR(100),
comment TEXT,
dt DATETIME DEFAULT CURRENT_TIMESTAMP
)";

$pdo->query($sql);
if(
!empty($_POST["new_drink"])
){

$sql=
$pdo->prepare(
"INSERT INTO drink(name)
VALUES(:name)"
);

$sql->bindValue(
":name",
$_POST["new_drink"]
);

$sql->execute();

}




/* 投稿保存 */

if(
!empty($_POST["drink"])
&&
!empty($_POST["comment"])
){

$sql=
$pdo->prepare(
"
INSERT INTO
drink_post
(drink,comment)

VALUES
(:drink,:comment)
"
);

$sql->bindValue(
":drink",
$_POST["drink"]
);

$sql->bindValue(
":comment",
$_POST["comment"]
);

$sql->execute();

}
if(!empty($_POST["delete"])){
    $sql=$pdo->prepare("DELETE FROM drink_post WHERE id=:id");
    $sql->bindValue(":id",$_POST["delete"]);
    $sql->execute();
}


?>




<!-- ドリンク追加 -->

<h2>新しいドリンク追加</h2>

<form method="post">

<input
type="text"
name="new_drink">

<input
type="submit"
value="追加">

</form>

<hr>



<!-- 投稿 -->

<h2>飲んだドリンク投稿</h2>

<form method="post">

<select name="drink">

<?php

$sql=
"SELECT *
FROM drink";

$stmt=
$pdo->query($sql);

foreach(
$stmt
as
$row
){

echo
"<option>";

echo
$row["name"];

echo
"</option>";

}

?>

</select>

<br><br>

感想

<br>

<input
type="text"
name="comment">
<br><br>
<input
type="submit"
value="投稿">
</form>
<hr>
<h2>投稿一覧</h2>
<?php

$sql=
"
SELECT *
FROM drink_post

ORDER BY
id DESC
";

$stmt=
$pdo->query($sql);

foreach(
$stmt
as
$row
){

echo
$row["drink"];

echo
"<br>";

echo
$row["comment"];

echo
"<br>";

echo
$row["dt"];

echo
"<hr>";
?>
<form method="post">
    <input type="hidden"name="delete"value="<?php echo$row["id"]?>">
    <input type="submit"value="削除">
</form>
<hr>
<?php
}
?>


<h2>人気ランキング</h2>

<?php

$sql=
"
SELECT

drink,

COUNT(*)
AS cnt

FROM
drink_post

GROUP BY
drink

ORDER BY
cnt DESC
";

$stmt=
$pdo->query($sql);

$rank=1;

foreach(
$stmt
as
$row
){

echo
$rank;

echo
"位 ";

echo
$row["drink"];

echo
" ";

echo
$row["cnt"];

echo
"回";

echo
"<br>";



/* 棒 */

for(
$i=0;
$i<$row["cnt"];
$i++
){

echo
"■";

}

echo
"<hr>";

$rank++;

}

?>

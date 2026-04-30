<?php
require_once 'connection.php';

$N = 3;
$C = 5;

$mysqli = new mysqli($host, $user, $password, $database);

$orderBy = "";
if (isset($_GET["sortBy"])) {
	$allowed = ['name', 'price'];
	if (in_array($_GET['sortBy'], $allowed)) {
		$orderBy = "ORDER BY " . $_GET['sortBy'];
	}
}

$sql = "SELECT * FROM games " . $orderBy;
$result = $mysqli->query($sql);

?>

<!DOCTYPE html>
<html>
<body>
<form action="showTable.php" method="GET">
<label>
 	<input type="radio" name="sortBy" value="name"
 		<?php if (isset($_GET['sortBy']) && $_GET['sortBy'] == 'name') echo 'checked';?>>
	Сортировать по названию
</label>
<br>
<label>
	<input type="radio" name="sortBy" value="price"
		<?php if (isset($_GET['sortBy']) && $_GET['sortBy'] == 'price') echo 'checked';?>>
	Сортировать по цене
</label>
<br>
<button type="sumbit">Сортировать</button>

<?php
if ($result->num_rows > 0) {
	echo "<table border='1'>";
	echo "<tr>
			<th><a href='showTable.php?sortBy=name'>Название</th>
			<th>Описание</th>
			<th><a href='showTable.php?sortBy=price'>Цена</th>
			</tr>";
	while ($row = $result->fetch_assoc()) {
		echo "<tr>";
		echo "<td>" . htmlspecialchars($row['name']) . "</td>";
		echo "<td>" . htmlspecialchars($row['description']) . "</td>";
		echo "<td>" . number_format($row['price'], 2, ',', ' ') . " руб.</td>";
		echo "</tr>";
	}
	
	echo "</table>";
} else {
	echo "Нет данных для отображения.";
}
	
$mysqli->close();
?>

</body>
</html>
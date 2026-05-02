<?php
require_once 'connection.php';

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

<form action="editTable.php" method="GET">
    <button type="submit" name="action" value="add" class="add-btn">+ Добавить товар</button>
</form>

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
    <button type="submit">Сортировать</button>
</form>

<?php
if ($result->num_rows > 0) {
    echo "<form action='editTable.php' method='GET'>";
    echo "<table border='1'>";
    echo "<tr>";
    echo "<th><a href='showTable.php?sortBy=name'>Название</a></th>";
    echo "<th>Описание</th>";
    echo "<th><a href='showTable.php?sortBy=price'>Цена</a></th>";
    echo "<th>Редактировать</th>";  // 5.3
    echo "<th>Удалить</th>";         // 5.1
    echo "</tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
        echo "<td>" . number_format($row['price'], 2, ',', ' ') . " руб.</td>";
        
        echo "<td>";
        echo "<button name='action' type='submit' value='edit_{$row['id']}'>✏ Редактировать</button>";
        echo "</td>";
        
        echo "<td>";
        echo "<button name='action' type='submit' value='del_{$row['id']}'>🗑 Удалить</button>";
        echo "</td>";
        
        echo "</tr>";
    }
    echo "</table>";
    echo "</form>";
} else {
    echo "Нет данных для отображения.";
}

$mysqli->close();
?>

</body>
</html>
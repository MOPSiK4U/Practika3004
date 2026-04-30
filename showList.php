<?php
require_once 'connection.php';

$mysqli = new mysqli($host, $user, $password, $database);

$categories = ['Шутер', 'RPG', 'Симулятор'];

$categoryFilter = "";
$selectedCategory = "";
if (isset($_GET['category']) && $_GET['category'] !== '') {
    $selectedCategory = $mysqli->real_escape_string($_GET['category']);
    $categoryFilter = "WHERE category = '$selectedCategory'";
}

$N = 3;

$countSql = "SELECT COUNT(*) as total FROM games " . $categoryFilter;
$countResult = $mysqli->query($countSql);
$totalRow = $countResult->fetch_assoc();
$totalItems = $totalRow['total'];
$totalPages = ($totalItems > 0) ? ceil($totalItems / $N) : 1;

$currentPage = 1;
if (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) {
    $currentPage = (int)$_GET['page'];
    if ($currentPage > $totalPages && $totalPages > 0) {
        $currentPage = $totalPages;
    }
}

$offset = ($currentPage - 1) * $N;
$limitSql = "LIMIT $offset, $N";

$sql = "SELECT * FROM games $categoryFilter $limitSql";
$result = $mysqli->query($sql);
?>

<!DOCTYPE html>
<html>
<body>

<div class="categories">
    <strong>Категории:</strong>
    <a href="showList.php" class="<?php echo ($selectedCategory == '') ? 'active-cat' : ''; ?>">Очистить</a>
    <?php foreach ($categories as $cat): ?>
        <a href="showList.php?category=<?php echo urlencode($cat); ?>"
           class="<?php echo ($selectedCategory == $cat) ? 'active-cat' : ''; ?>">
            <?php echo htmlspecialchars($cat); ?>
        </a>
    <?php endforeach; ?>
</div>

<?php
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='game'>";
        echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
        echo "<p>" . htmlspecialchars($row['description']) . "</p>";
        echo "<p>Цена: " . number_format($row['price'], 2, ',', ' ') . " руб.</p>";
        if (!empty($row['category'])) {
            echo "<p><small>Категория: " . htmlspecialchars($row['category']) . "</small></p>";
        }
        echo "</div><hr>";
    }
} else {
    echo "Товары не найдены.";
}
?>

<div class="pagination">
    <?php 
    $displayPages = max($totalPages, 1);
    
    for ($i = 1; $i <= $displayPages; $i++): 
        $categoryParam = $selectedCategory ? '&category=' . urlencode($selectedCategory) : '';
    ?>
        <a href="showList.php?page=<?php echo $i; ?><?php echo $categoryParam; ?>"
           class="<?php echo ($i == $currentPage) ? 'active' : ''; ?>">
            <?php echo $i; ?>
        </a>
    <?php endfor; ?>
    
    <span style="margin-left: 20px; font-size: 12px; color: gray;">
        (Всего товаров: <?php echo $totalItems; ?>, страниц: <?php echo $displayPages; ?>)
    </span>
</div>

<?php $mysqli->close(); ?>
</body>
</html>
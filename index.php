<?php
require_once 'header.php';

$orderBy = "ORDER BY name";
$whereClause = "";
$params = [];

if (!empty($selectedCategory)) {
    $whereClause = "WHERE category = :category";
    $params[':category'] = $selectedCategory;
}

$sql = "SELECT * FROM games $whereClause $orderBy";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Список товаров</h2>

<?php if (count($products) > 0): ?>
    <?php foreach ($products as $product): ?>
        <div>
            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
            <a href="info.php?id=<?php echo $product['id']; ?>">Подробнее →</a>
        </div>
        <hr>
    <?php endforeach; ?>
<?php else: ?>
    <p>Товары не найдены.</p>
<?php endif; ?>

<?php require_once 'footer.php'; ?>
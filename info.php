<?php
require_once 'header.php';

$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$productId) {
    echo "<p>Ошибка: товар не указан.</p>";
    require_once 'footer.php';
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM games WHERE id = :id");
$stmt->execute([':id' => $productId]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "<p>Товар не найден.</p>";
    require_once 'footer.php';
    exit;
}

$inCart = false;
if (isset($_POST['add_to_cart'])) {
    $stmt = $pdo->prepare("INSERT INTO cart (product_id, added_at) VALUES (:product_id, NOW())");
    $stmt->execute([':product_id' => $productId]);
    $inCart = true;
}

$stmt = $pdo->prepare("SELECT COUNT(*) FROM cart WHERE product_id = :product_id");
$stmt->execute([':product_id' => $productId]);
$cartCount = $stmt->fetchColumn();
$inCart = $cartCount > 0;

$stmt = $pdo->prepare("SELECT * FROM photos WHERE product_id = :product_id");
$stmt->execute([':product_id' => $productId]);
$photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2><?php echo htmlspecialchars($product['name']); ?></h2>

<?php if (!empty($product['logo'])): ?>
    <img src="<?php echo htmlspecialchars($product['logo']); ?>" alt="Logo" width="50">
<?php endif; ?>

<p><strong>Описание:</strong> <?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
<p><strong>Цена:</strong> <?php echo number_format($product['price'], 2, ',', ' '); ?> руб.</p>

<form action="" method="POST">
    <button type="submit" name="add_to_cart" <?php echo $inCart ? 'disabled' : ''; ?>>
        <?php echo $inCart ? 'Товар в корзине' : 'Добавить в корзину'; ?>
    </button>
</form>

<?php if (count($photos) > 0): ?>
    <h3>Иллюстрации</h3>
    <?php foreach ($photos as $photo): ?>
        <img src="<?php echo htmlspecialchars($photo['path']); ?>" alt="Иллюстрация" width="150">
    <?php endforeach; ?>
<?php endif; ?>

<br><br>
<a href="index.php">← Назад к списку</a>

<?php require_once 'footer.php'; ?>
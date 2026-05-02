<?php
require_once 'connection.php';

$mysqli = new mysqli($host, $user, $password, $database);

$action = isset($_GET['action']) ? $_GET['action'] : '';
$productId = null;
$product = null;
$mode = '';

// Определяем действие
if (strpos($action, 'del_') === 0) {
    $mode = 'delete';
    $productId = (int)str_replace('del_', '', $action);
} elseif (strpos($action, 'edit_') === 0) {
    $mode = 'edit';
    $productId = (int)str_replace('edit_', '', $action);
    $result = $mysqli->query("SELECT * FROM games WHERE id = $productId");
    $product = $result->fetch_assoc();
} elseif ($action == 'add') {
    $mode = 'add';
}

$mysqli->close();
?>

<!DOCTYPE html>
<html>
<body>

<?php if ($mode == 'delete'): ?>
    <h2>Подтверждение удаления</h2>
    <p>Вы действительно хотите удалить товар с ID = <?php echo $productId; ?>?</p>
    <form action="saveTable.php" method="POST">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="id" value="<?php echo $productId; ?>">
        <button type="submit" name="confirm" value="yes">Да</button>
        <button type="submit" name="confirm" value="no">Нет</button>
    </form>

<?php elseif ($mode == 'edit' && $product): ?>
    <h2>Редактирование товара</h2>
    <form action="saveTable.php" method="POST">
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
        
        <label>Название:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
        
        <label>Описание:</label>
        <textarea name="description" rows="4" required><?php echo htmlspecialchars($product['description']); ?></textarea>
        
        <label>Цена (руб.):</label>
        <input type="number" name="price" step="0.01" value="<?php echo $product['price']; ?>" required>
        
        <button type="submit">Сохранить</button>
        <a href="showTable.php"><button type="button">Отмена</button></a>
    </form>

<?php elseif ($mode == 'add'): ?>
    <h2>Добавление нового товара</h2>
    <form action="saveTable.php" method="POST">
        <input type="hidden" name="action" value="add">
        
        <label>Название:</label>
        <input type="text" name="name" required>
        
        <label>Описание:</label>
        <textarea name="description" rows="4" required></textarea>
        
        <label>Цена (руб.):</label>
        <input type="number" name="price" step="0.01" required>
        
        <button type="submit">Сохранить</button>
        <a href="showTable.php"><button type="button">Отмена</button></a>
    </form>

<?php else: ?>
    <p>Ошибка: неверное действие.</p>
    <a href="showTable.php">Вернуться к списку товаров</a>
<?php endif; ?>

</body>
</html>
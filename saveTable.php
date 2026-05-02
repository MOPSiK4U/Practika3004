<?php
require_once 'connection.php';

$mysqli = new mysqli($host, $user, $password, $database);

$action = isset($_POST['action']) ? $_POST['action'] : '';
$message = '';
$success = false;

if ($action == 'delete') {
    $confirm = isset($_POST['confirm']) ? $_POST['confirm'] : '';
    $id = (int)$_POST['id'];
    
    if ($confirm == 'yes') {
        $sql = "DELETE FROM games WHERE id = $id";
        if ($mysqli->query($sql)) {
            $message = "Товар с ID = $id успешно удалён!";
            $success = true;
        } else {
            $message = "Ошибка удаления: " . $mysqli->error;
        }
    } elseif ($confirm == 'no') {
        $message = "Удаление отменено.";
        $success = false;
    }
}

// 5.3.3 РЕДАКТИРОВАНИЕ
elseif ($action == 'edit') {
    $id = (int)$_POST['id'];
    $name = $mysqli->real_escape_string($_POST['name']);
    $description = $mysqli->real_escape_string($_POST['description']);
    $price = (float)$_POST['price'];
    
    $sql = "UPDATE games SET 
            name = '$name', 
            description = '$description', 
            price = $price 
            WHERE id = $id";
    
    if ($mysqli->query($sql)) {
        $message = "Товар «$name» успешно обновлён!";
        $success = true;
    } else {
        $message = "Ошибка обновления: " . $mysqli->error;
    }
}

elseif ($action == 'add') {
    $name = $mysqli->real_escape_string($_POST['name']);
    $description = $mysqli->real_escape_string($_POST['description']);
    $price = (float)$_POST['price'];
    
    $sql = "INSERT INTO games (name, description, price) VALUES ('$name', '$description', $price)";
    
    if ($mysqli->query($sql)) {
        $message = "Новый товар «$name» успешно добавлен!";
        $success = true;
    } else {
        $message = "Ошибка добавления: " . $mysqli->error;
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html>
<body>
    <div class="message <?php echo $success ? 'success' : 'error'; ?>">
        <?php echo $message; ?>
    </div>
    <a href="showTable.php">← Вернуться к списку товаров</a>
</body>
</html>
<?php
require_once 'connection.php';

$selectedCategory = isset($_GET['category']) ? $_GET['category'] : '';

$stmt = $pdo->query("SELECT DISTINCT category FROM games WHERE category IS NOT NULL AND category != ''");
$categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Магазин игр</title>
</head>
<body>

<header>
    <h1>Магазин игр</h1>
    <div>
        <a href="index.php">Все</a>
        <?php foreach ($categories as $cat): ?>
            <a href="index.php?category=<?php echo urlencode($cat); ?>">
                <?php echo htmlspecialchars($cat); ?>
            </a>
        <?php endforeach; ?>
    </div>
</header>

<main>
</main>

<footer>
    <hr>
    <p>&copy; <?php echo date('Y'); ?> Магазин игр. Все права защищены.</p>
    <form action="index.php" method="POST">
        <input type="email" name="subscribe_email" placeholder="Ваш e-mail" required>
        <button type="submit" name="subscribe">Подписаться</button>
    </form>
    <?php
    if (isset($_POST['subscribe']) && isset($_POST['subscribe_email'])) {
        echo "<p>Вы подписались на рассылку!</p>";
    }
    ?>
</footer>

</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <title>Игра "Арифметическая прогрессия"</title>
</head>
<body>
    <h2>Найдите пропущенное число:</h2>
    <p><?= implode(' ', $progressionData['progression']) ?></p>

    <form method="POST">
        <input type="text" name="name" placeholder="Ваше имя" required>
        <input type="text" name="answer" placeholder="Введите число" required>
        <input type="hidden" name="progressionData" value='<?= htmlspecialchars($progressionJson) ?>'>
        <button type="submit">Ответить</button>
    </form>

    <?= isset($message) ? $message : '' ?>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <script>
            setTimeout(function() {
                window.location.href = 'index.php';
            }, 3000);
        </script>
    <?php endif; ?>
</body>
</html>
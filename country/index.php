<?php
session_start();
$json_file = 'countries.json';
$data = file_get_contents($json_file);
$countries = json_decode($data, true);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>国一覧 - あゆ鯖国管理システム</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <h2>国一覧</h2>
        <?php if (count($countries) > 0): ?>
            <?php foreach ($countries as $country): ?>
                <div class="country-item">
                    <button class="country-button"><?php echo htmlspecialchars($country['name']); ?></button>
                    <div class="country-details">
                        <p><strong>代表:</strong> <?php echo htmlspecialchars($country['representative']); ?></p>
                        <p><strong>座標:</strong> X: <?php echo htmlspecialchars($country['coordinates']['x']); ?> Y: <?php echo htmlspecialchars($country['coordinates']['y']); ?> Z: <?php echo htmlspecialchars($country['coordinates']['z']); ?></p>
                        <p><strong>国民:</strong></p>
                        <ul class="citizen-list">
                            <?php foreach ($country['citizens'] as $citizen): ?>
                                <li>・<?php echo htmlspecialchars($citizen); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>国のデータがありません。</p>
        <?php endif; ?>
    </div>
    <script>
        document.querySelectorAll('.country-button').forEach(button => {
            button.addEventListener('click', () => {
                const details = button.nextElementSibling;
                details.classList.toggle('active');
            });
        });
    </script>
</body>
</html>

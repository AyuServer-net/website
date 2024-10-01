<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$countries = json_decode(file_get_contents('countries.json'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_country'])) {
        $name = $_POST['name'];
        $representative = $_POST['representative'];
        $x = $_POST['x'];
        $y = $_POST['y'];
        $z = $_POST['z'];
        $citizens = explode("\n", trim($_POST['citizens']));

        $countries[] = [
            'name' => $name,
            'representative' => $representative,
            'coordinates' => ['x' => $x, 'y' => $y, 'z' => $z],
            'citizens' => $citizens
        ];

        file_put_contents('countries.json', json_encode($countries, JSON_PRETTY_PRINT));
        header('Location: add_country.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>国追加 - あゆ鯖国管理システム</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <h2>国を追加</h2>

        <form method="post" action="add_country.php">
            <label for="name">国名:</label>
            <input type="text" id="name" name="name" required><br><br>

            <label for="representative">代表者:</label>
            <input type="text" id="representative" name="representative" required><br><br>

            <label for="x">X座標:</label>
            <input type="text" id="x" name="x" required><br><br>

            <label for="y">Y座標:</label>
            <input type="text" id="y" name="y" required><br><br>

            <label for="z">Z座標:</label>
            <input type="text" id="z" name="z" required><br><br>

            <label for="citizens">国民 (1行1名):</label>
            <textarea id="citizens" name="citizens" rows="5" required></textarea><br><br>

            <button type="submit" name="add_country" class="country-button">追加</button>
        </form>
    </div>
</body>
</html>

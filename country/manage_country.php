<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// JSONファイルのパス
$jsonFilePath = 'countries.json';

// JSONファイルから国の情報を読み込む
if (file_exists($jsonFilePath)) {
    $jsonData = file_get_contents($jsonFilePath);
    $countries = json_decode($jsonData, true);
} else {
    $countries = [];
}

// 編集用の国のデータを初期化
$selectedCountry = null;
if (isset($_POST['country'])) {
    $selectedCountryName = $_POST['country'];
    foreach ($countries as $country) {
        if ($country['name'] === $selectedCountryName) {
            $selectedCountry = $country;
            break;
        }
    }
}

// 国の情報を更新する処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update'])) {
        $updatedName = $_POST['name'];
        $updatedRepresentative = $_POST['representative'];
        $updatedX = $_POST['x'];
        $updatedY = $_POST['y'];
        $updatedZ = $_POST['z'];
        $updatedCitizens = array_filter(array_map('trim', explode("\n", $_POST['citizens'])));

        foreach ($countries as &$country) {
            if ($country['name'] === $selectedCountryName) {
                $country['name'] = $updatedName;
                $country['representative'] = $updatedRepresentative;
                $country['coordinates'] = [
                    'x' => $updatedX,
                    'y' => $updatedY,
                    'z' => $updatedZ
                ];
                $country['citizens'] = $updatedCitizens;
                break;
            }
        }
        file_put_contents($jsonFilePath, json_encode($countries, JSON_PRETTY_PRINT));
        $selectedCountry = null; // 更新後にリセット
    } elseif (isset($_POST['delete'])) {
        $nameToDelete = $_POST['country'];
        $countries = array_filter($countries, function($country) use ($nameToDelete) {
            return $country['name'] !== $nameToDelete;
        });
        file_put_contents($jsonFilePath, json_encode($countries, JSON_PRETTY_PRINT));
        $selectedCountry = null; // 削除後にリセット
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>国の管理</title>
    <link rel="stylesheet" href="styles.css"> <!-- CSSファイルをリンク -->
</head>
<body>
<header>
    <div class="header-content">
        <h1>あゆ鯖 国管理システム</h1>
        <nav>
            <ul>
                <li><a href="index.php">国一覧</a></li>
                <?php if (isset($_SESSION['username'])): ?>
                    <li><a href="add_country.php">国を追加</a></li>
                    <li><a href="manage_country.php">国を管理</a></li>
                    <li><a href="logout.php">ログアウト</a></li>
                <?php else: ?>
                    <li><a href="login.php">ログイン</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>

<div class="container">
    <h2>国の選択</h2>
    <form method="post" action="manage_country.php">
        <div class="form-group">
            <label for="country">国を選択:</label>
            <select id="country" name="country" onchange="this.form.submit()">
                <option value="">選択してください</option>
                <?php foreach ($countries as $country): ?>
                    <option value="<?= htmlspecialchars($country['name']) ?>" <?= isset($selectedCountry) && $selectedCountry['name'] === $country['name'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($country['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    <?php if ($selectedCountry): ?>
        <h2>国の編集</h2>
        <form method="post" action="manage_country.php">
            <input type="hidden" name="country" value="<?= htmlspecialchars($selectedCountry['name']) ?>">
            <div class="form-group">
                <label for="name">国名:</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($selectedCountry['name']) ?>" required>
            </div>
            <div class="form-group">
                <label for="representative">代表者:</label>
                <input type="text" id="representative" name="representative" value="<?= htmlspecialchars($selectedCountry['representative']) ?>" required>
            </div>
            <div class="form-group">
                <label for="x">座標 X:</label>
                <input type="text" id="x" name="x" value="<?= htmlspecialchars($selectedCountry['coordinates']['x']) ?>" required>
            </div>
            <div class="form-group">
                <label for="y">座標 Y:</label>
                <input type="text" id="y" name="y" value="<?= htmlspecialchars($selectedCountry['coordinates']['y']) ?>" required>
            </div>
            <div class="form-group">
                <label for="z">座標 Z:</label>
                <input type="text" id="z" name="z" value="<?= htmlspecialchars($selectedCountry['coordinates']['z']) ?>" required>
            </div>
            <div class="form-group">
                <label for="citizens">国民 (1行1人):</label>
                <textarea id="citizens" name="citizens" rows="10" required><?= htmlspecialchars(implode("\n", $selectedCountry['citizens'])) ?></textarea>
            </div>
            <button type="submit" name="update">更新</button>
            <button type="submit" name="delete" style="background-color: red; color: white;">削除</button>
        </form>
    <?php elseif (isset($_POST['country'])): ?>
        <p>指定された国が見つかりません。</p>
    <?php endif; ?>
</div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>あゆ鯖 国管理システム</title>
    <link rel="stylesheet" href="styles.css">
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

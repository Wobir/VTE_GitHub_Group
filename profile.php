<?php
// --- БЛОК ЛОГИКИ (PHP) ---
$host = 'localhost';
$dbname = 'vk_db'; // Укажи имя своей БД
$username = 'root'; 
$password = '';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
} catch (PDOException $e) {
    die("Ошибка подключения к БД: " . $e->getMessage());
}

// Обработка добавления поста
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['message'])) {
    $message = htmlspecialchars($_POST['message']);
    $stmt = $db->prepare("INSERT INTO wall_posts (text) VALUES (:text)");
    $stmt->execute();
    
    // Перенаправление, чтобы избежать повторной отправки при обновлении страницы (F5)
    header("Location: profile.php");
    exit;
}

// Получение постов
$posts = $db->query("SELECT * FROM wall_posts ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Павел Дуров | В Контакте</title>
    <style>
        body { background: #f7f7f7; font-family: Tahoma, Arial, sans-serif; font-size: 11px; margin: 0; padding: 0; }
        
        /* Шапка */
        #header { background: #5f7fa4; height: 42px; border-bottom: 1px solid #45688e; color: #fff; }
        .container { width: 770px; margin: 0 auto; position: relative; }
        #logo { font-weight: bold; font-size: 13px; padding-top: 13px; float: left; }
        
        /* Контент */
        #content { width: 770px; margin: 15px auto; display: flex; }
        
        /* Левое меню */
        #left_menu { width: 130px; }
        #left_menu a { display: block; color: #2b587a; text-decoration: none; padding: 3px 6px; margin-bottom: 2px; }
        #left_menu a:hover { background: #e1e7ed; }
        
        /* Основная часть */
        #main_profile { flex-grow: 1; background: #fff; border: 1px solid #dbe1e8; padding: 15px; display: flex; }
        
        #photo_col { width: 200px; margin-right: 20px; }
        #photo_col img { border: 1px solid #dae1e8; padding: 3px; width: 200px; }
        
        #info_col { flex-grow: 1; }
        h2 { color: #45688e; font-size: 17px; margin: 0 0 10px 0; border-bottom: 1px solid #dae1e8; padding-bottom: 5px; }
        
        .info_row { margin-bottom: 5px; overflow: hidden; }
        .label { float: left; width: 120px; color: #777; }
        .data { color: #2b587a; }
        
        /* Стена */
        .wall_title { color: #45688e; font-weight: bold; margin-top: 25px; border-bottom: 1px solid #dae1e8; padding-bottom: 3px; }
        #wall_form { background: #f7f7f7; border: 1px solid #dae1e8; padding: 10px; margin: 10px 0; }
        textarea { width: 97%; height: 40px; border: 1px solid #ccc; font-family: Tahoma; font-size: 11px; padding: 5px; resize: none; }
        .btn { background: #6083a9; color: #fff; border: 1px solid #45688e; padding: 4px 12px; cursor: pointer; margin-top: 5px; }
        
        .post { border-bottom: 1px solid #dae1e8; padding: 10px 0; }
        .post_author { color: #2b587a; font-weight: bold; }
        .post_date { color: #999; font-size: 9px; margin-top: 5px; }
    </style>
</head>
<body>

<div id="header">
    <div class="container">
        <div id="logo">В Контакте</div>
    </div>
</div>

<div id="content">
    <div id="left_menu">
        <a href="#">Моя Страница</a>
        <a href="#">Мои Друзья</a>
        <a href="#">Мои Фотографии</a>
        <a href="#">Мои Сообщения</a>
        <a href="#">Мои Настройки</a>
    </div>

    <div id="main_profile">
        <div id="photo_col">
            <img src="https://vk.com" alt="Фото">
            <button class="btn" style="width: 100%; margin-top: 10px;">Редактировать</button>
        </div>

        <div id="info_col">
            <h2>Павел Дуров</h2>
            <div class="info_row">
                <div class="label">День рождения:</div>
                <div class="data">10 октября 1984 г.</div>
            </div>
            <div class="info_row">
                <div class="label">Город:</div>
                <div class="data">Ленинград</div>
            </div>
            <div class="info_row">
                <div class="label">Школа:</div>
                <div class="data">Академическая гимназия</div>
            </div>

            <!-- СТЕНА -->
            <div class="wall_title">Стена</div>
            
            <form id="wall_form" method="POST">
                <textarea name="message" placeholder="Ваше сообщение..."></textarea>
                <div style="text-align: right;">
                    <input type="submit" class="btn" value="Отправить">
                </div>
            </form>

            <div id="wall_posts">
                <?php if (empty($posts)): ?>
                    <p style="color: #777; text-align: center;">На стене пока нет записей.</p>
                <?php else: ?>
                    <?php foreach ($posts as $post): ?>
                        <div class="post">
                            <div class="post_author">Иван Иванов</div>
                            <div class="post_text"><?php echo $post; ?></div>
                            <div class="post_date"><?php echo $post['date_added']; ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>

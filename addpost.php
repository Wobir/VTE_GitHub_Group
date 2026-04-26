<?php

$host = "";
$user = "";
$pass = "";
$db   = "";

$link = mysqli_connect($host, $user, $pass, $db);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $text = mysqli_real_escape_string($link, $_POST['message']);
    if (!empty($text)) {
        mysqli_query($link, "INSERT INTO posts (content) VALUES ('$text')");
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

$result = mysqli_query($link, "SELECT * FROM posts ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Стенка</title>
    
    <link href="https://jsdelivr.net" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        body { 
            background-color: #f0f2f5; 
            font-family: Arial, sans-serif;
        }
        .container { 
            max-width: 700px; 
        }
        .post-card { 
            border-radius: 12px; 
            border: none; 
            box-shadow: 0 1px 3px rgba(0,0,0,0.1); 
            margin-bottom: 20px;
        }
        .btn-primary { 
            background-color: #0866ff; 
            border: none; 
            font-weight: 600; 
        }
        .btn-primary:hover { 
            background-color: #0055e0; 
        }
        .card-title { 
            font-weight: bold; 
            color: #1c1e21; 
        }
        .post-date { 
            font-size: 0.85rem; 
            color: #65676b; 
        }
        textarea { 
            resize: none; 
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="card post-card">
        <div class="card-body">
            <h5 class="card-title mb-3">Создать запись</h5>
            <form method="POST">
                <textarea name="message" class="form-control mb-3" rows="3" placeholder="Что у вас нового?" required></textarea>
                <button type="submit" class="btn btn-primary px-4">Опубликовать</button>
            </form>
        </div>
    </div>

    <hr class="my-4">
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="card post-card">
            <div class="card-body">
                <p class="card-text" style="font-size: 1.1rem;">
                    <?php echo htmlspecialchars($row['content']); ?>
                </p>
                <div class="post-date">
                    📅 <?php echo date('d.m.Y H:i', strtotime($row['created_at'])); ?>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>
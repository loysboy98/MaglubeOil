<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

require 'vendor/autoload.php'; // PHPMailer + phpdotenv

// Загружаем .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'] ?? '';
    $email    = $_POST['email'] ?? '';
    $phone    = $_POST['phone'] ?? '';
    $position = $_POST['position'] ?? '';
    $message  = $_POST['message'] ?? '';

    $mail = new PHPMailer(true);

    try {
        // Настройки SMTP из .env
        $mail->isSMTP();
        $mail->Host       = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['SMTP_USER'];
        $mail->Password   = $_ENV['SMTP_PASS'];
        $mail->SMTPSecure = $_ENV['SMTP_SECURE'];
        $mail->Port       = $_ENV['SMTP_PORT'];

        // Кодировка
        $mail->CharSet  = 'UTF-8';
        $mail->Encoding = 'base64';

        // Отправитель
        $mail->setFrom($_ENV['MAIL_FROM'], $_ENV['MAIL_FROM_NAME']);

        // Получатели
        $mail->addAddress($_ENV['MAIL_TO1']);
        $mail->addAddress($_ENV['MAIL_TO2']);

        // Прикрепляем файл (проверка PDF + размер)
        if (isset($_FILES['resume']) && $_FILES['resume']['error'] === 0) {
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime  = $finfo->file($_FILES['resume']['tmp_name']);
            if ($mime === 'application/pdf' && $_FILES['resume']['size'] <= 5*1024*1024) {
                $mail->addAttachment($_FILES['resume']['tmp_name'], $_FILES['resume']['name']);
            }
        }

        // Формат письма
        $mail->isHTML(true);
        $mail->Subject = "Новое заявление: $fullname";
        $mail->Body    = "
            <b>Имя:</b> $fullname <br>
            <b>Email:</b> $email <br>
            <b>Телефон:</b> $phone <br>
            <b>Должность:</b> $position <br>
            <b>Сообщение:</b> $message
        ";

        $mail->send();

        // Красивый ответ
        echo '
        <html lang="ru">
        <head>
          <meta charset="UTF-8">
          <title>Заявление отправлено</title>
          <style>
            body { font-family: Arial, sans-serif; background: #f3f4f6; display:flex; justify-content:center; align-items:center; height:100vh; margin:0; }
            .message-box { background:#fff; padding:30px; border-radius:12px; text-align:center; max-width:400px; width:90%; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
            .success { color:#28a745; font-size:20px; margin-bottom:20px; }
            .btn { display:inline-block; margin-top:20px; padding:10px 20px; background:#007BFF; color:#fff; text-decoration:none; border-radius:8px; transition:0.3s; }
            .btn:hover { background:#0056b3; }
          </style>
        </head>
        <body>
          <div class="message-box">
            <div class="success">✅ Ваше заявление успешно отправлено!</div>
            <a href="index.html" class="btn">⬅ Вернуться назад</a>
          </div>
        </body>
        </html>';
    } catch (Exception $e) {
        // Логируем ошибку (не показываем пароль пользователю)
        file_put_contents(__DIR__ . '/mail_error.log', date('c')." - ".$mail->ErrorInfo.PHP_EOL, FILE_APPEND);

        echo '
        <html lang="ru">
        <head>
          <meta charset="UTF-8">
          <title>Ошибка</title>
          <style>
            body { font-family: Arial, sans-serif; background: #f3f4f6; display:flex; justify-content:center; align-items:center; height:100vh; margin:0; }
            .message-box { background:#fff; padding:30px; border-radius:12px; text-align:center; max-width:400px; width:90%; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
            .error { color:#dc3545; font-size:20px; margin-bottom:20px; }
            .btn { display:inline-block; margin-top:20px; padding:10px 20px; background:#007BFF; color:#fff; text-decoration:none; border-radius:8px; transition:0.3s; }
            .btn:hover { background:#0056b3; }
          </style>
        </head>
        <body>
          <div class="message-box">
            <div class="error">❌ Произошла ошибка при отправке.</div>
            <a href="index.html" class="btn">⬅ Вернуться назад</a>
          </div>
        </body>
        </html>';
    }
}

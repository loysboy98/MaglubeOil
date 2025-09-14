<?php
// Подключаем PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Подключение файлов PHPMailer (если нет composer)
require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

// Проверяем, что форма отправлена методом POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Собираем данные из формы
    $fullname = $_POST['fullname'] ?? '';  // Полное имя
    $email    = $_POST['email'] ?? '';     // Почта кандидата
    $phone    = $_POST['phone'] ?? '';     // Телефон кандидата
    $position = $_POST['position'] ?? '';  // Желаемая должность
    $message  = $_POST['message'] ?? '';   // Сообщение

    // Создаем объект PHPMailer
    $mail = new PHPMailer(true);

    try {
        /* 
         =======================
         🔑 НАСТРОЙКА SMTP
         =======================
         */
        $mail->isSMTP();                             // Используем SMTP
        $mail->Host       = 'mail.maglube.uz';       // Сервер исходящей почты (см. в cPanel)
        $mail->SMTPAuth   = true;                    // Включаем авторизацию
        $mail->Username   = 'info@test.uz';          // 📧 Почтовый ящик (логин)
        $mail->Password   = 'Password';              // 🔑 Пароль от почтового ящика
        $mail->SMTPSecure = 'ssl';                   // Шифрование (ssl или tls)
        $mail->Port       = 465;                     // Порт (обычно 465 для ssl или 587 для tls)

        /* 
         =======================
         📝 КОДИРОВКА
         =======================
         */
        $mail->CharSet  = 'UTF-8';   // Чтобы письма приходили без иероглифов
        $mail->Encoding = 'base64';

        /* 
         =======================
         👤 ОТПРАВИТЕЛЬ
         =======================
         */
        $mail->setFrom('info@test.uz', 'HR UNOC');  // От кого письмо

        /* 
         =======================
         📬 ПОЛУЧАТЕЛИ
         =======================
         */
        $mail->addAddress("test98@test.com");  // Первый получатель (HR 1)
        $mail->addAddress("test@test2.uz");    // Второй получатель (HR 2)

        /* 
         =======================
         📎 ВЛОЖЕНИЕ
         =======================
         */
        if (isset($_FILES['resume']) && $_FILES['resume']['error'] === 0) {
            // Проверяем, что файл существует и загружен успешно
            $mail->addAttachment($_FILES['resume']['tmp_name'], $_FILES['resume']['name']);
        }

        /* 
         =======================
         💌 СОДЕРЖАНИЕ ПИСЬМА
         =======================
         */
        $mail->isHTML(true); // Включаем HTML формат
        $mail->Subject = "Новое заявление: $fullname"; // Тема письма
        $mail->Body    = "
            <b>Имя:</b> $fullname <br>
            <b>Email:</b> $email <br>
            <b>Телефон:</b> $phone <br>
            <b>Должность:</b> $position <br>
            <b>Сообщение:</b> $message
        ";

        // Письмо отправлено успешно
        $mail->send();

        /* 
         =======================
         ✅ КРАСИВОЕ УВЕДОМЛЕНИЕ
         =======================
         */
        echo '
        <html lang="ru">
        <head>
          <meta charset="UTF-8">
          <title>Заявление отправлено</title>
          <style>
            body { font-family: Arial, sans-serif; background: #f3f4f6; margin:0; padding:0; display:flex; justify-content:center; align-items:center; height:100vh; }
            .message-box { background: #fff; padding: 30px; border-radius: 12px; text-align:center; max-width: 400px; width: 90%; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
            .success { color: #28a745; font-size:20px; margin-bottom:20px; }
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
        /* 
         =======================
         ❌ ЕСЛИ ОШИБКА
         =======================
         */
        echo '
        <html lang="ru">
        <head>
          <meta charset="UTF-8">
          <title>Ошибка</title>
          <style>
            body { font-family: Arial, sans-serif; background: #f3f4f6; margin:0; padding:0; display:flex; justify-content:center; align-items:center; height:100vh; }
            .message-box { background: #fff; padding: 30px; border-radius: 12px; text-align:center; max-width: 400px; width: 90%; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
            .error { color: #dc3545; font-size:20px; margin-bottom:20px; }
            .btn { display:inline-block; margin-top:20px; padding:10px 20px; background:#007BFF; color:#fff; text-decoration:none; border-radius:8px; transition:0.3s; }
            .btn:hover { background:#0056b3; }
          </style>
        </head>
        <body>
          <div class="message-box">
            <div class="error">❌ Ошибка при отправке: ' . $mail->ErrorInfo . '</div>
            <a href="index.html" class="btn">⬅ Вернуться назад</a>
          </div>
        </body>
        </html>';
    }
}

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
require 'db_config.php'; // Подключаем конфигурацию базы данных

// Создаем PDO инстанс
try {
    $pdo = new PDO("mysql:host={$dbConfig['host']};dbname={$dbConfig['db']};charset={$dbConfig['charset']}", $dbConfig['user'], $dbConfig['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    exit("Ошибка подключения к базе данных: " . $e->getMessage());
}

// Получение идентификатора пользователя для примера (здесь должна быть ваша логика аутентификации или сессии)
$userId = 1; // Затычка для примера, обычно здесь будет $_SESSION['user_id']

// Получаем список ID каналов-назначения пользователя
$stmt = $pdo->prepare("SELECT channel_id FROM user_channels WHERE user_id = ?");
$stmt->execute([$userId]);
$channels = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Если у пользователя нет каналов, отправляем пустой JSON
if (empty($channels)) {
    echo json_encode([]);
    exit;
}

// Получаем последние сообщения из этих каналов
$placeholders = implode(',', array_fill(0, count($channels), '?')); // Создаем строку плейсхолдеров для IN()
$stmt = $pdo->prepare("SELECT content, date_received FROM messages WHERE channel_id IN ($placeholders) ORDER BY message_id DESC LIMIT 50");
$stmt->execute($channels);
$messages = $stmt->fetchAll();

// Отправляем сообщения в JSON формате
header('Content-Type: application/json');
echo json_encode($messages);

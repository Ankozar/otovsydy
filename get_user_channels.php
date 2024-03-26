<?php
require 'db_config.php'; // Подключаем конфигурацию базы данных

// Создаем объект PDO для соединения с базой данных
try {
    $pdo = new PDO("mysql:host={$dbConfig['host']};dbname={$dbConfig['db']};charset={$dbConfig['charset']}", $dbConfig['user'], $dbConfig['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    exit("Ошибка подключения к базе данных: " . $e->getMessage());
}

// Предполагается, что у вас есть ID пользователя. Если нет, вы должны его получить, например из сессии.
$user_id = 1; // Сюда должен быть задан реальный идентификатор пользователя

// SQL-запрос для получения каналов-назначения пользователя
$sql = "SELECT channels.channel_id, channels.channel_name, channels.channel_url FROM channels
        JOIN user_channels ON channels.channel_id = user_channels.channel_id
        WHERE user_channels.user_id = :user_id";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user_channels = $stmt->fetchAll();

// Отправляем ответ с каналами в формате JSON
header('Content-Type: application/json');
echo json_encode($user_channels);

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

// Получаем идентификатор выбранного канала-назначения из GET параметра destination_channel_id
$destinationChannelId = isset($_GET['destination_channel_id']) ? (int)$_GET['destination_channel_id'] : 0;

// Предполагаем, что у вас есть связующая таблица между каналами-источниками и назначениями или логика, позволяющая получить список каналов-источников для канала-назначения.
// Так как структура базы данных для этого не описана, приведу общий запрос, который следует доработать под вашу конкретную схему.

$sql = "SELECT channel_id, channel_name, channel_url FROM channels
        WHERE channel_id IN (SELECT source_channel_id FROM channel_relationships WHERE destination_channel_id = :destination_channel_id)";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':destination_channel_id', $destinationChannelId, PDO::PARAM_INT);
$stmt->execute();
$source_channels = $stmt->fetchAll();

// Отправляем ответ с каналами в формате JSON
header('Content-Type: application/json');
echo json_encode($source_channels);
?>
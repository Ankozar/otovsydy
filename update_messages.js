document.addEventListener('DOMContentLoaded', function() {
    // Функция загрузки сообщений для ленты
    function loadMessages() {
        fetch('get_messages.php')
        .then(response => response.json())
        .then(data => {
            const messageStream = document.querySelector('.message-stream ul');
            messageStream.innerHTML = ''; // Очищаем текущие сообщения
            // Отображаем новые сообщения
            data.forEach(message => {
                const li = document.createElement('li');
                li.textContent = message.content; // Используйте ключ массива, соответствующий колонке содержимого сообщения
                messageStream.appendChild(li);
            });
        })
        .catch(error => {
            console.error('Ошибка:', error);
        });
    }

    // Инициализация ленты сообщений при загрузке
    loadMessages();

    // Установка таймера для автоматического обновления сообщений каждые 5 секунд
    setInterval(loadMessages, 5000);
});

$(document).ready(function() {
    // Получение сообщений
    function getMessages() {
        $.post('/api.php', {
            request: 'get_messages',
            channel_id: 2
        }, function(data) {
            // Отображение сообщений
            console.log(data)
            for (let message of Object.values(data)) {
                $('#messages').append('<p>' + message.text + '</p>');
            }
        },
        'json'
        );
    }

    // Отправка сообщения
    function sendMessage() {
        let text = $('#message').val();
        $.post('/api/send_message', {
            channel_id: 1234567890,
            text: text
        });
    }

    // Загрузка сообщений при загрузке страницы
    getMessages();

    // Отправка сообщения по нажатию кнопки
    $('#send').click(function() {
        sendMessage();
    });
});
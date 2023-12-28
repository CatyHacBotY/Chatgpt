<?php
$bottoken = '6977634869:AAE93iKkQo3bqOI9Eh5sGd6Lyh876AyIBho'; // Add your bot token here
$webhook_url = 'WEBHOOK URL'; // Your initial/default webhook URL
function sendMessage($chatID, $message) {
    global $bottoken;
    $url = 'https://api.telegram.org/bot' . $bottoken . '/sendMessage';
    $data = [
        'chat_id' => $chatID,
        'text' => $message
    ];
//Codes by @InfinityHackersKE
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
}

function sendChatAction($chatID, $action) {
    global $bottoken;
    $url = 'https://api.telegram.org/bot' . $bottoken . '/sendChatAction';
    $data = [
        'chat_id' => $chatID,
        'action' => $action
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
}

$update = file_get_contents("php://input");
$update = json_decode($update, true);

if ($_GET['url']) {
    $telegram_api_url = 'https://api.telegram.org/bot' . $bottoken . '/setWebhook?url=';
    $webhook = $_GET['url']; 

    $set_webhook_url = $telegram_api_url . $webhook;

    $ch = curl_init($set_webhook_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);


    if ($result === false) {
        echo "Error setting the webhook.";
    } else {
        echo "Webhook successfully set!";
    }
    exit; // Terminate execution after setting the webhook
}

if (isset($update['message'])) {
    $message = $update['message'];
    $chatId = $message['chat']['id'];
    $text = $message['text'];

    if ($text === '/start') {
        sendMessage($chatId, "鈽猴笍Hello! I'm here to assist you. You can begin a conversation by typing '*ihk*' followed by your question or message.");
    }

    if (strtolower(substr($text, 0, 5)) === 'ihk') {
        sendChatAction($chatId, 'typing');

        $query = substr($text, 5);
        $worker_api_url = "https://chatgpt.apinepdev.workers.dev/?question=" . urlencode($query);

        $ch = curl_init($worker_api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $worker_response = curl_exec($ch);
        curl_close($ch);

        $response_data = json_decode($worker_response, true);
        $answer = $response_data['answer'] ?? '馃槅 Apologies, I cannot answer your question right now. Please try another question.';

        sendChatAction($chatId, 'typing');
        sendMessage($chatId, "馃槑 IHK GPT by @EscaliBud :\n$answer");
    }
}
//Share with Credits
//property of Infinity Hackers Kenya
?>

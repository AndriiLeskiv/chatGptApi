<?php
if (isset($_POST['action']) && $_POST['action'] == 'get_chat_response'){
    $user_message = trim($_POST['message']);
    $user_message = htmlspecialchars($user_message, ENT_QUOTES, 'UTF-8');

    /*My api*/

    $api_key = 'Your key';

    $url = 'https://api.openai.com/v1/chat/completions';

    $data = [
        "model" => "gpt-4o",
        "store" => true,
        "messages" => [
            ["role" => "user", "content" => $user_message],
        ]
    ];

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key,
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($response, true);

    if (isset($result['error'])) {
        echo json_encode(['success' => false, 'error' => 'OpenAI error: ' . $result['error']['message']]);
    } else {
        echo json_encode(['success' => true, 'response' => $result['choices'][0]['message']['content']]);
    }
}
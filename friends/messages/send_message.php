<?php
function addMessage($sender, $receiver, $content) {
    $filename = "data/messages/friend/{$sender}-{$receiver}.json";
    $message = [
        "sender" => $sender,
        "receiver" => $receiver,
        "content" => $content,
        "timestamp" => date("Y-m-d H:i:s")
    ];
    $messages = [];
    if (file_exists($filename)) {
        $messages = json_decode(file_get_contents($filename), true);
    }
    $messages["messages"][] = $message;
    file_put_contents($filename, json_encode($messages, JSON_PRETTY_PRINT));
}



?>
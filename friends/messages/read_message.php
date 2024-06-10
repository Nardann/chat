<?php
function readMessages($sender, $receiver) {
    $filename = "data/messages/friend/{$sender}-{$receiver}.json";
    if (file_exists($filename)) {
        $messages = json_decode(file_get_contents($filename), true);
        return $messages["messages"];
    } else {
        return [];
    }
}
?>
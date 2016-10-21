<?php

header('Content-type: application/json; charset=utf-8');

$json[] = array(
    'title' => (string)$page->title(),
    'uri' => (string)$page->uri(),
    'small_image_url'  => (string)$page->small_image_url(),
    'background_image_url'  => (string)$page->background_image_url(),
    'text'  => (string)$page->text()
); 

echo json_encode($json, JSON_UNESCAPED_SLASHES);

?>
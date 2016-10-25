<?php

header('Content-type: application/json; charset=utf-8');


function  create_options_list($category)
{
    $json = array();
    
    $options = $category->children()->visible();

    foreach($options as $option) 
    {
        $json[] = array(
            'title' => (string)$option->title(),
            'uri' => (string)$option->uri()
        );
    }

    return $json;    
}

$json = create_options_list($page);

echo json_encode($json, JSON_UNESCAPED_SLASHES);

?>
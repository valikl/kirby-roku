<?php

header('Content-type: application/json; charset=utf-8');

define("TYPE_VIDEO", "video");

function  create_playlist_children_list($item)
{
    $json = array();
    
    $videos = $item->children()->visible();
            
    foreach($videos as $video) {

        if ((string)$video->intendedTemplate() != TYPE_VIDEO)
        {
            // ERROR ERROR
        }

        $json[] = array(
            'type' => TYPE_VIDEO,
            'title' => (string)$video->title(),
            'background_image_url'  => (string)$video->background_image_url(),
            'side_image_url'  => (string)$video->side_image_url(),
            'video_url'  => (string)$video->video_url(),
            'description'  => (string)$video->description()
        ); 
    }
    
    return $json;
}

$json = create_playlist_children_list($page);

echo json_encode($json, JSON_UNESCAPED_SLASHES);
    
?>
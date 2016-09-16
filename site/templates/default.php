<?php

header('Content-type: application/json; charset=utf-8');

define("TYPE_CATEGORY", "category");
define("TYPE_PLAYLIST", "playlist");
define("TYPE_VIDEO", "video");

define("ROOT_NAME", "kabbalah-channel");

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

function  create_category_children_list($category)
{
    $json = array();
    //echo (string)$category->intendedTemplate();
    //echo (string)$category->title();
    //var_dump($category->toArray());
  
    if ((string)$category->intendedTemplate() != TYPE_CATEGORY)
    {
        // ERROR ERROR
    }
    
    $items = $category->children()->visible();
    
    foreach($items as $item) {
        
        //echo (string)$item->intendedTemplate();
        
        if ((string)$item->intendedTemplate() == TYPE_VIDEO)
        {
            $json[] = array(
                'type' => TYPE_VIDEO,
                'title' => (string)$item->title(),
                'background_image_url'  => (string)$item->background_image_url(),
                'side_image_url'  => (string)$item->side_image_url(),
                'video_url'  => (string)$item->video_url(),
                'description'  => (string)$item->description()
            ); 
            continue;
        }
        
        if ((string)$item->intendedTemplate() == TYPE_PLAYLIST)
        {
            $json[] = array(
                'type' => TYPE_PLAYLIST,
                'title' => (string)$item->title(),
                'image_url'  => (string)$item->image_url(),
                'description'  => (string)$item->description(),
                'children' => create_playlist_children_list($item)
            );
        }
    }
    
    return $json;
}


$data = $pages->find(ROOT_NAME)->children()->visible();

$json = array();

foreach($data as $category) {
   
    $json[] = array(
    'type' => TYPE_CATEGORY,
    'title' => (string)$category->title(),
    'children' => create_category_children_list($category)
    );
}

echo json_encode($json, JSON_UNESCAPED_SLASHES);

?>
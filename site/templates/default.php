<?php

header('Content-type: application/json; charset=utf-8');

define("TYPE_HOMEPAGE", "homepage");
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
            'uri' => (string)$video->uri(),
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
    
    $items = $category->children()->visible();
    
    foreach($items as $item) {
        
        //echo (string)$item->intendedTemplate();
                
        switch ((string)$item->intendedTemplate())
        {
            case TYPE_VIDEO:
            {
                $json[] = array(
                    'type' => TYPE_VIDEO,
                    'uri' => (string)$item->uri(),
                    'title' => (string)$item->title(),
                    'background_image_url'  => (string)$item->background_image_url(),
                    'side_image_url'  => (string)$item->side_image_url(),
                    'video_url'  => (string)$item->video_url(),
                    'description'  => (string)$item->description()
                ); 
                break;
            }
            case TYPE_PLAYLIST:
            { 
                $json[] = array(
                    'type' => TYPE_PLAYLIST,
                    'uri' => (string)$item->uri(),
                    'title' => (string)$item->title(),
                    'image_url'  => (string)$item->image_url(),
                    'background_image_url'  => (string)$item->background_image_url(),
                    'description'  => (string)$item->description()
                    //'children' => create_playlist_children_list($item)
                );
                break;
            }
            default:
            {
                // ERROR ERROR
            }
        }
    }
    
    return $json;
}

function  create_options_list($category)
{
    $json = array();
    
    // Homepage has only one child - Options category
    $items = $category->children()->visible(); 
    
    foreach($items as $item) 
    {
        $options = $item->children()->visible();
        
        foreach($options as $option) 
        {
            $json[] = (string)$option->title();
        }
        
        //Should be only one child - option
        break;
    }
    
    return $json;    
}

$data = $pages->find(ROOT_NAME)->children()->visible();

$json = array();

foreach($data as $node) {
    
    switch ((string)$node->intendedTemplate())
    {
        case TYPE_CATEGORY:
        {
            $json[] = array(
                'type' => TYPE_CATEGORY,
                'uri' => (string)$node->uri(),
                'title' => (string)$node->title(),
                'children' => create_category_children_list($node)
            );
            break;
        }
        case TYPE_HOMEPAGE:
        { 
            $json[] = array(
                'type' => TYPE_HOMEPAGE,
                'uri' => (string)$node->uri(),
                'live_stream_url' => (string)$node->live_stream_url(),
                'small_image_selected_url' => (string)$node->small_image_selected_url(),
                'small_image_unselected_url' => (string)$node->small_image_unselected_url(),
                'background_image_selected_url' => (string)$node->background_image_selected_url(),
                'background_image_unselected_url' => (string)$node->background_image_unselected_url(),
                'about_description' => (string)$node->about_description(),
                'options' => create_options_list($node)
            );
            break;
        }
        default:
        {  
            // ERROR ERROR
        }
    }
}

echo json_encode($json, JSON_UNESCAPED_SLASHES);

?>
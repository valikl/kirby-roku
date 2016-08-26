<?php

header('Content-type: application/json; charset=utf-8');

function  create_playlist_children_list($item)
{
    $json = array();
    
    $videos = $item->children()->visible()->flip()->paginate(10);
            
    foreach($videos as $video) {

        if ((string)$video->intendedTemplate() != "video")
        {
            // ERROR ERROR
        }

        $json[] = array(
            'TYPE' => "video IN PLAYLIST",
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
  
    if ((string)$category->intendedTemplate() != "category")
    {
        // ERROR ERROR
    }
    
    $items = $category->children()->visible()->flip()->paginate(10);
    
    foreach($items as $item) {
        
        //echo (string)$item->intendedTemplate();
        
        if ((string)$item->intendedTemplate() == "video")
        {
            $json[] = array(
                'TYPE' => "video",
                'title' => (string)$item->title(),
                'background_image_url'  => (string)$item->background_image_url(),
                'side_image_url'  => (string)$item->side_image_url(),
                'video_url'  => (string)$item->video_url(),
                'description'  => (string)$item->description()
            ); 
            continue;
        }
        
        if ((string)$item->intendedTemplate() == "playlist")
        {
            $json[] = array(
                'TYPE' => "PLAYLIST",
                'title' => (string)$item->title(),
                'image_url'  => (string)$item->image_url(),
                'description'  => (string)$item->description(),
                'children' => create_playlist_children_list($category)
            );
        }
    }
    
    return $json;
}

//$data = $pages->find('kabbalah-for-beginners')->children()->visible()->flip()->paginate(10);
$data = $pages->first()->children()->visible()->flip()->paginate(10);

$json = array();

foreach($data as $category) {
   
    $json[] = array(
    'TYPE' => "CATEGORY",
    'title' => (string)$category->title(),
    'children' => create_category_children_list($category)
    );
}

echo json_encode($json, JSON_UNESCAPED_SLASHES);

?>
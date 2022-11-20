<?php


function youtubeVid($url, $width = '640', $height = '360') {
    
    //extract the ID
    preg_match(
            '/[\?\&]v=([^\?\&]+)/',
            $url,
            $matches
        );

    //the ID of the YouTube URL: eLl7Y29eC7o
    $youtube_id = $matches[1];


    //echo the embed code. You can even wrap it in a class
    return '<iframe width="' .$width. '" height="'.$height.'" src="//www.youtube.com/embed/'.$youtube_id.'" frameborder="0" allowfullscreen></iframe>';
}


function img($params = [], $directPath = false) {

    $src = $params['src'];
    $alt = isset($params['alt']) ? 'alt="'.$params['alt'].'"' : '';
    $width = isset($params['width']) ? 'width="'.$params['width'].'"' : '';
    $height = isset($params['height']) ? 'height="'.$params['height'].'"' : '';
    $class = isset($params['class']) ? 'class="'.$params['class'].'"' : '';

    if ($directPath) {
        $newSrc = baseUrl("assets/tinyeditor/filemanager/files/{$src}");
        return "<img {$class} {$width} {$height} src=\"".baseUrl('assets/tinyeditor/filemanager/files/'.$src.'')."\" {$alt} />";
    }
    return "<img {$class} {$width} {$height} src=\"".baseUrl("assets/$src")."\" {$alt} />";
}




function relevantPath($content, $prepare = true)
{
    // If TRUE - replace to relevant URL (site URL)
    if ($prepare) {
        
        $content = str_replace('%RELEVANT_PATH%', baseUrl('assets/tinyeditor/filemanager/files/'), $content ?? '');
        return $content;
    } else {
        // If FALSE - replace to %RELEVANT_PATH%

        // Find images
        $srcReplaced = [];
        // preg_match_all("/src=[\"']([^http|$\/\/].*)[\"']/", $content, $matches);
        preg_match_all("/src=\"(.*)\"/", $content, $matches);

        foreach ($matches[1] as $key => $src) {
            if (strpos($src, 'files')) {
                $srcArr = explode('files/', $src)[1];
                $srcReplaced[] = '%RELEVANT_PATH%'.$srcArr;
            }
        }
        
        $content = preg_replace('/https?[:]\/\/(.+)files\//', '%RELEVANT_PATH%', $content);
        
        // Change image urls
        foreach ($srcReplaced as $key => $src) {
            $content = str_replace($matches[1][$key], $src, $content);
        }

        return $content;
    }

}



// Base64 to image JPEG
function base64_to_jpeg($base64_string, $output_file) {
        
    // Check file format
    $exts = ['jpg', 'jpeg', 'png', 'gif', 'tiff', 'webm'];
    
    // Images array
    $imagesArray = [];
    
    foreach ($base64_string as $base) {
        
        // Find extention in base64
        preg_match('/(?:[A-z]+)/', $base, $extension);
        $ext = !empty($extension) ? $extension[0] : null;

        // Check if not null
        if (!$ext) return false;

        // Check if extension name is valid
        if (!in_array($ext, $exts)) return false;    
        
        $imageName = $output_file . 'image-' . rand() . time() . '.' . $ext;
        $imagesArray[] = $imageName;

        // open the output file for writing
        $ifp = fopen($imageName, 'wb');

        $data = explode(',', $base);

        // we could add validation here with ensuring count( $data ) > 1
        fwrite( $ifp, base64_decode($data[ 1 ]) );

        // clean up the file resource
        fclose($ifp); 
    }  
    
    return $imagesArray;
}

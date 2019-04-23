<?php
$plugins = explode(',', $modx->getOption('ckeditor.extra_plugins'));
if (in_array('base64image', $plugins)) {
    $content = $resource->content;
    $content = preg_replace_callback(
        '/<img[^>]+src="?\'?([^"\']+)"?\'?[^>]*>/i',
        function ($matches) {
            $output = $matches[0];
            if (preg_match('/^data:image\/(\w+);base64,/', $matches[1], $type)) {
                $data = substr($matches[1], strpos($matches[1], ',') + 1);
                $type = strtolower($type[1]); // jpg, png, gif
                if (in_array($type, [ 'jpg', 'jpeg', 'gif', 'png' ])) {
                    $data = base64_decode($data);
                    if ($data !== false) {
                        $path = MODX_ASSETS_PATH . 'images/content/';
                        $path .= date('Y-m-d') . '/';
                        if (!file_exists($path)) {
                            mkdir($path, 0777, true);
                        }
                        $name = substr(md5($data), 0, 12) . ".{$type}";
                        if (!file_exists($path . $name)) {
                            file_put_contents($path . $name, $data);
                        }
                        $url = str_replace(MODX_ASSETS_PATH , MODX_ASSETS_URL, $path . $name);
                        $output = str_replace($matches[1], $url, $matches[0]);
                    }
                }
            }
            
            return $output;
        },
        $content
    );
    $resource->set('content', $content);
    $resource->save();
}
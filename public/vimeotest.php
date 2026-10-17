<?php

$files = file_get_contents('https://api.vimeo.com/videos/1055554938?fields=metadata.connections.pictures.uri');

echo $files;

echo "Hi";

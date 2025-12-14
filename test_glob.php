<?php
$base = __DIR__;
$escaped = str_replace(['[', ']'], ['[[]', '[]]'], $base);
$path = $escaped . '/database/migrations/*.php';
echo "Globbing escaped: $path\n";
$files = glob($path);
print_r($files);

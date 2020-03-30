<?php

ignore_user_abort(true);
set_time_limit(3600);

$main_path = __DIR__;
$zip_file  = 'build.zip';

$excluded  = [
    $main_path . '/.',
    $main_path . '/..',
    $main_path . '/.env',
    $main_path . '/.env.example',
    $main_path . '/package-lock.json',
    $main_path . '/package.json',
    $main_path . '/phpunit.xml',
    $main_path . '/postcss.config.js',
    $main_path . '/README.md',
    $main_path . '/webpack.config.js',
    $main_path . '/zipping.php',
    $main_path . '/.git',
    $main_path . '/.vscode',
    $main_path . '/cache',
    $main_path . '/docs',
    $main_path . '/node_modules',
    $main_path . '/test',
    $main_path . '/vendor'
];
$files = [];

print "Traversing all directories in {$main_path}\n";
$directory = new \RecursiveDirectoryIterator($main_path);

print "Reading all files in {$main_path}\n";
$iterator  = new \RecursiveIteratorIterator($directory);

function isContainExcluded($file, &$excluded)
{
    foreach ($excluded as $key => $value) {
        $res = strpos($file, $value);
        if ($res !== false) {
            return true;
        }
    }

    return false;
}

foreach ($iterator as $info) {
    $file = $info->getPathname();
    if (!isContainExcluded($file, $excluded)) {
        $files[] = str_replace("{$main_path}/", '', $file);
    }
}

if (file_exists($main_path) && is_dir($main_path))
{
    $zip = new ZipArchive();

    if (file_exists($zip_file)) {
        unlink($zip_file);
    }

    if ($zip->open($zip_file, ZIPARCHIVE::CREATE) !== TRUE) {
        die("cannot open <$zip_file>\n");
    }

    foreach ($files as $key => $value) {
        if (is_file($value)) {
            print "Added $value\n";
            $zip->addFile($value);
        }
    }

    print 'Total files: '. count($files) . "\n";

    $zip->close();
}

<?php

$root = dirname(__DIR__);

$scanDirs = [
    'app',
    'routes',
    'resources/views',
    'resources/js',
    'resources/css',
    'config',
];

$allText = '';

foreach ($scanDirs as $dir) {
    $full = "$root/$dir";

    if (!is_dir($full)) {
        continue;
    }

    foreach (new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($full)
    ) as $file) {

        if ($file->isFile()) {
            $allText .= "\n";
            $allText .= $file->getPathname();
            $allText .= "\n";
            $allText .= file_get_contents($file->getPathname());
        }
    }
}

function phpFiles($dir)
{
    global $root;

    $files = [];

    $full = "$root/$dir";

    if (!is_dir($full)) {
        return [];
    }

    foreach (new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($full)
    ) as $file) {

        if (
            $file->isFile() &&
            str_ends_with($file->getFilename(), '.php')
        ) {
            $files[] = $file->getPathname();
        }
    }

    return $files;
}

function auditGroup($title, $dir)
{
    global $allText, $root;

    echo "\n=== $title ===\n";

    foreach (phpFiles($dir) as $file) {

        $class = pathinfo($file, PATHINFO_FILENAME);

        $count = substr_count($allText, $class);

        /*
         * Count 1 usually means:
         * only itself references it
         */

        if ($count <= 1) {
            echo str_replace($root.'/', '', $file);
            echo "\n";
        }
    }
}

/*
|--------------------------------------------------------------------------
| Laravel Class Groups
|--------------------------------------------------------------------------
*/

auditGroup('POSSIBLY UNUSED CONTROLLERS', 'app/Http/Controllers');

auditGroup('POSSIBLY UNUSED MODELS', 'app/Models');

auditGroup('POSSIBLY UNUSED SERVICES', 'app/Services');

auditGroup('POSSIBLY UNUSED HELPERS', 'app/Helpers');

auditGroup('POSSIBLY UNUSED EXPORTS', 'app/Exports');

auditGroup('POSSIBLY UNUSED PROVIDERS', 'app/Providers');

auditGroup('POSSIBLY UNUSED MAIL', 'app/Mail');

auditGroup('POSSIBLY UNUSED EVENTS', 'app/Events');

auditGroup('POSSIBLY UNUSED LISTENERS', 'app/Listeners');

auditGroup('POSSIBLY UNUSED POLICIES', 'app/Policies');

auditGroup('POSSIBLY UNUSED JOBS', 'app/Jobs');

/*
|--------------------------------------------------------------------------
| Blade Views
|--------------------------------------------------------------------------
*/

echo "\n=== POSSIBLY UNUSED BLADE VIEWS ===\n";

$viewDir = "$root/resources/views";

if (is_dir($viewDir)) {

    foreach (new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($viewDir)
    ) as $file) {

        if (
            $file->isFile() &&
            str_ends_with($file->getFilename(), '.blade.php')
        ) {

            $relative = str_replace(
                $root.'/resources/views/',
                '',
                $file->getPathname()
            );

            $viewName = str_replace(
                ['/', '.blade.php'],
                ['.', ''],
                $relative
            );

            if (!str_contains($allText, $viewName)) {

                echo str_replace(
                    $root.'/',
                    '',
                    $file->getPathname()
                );

                echo " => $viewName\n";
            }
        }
    }
}

echo "\nDONE.\n";
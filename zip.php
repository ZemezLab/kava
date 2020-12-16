<?php

$rootPath = realpath('.');

$zip = new ZipArchive();
$zip->open('tourware.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);
$zip->addEmptyDir('tourware');

/** @var SplFileInfo[] $files */
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootPath),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $name => $file)
{
    if (!$file->isDir())
    {
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($rootPath) + 1);

        if (
            substr($relativePath, 0, 6) !== '.github' &&
            substr($relativePath, 0, 4) !== '.git' &&
            substr($relativePath, 0, 5) !== '.idea' &&
            substr($relativePath, 0, 7) !== 'zip.php'
        ) {
            $zip->addFile($filePath, 'tourware\\' . $relativePath);
        }
    }
}

$zip->close();
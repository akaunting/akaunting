<?php

include_once __DIR__ . '/Autoloader.php';

\Complex\Autoloader::Register();


abstract class FilesystemRegexFilter extends RecursiveRegexIterator
{
    protected $regex;
    public function __construct(RecursiveIterator $it, $regex)
    {
        $this->regex = $regex;
        parent::__construct($it, $regex);
    }
}

class FilenameFilter extends FilesystemRegexFilter
{
    // Filter files against the regex
    public function accept()
    {
        return (!$this->isFile() || preg_match($this->regex, $this->getFilename()));
    }
}


$srcFolder = __DIR__ . DIRECTORY_SEPARATOR . 'src';
$srcDirectory = new RecursiveDirectoryIterator($srcFolder);

$filteredFileList = new FilenameFilter($srcDirectory, '/(?:php)$/i');
$filteredFileList = new FilenameFilter($filteredFileList, '/^(?!.*(Complex|Exception)\.php).*$/i');

foreach (new RecursiveIteratorIterator($filteredFileList) as $file) {
    if ($file->isFile()) {
        include_once $file;
    }
}

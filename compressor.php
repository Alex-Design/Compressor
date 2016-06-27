<?php

/* 
 * php compressor.php [overwrite]
 */

/*
 * Reads a file as a string, compresses it and outputs the result.
 */

$compressor = new Compressor();

$result = scandir('FilesToCompress');
$noDotsArray = array_slice($result, 2); 

foreach ($noDotsArray as $individualFile) {
    if (!array_key_exists(1, $argv)) {
        $compressor->readFile($individualFile);
    } else {
        $compressor->readFile($individualFile, $argv[1]);
    }
}

class Compressor {
    
    public function readFile($fileName, $argument = null) {
        
        $fileOutputName = $fileName . '.gz';
        
        $fileToReadContents = $this->readFileContent('FilesToCompress/' . $fileName);
        
        if (file_exists('CompressedFiles/' . $fileOutputName) == true) {
            if ($argument == 'overwrite') {
                $this->createAndWriteFile($fileToReadContents, $fileOutputName);
            } else {
                print_r(PHP_EOL . "\033[31m" . 'File: ' . $fileOutputName .  ' already exists.'. PHP_EOL . "\033[0m");
                print_r("\033[34m" . 'Please run "php batchCompressor.php overwrite" to overwrite.' . PHP_EOL . PHP_EOL . "\033[0m");
            }
        } else {
            $this->createAndWriteFile($fileToReadContents, $fileOutputName);
        }
        
    }
    
    public function readFileContent($fileToRead) {
        $fileContents = file_get_contents($fileToRead, 'r');
        return $fileContents;
    }
    
    public function createAndWriteFile($data, $fileOutputName) {
        $gzData = gzencode($data, 9);
        $filePath = fopen('CompressedFiles/' . $fileOutputName, "w");
        $this->writeFile($filePath, $gzData);
    }
    
    public function writeFile ($filePath, $gzData) {
        fwrite($filePath, $gzData);
        fclose($filePath);
    }
}
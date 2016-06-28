<?php

/* 
 * php compressor.php [overwrite]
 */

/*
 * Reads a file as a string, compresses it and outputs the result.
 */

$currentDirectoryName = 'FilesToCompress';

$compressor = new Compressor();
$compressor->scanRecursively($currentDirectoryName, $argv);

class Compressor {
    
    public function scanRecursively($currentDirectoryName, $argv = []) {
        
        $result = scandir($currentDirectoryName);
        $noDotsArray = array_slice($result, 2);
        if (in_array(".DS_Store", $noDotsArray)) {
            unset($noDotsArray[0]);
        }
        
        foreach ($noDotsArray as $individualLine) {
             
            if (is_dir($currentDirectoryName . '/' . $individualLine) == true) {
                $readableDirectory = $this->amendDirectoryName($currentDirectoryName, $individualLine);
                $this->scanRecursively($readableDirectory, $argv);
            } else {
                if (!array_key_exists(1, $argv)) {
                    $this->readFile($individualLine, $currentDirectoryName);
                } else {
                    $this->readFile($individualLine, $currentDirectoryName, $argv[1]);
                }
            }
        }
    }
    
    public function amendDirectoryName ($currentDirectoryName, $directoryAddition) {
        $newDirectoryName = $currentDirectoryName . '/' . $directoryAddition;
        return $newDirectoryName;
    }
    
    public function scanDirectory ($directoryName) {
        $directoryContents = scandir($directoryName);
        return $directoryContents;
    }
    
    public function readFile($fileName, $currentDirectoryName, $argument = null) {
        
        $fileOutputName = $fileName . '.gz';
        $fileToReadContents = $this->readFileContent($fileName, $currentDirectoryName);

        if (file_exists('CompressedFiles/' . $fileOutputName) == true) {
            if ($argument == 'overwrite') {
                $this->createAndWriteFile($fileToReadContents, $fileOutputName, $currentDirectoryName);
            } else {
                print_r(PHP_EOL . "\033[31m" . 'File: ' . $fileOutputName .  ' already exists.'. PHP_EOL . "\033[0m");
                print_r("\033[34m" . 'Please run "php batchCompressor.php overwrite" to overwrite.' . PHP_EOL . PHP_EOL . "\033[0m");
            }
        } else {
            $this->createAndWriteFile($fileToReadContents, $fileOutputName);
        }
        
    }
    
    public function readFileContent($fileToRead, $currentDirectoryName) {
        $fileContents = file_get_contents($currentDirectoryName . '/' . $fileToRead, 'r');
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
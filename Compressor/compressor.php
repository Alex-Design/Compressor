<?php

/* 
 * php compressor.php [overwrite]
 */

/*
 * Reads a file as a string, compresses it and outputs the result.
 */

$compressor = new Compressor();

if (!array_key_exists(1, $argv)) {
    $compressor->readFile();
} else {
    $compressor->readFile($argv[1]);
}

class Compressor {
    
    public function readFile($argument = null) {
        
        $fileToReadName = 'test.txt';
        $fileOutputName = "result.gz";
        
        $fileToReadContents = $this->readFileContent($fileToReadName);
        
        if (file_exists($fileOutputName)) {
            if ($argument == 'overwrite') {
                $this->createAndWriteFile($fileToReadContents, $fileOutputName);
            } else {
                print_r(PHP_EOL . 'File already exists.'. PHP_EOL);
                print_r('Please run "php batchCompressor.php overwrite" to overwrite it.' . PHP_EOL . PHP_EOL);
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
        $filePath = fopen($fileOutputName, "w");
        $this->writeFile($filePath, $gzData);
    }
    
    public function writeFile ($filePath, $gzData) {
        fwrite($filePath, $gzData);
        fclose($filePath);
    }
}
<?php
/*
 * Reads a file as a string, compresses it and outputs the result.
 */
class Compressor {
    
    public $fileToCompress;
    public $outputFileName;
    public $currentDirectoryName = 'FilesToCompress'; 
    
    public function __construct($fileToCompress = NULL, $outputFileName = NULL) 
    {
        $this->fileToCompress = $fileToCompress;
        $this->outputFileName = $outputFileName;
        
        print_r('The file ' . $fileToCompress . ' will now be compressed.');
        
        if (!$this->outputFileName == NULL && !$this->fileToCompress == NULL) {
            $fileContents = $this->readSpecificFile($fileToCompress);
            $this->createAndWriteFile($fileContents, $fileToCompress);
        } else {
            $this->compressFile($fileToCompress, $outputFileName);
        }
    }
    
    public function compressFile($fileName, $outputFileName) {
        $fileContents = $this->readFileContent($fileName);
        $this->createAndWriteFile($fileContents, $outputFileName);
    }
    
    private function scanRecursively($currentDirectoryName, $argv = []) {
        
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
    
    private function amendDirectoryName ($currentDirectoryName, $directoryAddition) {
        $newDirectoryName = $currentDirectoryName . '/' . $directoryAddition;
        return $newDirectoryName;
    }
    
    private function scanDirectory ($directoryName) {
        $directoryContents = scandir($directoryName);
        return $directoryContents;
    }
    
    private function readFile($fileName, $currentDirectoryName, $argument = null) {
        
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
    
    public function readSpecificFile($fileToRead) {
        $fileContents = file_get_contents($fileToRead, 'r');
        return $fileContents;
    }
    
    private function readFileContent($fileToRead, $currentDirectoryName = NULL) {
        $fileContents = file_get_contents($currentDirectoryName . '/' . $fileToRead, 'r');
        return $fileContents;
    }
    
    private function createAndWriteFile($data, $fileOutputName, $outputLocation = NULL) {
        
        $gzData = gzencode($data, 9);
        
        if ($outputLocation == NULL) {
            $filePath = fopen('CompressedFiles/' . $fileOutputName . '.gz', "w");   
        } else {
            $filePath = fopen($outputLocation . '/' . $fileOutputName . '.gz', "w"); 
        }
        
        $this->writeFile($filePath, $gzData);
    }
    
    private function writeFile($filePath, $gzData) {
        fwrite($filePath, $gzData);
        fclose($filePath);
    }
}
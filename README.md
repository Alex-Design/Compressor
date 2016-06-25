# Compressor
Reads a file as a string, compresses it and outputs the result into a new file.

## Usage
- Download the repository by clicking "clone or download" on the top right, and place it wherever you like.
- Next, open the terminal and type **cd yourfilelocation** where yourfilelocation is where the repository is located.
- Modify the compressor.php so that the values for the following are what you desire:

_$fileToReadName = 'test.txt';_

_$fileOutputName = "result.gz";_
        
(Do not modify anything below the $fileOutputName variable unless you know what you are doing)
- Finally, type **php compressor.php** into the terminal and press enter.

_Optional argument: overwrite_

_To use, type: **php compressor.php overwrite** into the terminal._

- The result will be output in the same folder that you are currently in.

## Upcoming Features
- Batch input/output, recursion
- Testing to check suitability for reading multiple different types of files

## Contributing
- If you have any contributions to make, please leave a comment somewhere or make a pull request, thanks.

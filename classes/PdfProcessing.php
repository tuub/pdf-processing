<?php

/**
 * This class contains all functions needed for th pdf processing interface.
 * On construction it loads the configurations from the config.ini file.
 */
class PdfProcessing
{

    /**
     * The array with configurations.
     */
    var $configs = NULL;

    /**
     * Contructor loading the configuration.
     */
    function __construct ($configs)
    {
        $this->configs = $configs;
    }

    /**
     * Renames a file for security reasons.
     *
     * @param string $filename            
     */
    public function renameFile ($filename, $fileExt)
    {
        return hash($this->configs['hash'], $filename . time()) . $fileExt;
    }

    /**
     * Saves a file and stores the filename and the original name in the
     * session.
     *
     * @param File $file
     * @param string $targetDir
     * @param string $newFileName
     * @return boolean
     */
    public function saveFile ($file, $saveFileName)
    {
        if (!file_exists($this->configs['uploadPath'])) {
            mkdir($this->configs['uploadPath']);
        }
        
        $targetFile = $this->configs['uploadPath'] . $saveFileName;
        
        if (file_exists($targetFile)) {
            $errorMessage = $messages['fileAlreadyExists'];
            
        } elseif (move_uploaded_file($file['tmp_name'], $targetFile)) {
            $_SESSION['uploadFile'] = $targetFile;
            $filename = htmlentities(basename($file['name']));
            $_SESSION['originalFileName'] = $filename;
            
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Creates name and display name of the processed file and saves them to the session.
     * 
     * @param string $fileExt - the file extension
     */
    public function createAndSaveProcessedFileName($fileExt) 
    {        
        $_SESSION['processedFile'] = $this->configs['processedPath'] 
            . $this->addFileSuffix($_SESSION['uploadFile'], $fileExt, '_processed'); 
        
        $_SESSION['processedDisplayName'] = $this->addFileSuffix($_SESSION['originalFileName'], 
            $fileExt, '_processed');
    }
    
    /**
     * Saves the given content as an xmp file.
     *  
     * @param string $content
     */
    public function saveXmpFile($content) 
    {
        if (!file_exists($this->configs['xmpPath'])) {
            mkdir($this->configs['xmpPath']);
        }
        $xmpPath = $this->configs['xmpPath'] . basename($_SESSION['uploadFile'], '.pdf') . '.xmp';
        if (file_put_contents($xmpPath, $content)) {
            $_SESSION['xmpFile'] = $xmpPath; 
        } else {
            $errorMessage = $messages['xmpFileNotSaved'];
            error_log("The .xmp file could not be saved!");
        }
        
    }
    
    /**
     * Adds a suffix to the file name keeping the file extension.
     * 
     * @param string $filename
     * @param string $fileExt
     * @param string $suffix
     * @return string
     */
    private function addFileSuffix($filename, $fileExt, $suffix) 
    {
        return basename($filename, $fileExt) . $suffix . $fileExt;
    }
    
    /**
     * Creates an associative array from metadata fields in a the post.
     * 
     * @return string[]
     */
    public function createMetadataArray() 
    {
        $metadataArray = array();
        foreach ($this->configs['metadataField'] as $field) {
            if (isset($_POST[$field])) {
                $value = trim($_POST[$field]);
                if (!empty($field)) {
                    $metadataArray[$field] = $value;
                }
            }
        }
        return $metadataArray;
    }
    
    /**
     * Creates the arguments for PDF/A conversion.
     *  
     * @param string $level - the compliancy level
     * @param string $mode - the conversion mode
     * @param string[] $metadataArray - additional metadata
     * @return string - the arguments
     */
    public function createPdfaArgs($level, $mode) 
    {
        $metadata = '';
        if (!empty($_SESSION['xmpFile'])) {
            $metadata = $this->configs['metadataArg'] . $_SESSION['xmpFile'] . ' ';
        }
        
        $args = $mode . ' ' . $metadata 
            . $this->configs['pdfLevelArg'] . $level . ' ' 
            . $this->configs['pdfOutputArg'] . $_SESSION['processedFile'] . ' ' 
            . $this->configs['pdfOverwriteArg'] . ' ' 
            . $this->configs['cachefolderArg'] . ' ' 
            . $this->configs['pdfLangArg'] . $lang . ' ' 
            . $_SESSION['uploadFile'];
        
        return $args;
    }

    /**
     * Creates the arguments for PDF/A validation.
     *
     * @param string $level - the compliancy level
     * @return string - the arguments
     */
    public function createPdfaValidateArgs($level)
    {
        $args = ' --analyze '
        . $this->configs['pdfLevelArg'] . $level . ' '
        . $this->configs['cachefolderArg'] . ' '
        . $this->configs['pdfLangArg'] . $lang . ' ' 
        . $_SESSION['uploadFile'];
    
        return $args;
    }
    
    /**
     * Creates the arguments for PDF profile processing.
     * 
     * @param string $profile - the profile file name
     * @return string - the arguments
     */
    public function createPdfProfileArgs($profile)
    {
        $args = $this->configs['pdfProfileArg'] . ' ' 
            . $this->configs['pdfProfilesPath'] . escapeshellarg($profile) . ' '
            . $_SESSION['uploadFile'] . ' ' . $this->configs['pdfOutputArg'] 
            . $_SESSION['processedFile'] . ' ' . $this->configs['pdfOverwriteArg'] . ' '
            . $this->configs['pdfLangArg'] . $lang . ' '
            . $this->configs['cachefolderArg'];
        return $args;
    }

    /**
     * Creates the arguments for free PDF processing.
     *
     * @param string $args - the free args
     * @return string - the arguments
     */
    public function createPdfFreeArgs($freeArgs)
    {
        $args = escapeshellcmd($freeArgs) . ' ' . $this->configs['pdfOutputArg'] 
            . $_SESSION['processedFile'] . ' ' . $this->configs['pdfOverwriteArg'] . ' ' 
            . $this->configs['cachefolderArg'] . ' ' 
            . $this->configs['pdfLangArg'] . $lang . ' '
            . $_SESSION['uploadFile'];
        return $args;
    }
    
    /**
     * Executes the pdf processor with the given arguments.
     * 
     * @param string $args
     * @return string - the pdf processor return value
     */
    public function executePdfProcessing ($args)
    {
        $cmd = $this->configs['pdfProcessor'] . ' ' . $args;
        return shell_exec($cmd);
    }
    
    /**
     * Returns the pdf profile in the profiles directory.
     * 
     * @return array
     */
    public function getPdfProfiles() 
    {
        $profileDir = $this->configs['pdfProfilesPath'];
        if (!is_dir($profileDir)) {
            error_log("The pdf profiles path in the config.ini '$profileDir' is not a valid directory");
            return array();
        }
        $profiles = scandir($profileDir);
        
        $cleanedProfiles = array();
        foreach ($profiles as $val) {
            if ($val != '.' && $val != '..') {
                array_push($cleanedProfiles, $val);
            }
        }
        
        return $cleanedProfiles;
    }
    
    /**
     * Writes a download file in the output buffer.
     * 
     * @param string $path
     * @param string $mimeType
     * @param string $displayName
     */
    public function downloadFile($path, $mimeType, $displayName) 
    {
        $filesize = filesize($path);
        
        header("Content-Type: $mimeType");
        header("Content-Disposition: attachment; filename=$displayName");
        header("Content-Length: $filesize");
        
        readfile($path);
    }
    
    /**
     * Deletes the uploaded and processed files and clears the session.
     */
    public function clearSession() 
    {
        unlink($_SESSION['uploadFile']);
        unlink($_SESSION['processedFile']);
        unlink($_SESSION['xmpFile']);
        session_unset();
    }

    /**
     * Filters a string line by line.
     * 
     * @param string $returnValue
     * @return string - the filtered string
     */
    public function filterReturnValue($returnValue) 
    {
        $filteredValue = '';
        $lines = explode(PHP_EOL, $returnValue);
        foreach ($lines as $line) {
            if (preg_match($this->configs['lineRegex'], $line)) {
                $filteredValue .= $line . PHP_EOL;
            }
        }
        return $filteredValue;
    }
    
    /**
     * Checks if their were no errors in the return summary.
     * 
     * @param string $returnValue
     * @return boolean
     */
    public function returnOk($returnValue) 
    {
        $lines = explode(PHP_EOL, $returnValue);
        $value = "nothing found";
        foreach ($lines as $line) {
            if (preg_match($this->configs['summaryRegex'], $line)) {
                $value = preg_replace($this->configs['summaryRegex'], '$1', $line);
            }
        }
        return strcmp("0", $value) == 0;
    }
}

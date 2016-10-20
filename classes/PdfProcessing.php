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
    function __construct ()
    {
        $this->configs = parse_ini_file("ini/config.ini");
        if (!$this->configs) {
            error_log('The configuration file ini/config.ini could not be loaded!');
        }
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
            $filename = basename($file['name']);
            $_SESSION['uploadFile'] = $targetFile;
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
    public function createAndSaveProcessedFileName($fileExt) {        
        $_SESSION['processedFile'] = $this->configs['processedPath'] 
            . $this->addFileSuffix($_SESSION['uploadFile'], $fileExt, '_processed'); 
        
        $_SESSION['processedDisplayName'] = $this->addFileSuffix($_SESSION['originalFileName'], 
            $fileExt, '_processed');
    }
    

    private function addFileSuffix($filename, $fileExt, $suffix) 
    {
        return basename($filename, $fileExt) . $suffix . $fileExt;
    }
    
    /**
     * Creates the arguments for PDF/A processing.
     *  
     * @param string $type - the processing type
     * @param string $level - the compliancy level
     * @param string $mode - the processing mode
     * @return string - the arguments
     */
    public function createPdfaArgs($type, $level, $mode) 
    {
        $args = $type . ' ' . $mode . ' ' . $this->configs['pdfLevelArg'] . $level . ' ' 
            . $this->configs['pdfOutputArg'] . $_SESSION['processedFile'] . ' '
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
            . $_SESSION['uploadFile'] . ' ' . $_SESSION['processedFile'];
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
        $args = $freeArgs . ' ' . $this->configs['pdfOutputArg'] 
            . $_SESSION['processedFile'] . ' ' . $_SESSION['uploadFile'];
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
        return shell_exec(escapeshellcmd($cmd));
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
            error_log('The pdf profiles path in the config.ini "' . $profileDir . '" is not a valid directory');
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
        session_unset();
    }
}
?>
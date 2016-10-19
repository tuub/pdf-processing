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
    public function renameFile ($filename, $suffix)
    {
        return hash($this->configs['hash'], $filename . time()) . $suffix;
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
        
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            $filename = basename($file['name']);
            $_SESSION['targetFile'] = $targetFile;
            $_SESSION['originalFileName'] = $filename;
            
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * Creates the arguments for PDF/A processing.
     *  
     * @param string $type - the processing type
     * @param string $level - the compliancy level
     * @param string $mode - the processing mode
     * @param string $suffix - the file suffix
     * @return string - the arguments
     */
    public function createPdfaArgs($type, $level, $mode, $suffix) 
    {
        $args = $type . ' ' . $mode . ' ' . $this->configs['pdfLevelArg'] . $level . ' ' 
            . $this->configs['pdfOutputArg'] . $this->configs['processedPath'] 
            . basename($_SESSION['targetFile'], $suffix) . '_processed' . $suffix . ' '
            . $_SESSION['targetFile'];
        return $args;
    }

    /**
     * Creates the arguments for PDF profile processing.
     * 
     * @param string $profile - the profile file name
     * @param string $suffix - the file suffix
     * @return string - the arguments
     */
    public function createPdfProfileArgs($profile, $suffix)
    {
        $args = $this->configs['pdfProfileArg'] . ' ' 
            . $this->configs['pdfProfilesPath'] . escapeshellarg($profile) . ' '
            . $_SESSION['targetFile'] . ' '
            . $this->configs['processedPath']
            . basename($_SESSION['targetFile'], $suffix) . '_processed' . $suffix;
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
    public function getPdfProfiles() {
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
     * Deletes the uploaded file and clears the session.
     */
    public function clearSession() 
    {
        unlink($_SESSION['targetFile']);
        session_unset();
    }
}
?>
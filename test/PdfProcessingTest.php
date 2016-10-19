<?php
require_once 'PHPUnit/Autoload.php';
include_once 'classes/PdfProcessing.php';

class PdfProcessingTest extends PHPUnit_Framework_TestCase
{

    function testCreatePdfaArgs() {
        $processor = new PdfProcessing();
        
        $_SESSION['targetFile'] = 'myFile.pdf';
        $retval = $processor->createPdfaArgs('--analyze', '2a', 
            '--forceconversion_reconvert', '.pdf');
        
        $this->assertEquals('--analyze --forceconversion_reconvert ' 
            . $processor->configs['pdfLevelArg'] . '2a ' 
            . $processor->configs['pdfOutputArg'] . $processor->configs['processedPath'] 
            . 'myFile_processed.pdf ' . 'myFile.pdf', $retval);
        
        session_unset();
    }
    
    
    function testCreatePdfProfileArgs() {
        $processor = new PdfProcessing();
    
        $_SESSION['targetFile'] = 'myFile.pdf';
        $profileFile = 'A file name with spaces.kfpx';
        
        $retval = $processor->createPdfProfileArgs($profileFile, '.pdf');
    
        $this->assertEquals($processor->configs['pdfProfileArg'] . ' '
            . $processor->configs['pdfProfilesPath'] .escapeshellarg($profileFile) . ' '  
            . 'myFile.pdf '
            . $processor->configs['processedPath']. 'myFile_processed.pdf', $retval);
        
        session_unset();
    }
    
}

?>
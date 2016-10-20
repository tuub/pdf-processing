<?php
require_once 'PHPUnit/Autoload.php';
include_once 'classes/PdfProcessing.php';

class PdfProcessingTest extends PHPUnit_Framework_TestCase
{

    
    function testRenameFile()
    {
        $processor = new PdfProcessing();
        
        $filename = '/usr/local/bin/bad_script.sh'; 
        $fileExt = '.pdf';
        
        // Since the renameFile method adds a timestamp to the file, it cannot be tested
        // with 'assertEquals'. Thus, we only check that the path in the name is lost 
        // and that the extension is correct.
        
        $newName = $processor->renameFile($filename, $fileExt);
                
        $this->assertTrue(!empty(preg_match('/.pdf$/', $newName)));
        $this->assertTrue(empty(preg_match('/\//', $newName)));
    }
    
    
    function testCreateAndSaveProcessedFileName() 
    {
        $processor = new PdfProcessing();
        
        $_SESSION['uploadFile'] = '/path/to/bar.pdf';
        $_SESSION['originalFileName'] = 'foo.pdf';
        
        $processor->createAndSaveProcessedFileName('.pdf');
        
        $this->assertEquals($processor->configs['processedPath'] . 'bar_processed.pdf',
            $_SESSION['processedFile']);
        $this->assertEquals('foo_processed.pdf',
            $_SESSION['processedDisplayName']);
     
        session_unset();
    }
    
    
    function testCreatePdfaArgs() 
    {
        $processor = new PdfProcessing();
        
        $_SESSION['uploadFile'] = '/path/to/myFile.pdf';
        $_SESSION['processedFile'] = '/path/to/myFile_processed.pdf';
        $retval = $processor->createPdfaArgs('--analyze', '2a', 
            '--forceconversion_reconvert', '.pdf');
        
        $this->assertEquals('--analyze --forceconversion_reconvert ' 
            . $processor->configs['pdfLevelArg'] . '2a ' 
            . $processor->configs['pdfOutputArg']  
            . '/path/to/myFile_processed.pdf ' . '/path/to/myFile.pdf', $retval);
        
        session_unset();
    }
    
    
    function testCreatePdfProfileArgs() 
    {
        $processor = new PdfProcessing();
    
        $_SESSION['uploadFile'] = '/path/to/myFile.pdf';
        $_SESSION['processedFile'] = '/path/to/myFile_processed.pdf';
        $profileFile = 'A file name with spaces.kfpx';
        
        $retval = $processor->createPdfProfileArgs($profileFile, '.pdf');
    
        $this->assertEquals($processor->configs['pdfProfileArg'] . ' '
            . $processor->configs['pdfProfilesPath'] .escapeshellarg($profileFile) . ' '  
            . '/path/to/myFile.pdf ' . '/path/to/myFile_processed.pdf', $retval);
        
        session_unset();
    }

    
    function testCreatePdfFreeArgs()
    {
        $processor = new PdfProcessing();
        
        $freeArgs = '-foo -bar';
        $_SESSION['processedFile'] = 'foo.pdf';
        $_SESSION['uploadFile'] = 'bar.pdf';
        
        $args = $processor->createPdfFreeArgs($freeArgs);
        
        $this->assertEquals($freeArgs . ' ' . $processor->configs['pdfOutputArg'] 
            . 'foo.pdf bar.pdf', $args);
        
        session_unset();
    }
    
}

?>
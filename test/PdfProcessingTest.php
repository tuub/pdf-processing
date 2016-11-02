<?php
require_once 'PHPUnit/Autoload.php';
include_once 'classes/PdfProcessing.php';

class PdfProcessingTest extends PHPUnit_Framework_TestCase
{

    var $processor = NULL;
    
    function __construct() {
        $this->processor = new PdfProcessing(parse_ini_file("ini/config.ini"));
    }
    
    function testRenameFile()
    {
        $filename = '/usr/local/bin/bad_script.sh'; 
        $fileExt = '.pdf';
        
        // Since the renameFile method adds a timestamp to the file, it cannot be tested
        // with 'assertEquals'. Thus, we only check that the path in the name is lost 
        // and that the extension is correct.
        
        $newName = $this->processor->renameFile($filename, $fileExt);
                
        $this->assertTrue(!empty(preg_match('/.pdf$/', $newName)));
        $this->assertTrue(empty(preg_match('/\//', $newName)));
    }
    
    
    function testCreateAndSaveProcessedFileName() 
    {
        $_SESSION['uploadFile'] = '/path/to/bar.pdf';
        $_SESSION['originalFileName'] = 'foo.pdf';
        
        $this->processor->createAndSaveProcessedFileName('.pdf');
        
        $this->assertEquals($this->processor->configs['processedPath'] . 'bar_processed.pdf',
            $_SESSION['processedFile']);
        $this->assertEquals('foo_processed.pdf',
            $_SESSION['processedDisplayName']);
     
        session_unset();
    }
    
    
    function testCreatePdfaArgs() 
    {
        $_SESSION['uploadFile'] = '/path/to/myFile.pdf';
        $_SESSION['processedFile'] = '/path/to/myFile_processed.pdf';
        $retval = $this->processor->createPdfaArgs('--analyze', '2a', 
            '--forceconversion_reconvert', '.pdf');
        
        $this->assertEquals('--analyze --forceconversion_reconvert ' 
            . $this->processor->configs['pdfLevelArg'] . '2a ' 
            . $this->processor->configs['pdfOutputArg']  
            . '/path/to/myFile_processed.pdf ' 
            . $this->processor->configs['pdfOverwriteArg']  
            . ' /path/to/myFile.pdf', $retval);
        
        session_unset();
    }
    
    
    function testCreatePdfProfileArgs() 
    {
        $_SESSION['uploadFile'] = '/path/to/myFile.pdf';
        $_SESSION['processedFile'] = '/path/to/myFile_processed.pdf';
        $profileFile = 'A file name with spaces.kfpx';
        
        $retval = $this->processor->createPdfProfileArgs($profileFile, '.pdf');
    
        $this->assertEquals($this->processor->configs['pdfProfileArg'] . ' '
            . $this->processor->configs['pdfProfilesPath'] .escapeshellarg($profileFile) . ' '  
            . '/path/to/myFile.pdf ' . $this->processor->configs['pdfOutputArg'] 
            . '/path/to/myFile_processed.pdf '
            . $this->processor->configs['pdfOverwriteArg'], $retval);
        
        session_unset();
    }

    
    function testCreatePdfFreeArgs()
    {
        $freeArgs = '-foo -bar';
        $_SESSION['processedFile'] = 'foo.pdf';
        $_SESSION['uploadFile'] = 'bar.pdf';
        
        $args = $this->processor->createPdfFreeArgs($freeArgs);
        
        $this->assertEquals($freeArgs . ' ' . $this->processor->configs['pdfOutputArg'] 
            . 'foo.pdf ' . $this->processor->configs['pdfOverwriteArg']  
            . ' bar.pdf', $args);
        
        session_unset();
    }
    
    function testCreateMetadataArray()
    {
        $_POST['title'] = "A Title";
        $_POST['creator'] = "foo; bar";
        
        $metadataArray = $this->processor->createMetadataArray();
        
        $this->assertTrue(!empty($metadataArray));
        $this->assertEquals("A Title", $metadataArray['title']);
        $this->assertEquals("foo; bar", $metadataArray['creator']);       
    }
    
}

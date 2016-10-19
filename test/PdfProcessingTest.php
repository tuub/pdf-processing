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
            . $processor->configs['pdfOutputArg'] . '/tmp/pdf_processed/myFile_processed.pdf '
            . '/tmp/pdf_upload/myFile.pdf', $retval);
    }
}

?>
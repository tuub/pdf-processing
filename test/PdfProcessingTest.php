<?php
/**
 * (c) 2017 Technische Universität Berlin
 *
 * This software is licensed under GNU General Public License version 3 or later.
 *
 * For the full copyright and license information, 
 * please see https://www.gnu.org/licenses/gpl-3.0.html or read 
 * the LICENSE.txt file that was distributed with this source code.
 */
?>
<?php
/**
 * A class with unit tests for the class PdfProcessing.
 */

require_once 'PHPUnit/Autoload.php';
include_once 'classes/PdfProcessing.php';

class PdfProcessingTest extends PHPUnit_Framework_TestCase
{

    /**
     * The class to be tested. 
     */
    var $processor = NULL;
    
    /**
     * Instantiates the class to be tested.
     */
    function __construct() {
        $this->processor = new PdfProcessing(
            parse_ini_file("ini/config.ini"),
            parse_ini_file("ini/messages_de.ini")
        );
    }
    
    /**
     * Tests the renaming of files.
     */
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
    
    /**
     * Tests the creation of the name of the processed file
     * and that this name is saved in the session.
     */
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
    
    /**
     * Tests the creation of pdfa validation arguments.
     */
    function testCreatePdfaValidateArgs() 
    {
        $_SESSION['uploadFile'] = '/path/to/myFile.pdf';
        $retval = $this->processor->createPdfaValidateArgs('2a', 'de');
        
        $this->assertEquals(' --analyze ' 
            . $this->processor->configs['pdfLevelArg'] . '2a ' 
            . $this->processor->configs['cachefolderArg'] . ' '            
            . $this->processor->configs['pdfLangArg']
            . ' /path/to/myFile.pdf', $retval);
        
        session_unset();
    }

    /**
     * Tests the creation of pdfa conversion arguments.
     */
    function testCreatePdfaArgs()
    {
        $_SESSION['uploadFile'] = '/path/to/myFile.pdf';
        $_SESSION['processedFile'] = '/path/to/myFile_processed.pdf';
        $retval = $this->processor->createPdfaArgs('2a',
            '--forceconversion_reconvert', 'de');
    
        $this->assertEquals('--forceconversion_reconvert '
            . $this->processor->configs['pdfLevelArg'] . '2a '
            . $this->processor->configs['pdfOutputArg']
            . '/path/to/myFile_processed.pdf '
            . $this->processor->configs['pdfOverwriteArg'] . ' '
            . $this->processor->configs['cachefolderArg'] . ' '
            . $this->processor->configs['pdfLangArg']
            . ' /path/to/myFile.pdf', $retval);
    
        session_unset();
    }
    
    /**
     * Tests the creation of pdf profile arguments. 
     */
    function testCreatePdfProfileArgs() 
    {
        $_SESSION['uploadFile'] = '/path/to/myFile.pdf';
        $_SESSION['processedFile'] = '/path/to/myFile_processed.pdf';
        $profileFile = 'A file name with spaces.kfpx';
        
        $retval = $this->processor->createPdfProfileArgs($profileFile, '.pdf', 'de');
    
        $this->assertEquals($this->processor->configs['pdfProfileArg'] . ' '
            . $this->processor->configs['pdfProfilesPath'] .escapeshellarg($profileFile) . ' '  
            . '/path/to/myFile.pdf ' . $this->processor->configs['pdfOutputArg'] 
            . '/path/to/myFile_processed.pdf '
            . $this->processor->configs['pdfOverwriteArg'] . ' '
            . $this->processor->configs['pdfLangArg'] . ' '
            . $this->processor->configs['cachefolderArg'], $retval);
        
        session_unset();
    }

    /**
     * Tests the cretion of arguments for free execution. 
     */
    function testCreatePdfFreeArgs()
    {
        $freeArgs = '-foo -bar';
        $_SESSION['processedFile'] = 'foo.pdf';
        $_SESSION['uploadFile'] = 'bar.pdf';
        
        $args = $this->processor->createPdfFreeArgs($freeArgs, 'de');
        
        $this->assertEquals($freeArgs . ' ' . $this->processor->configs['pdfOutputArg'] 
            . 'foo.pdf ' . $this->processor->configs['pdfOverwriteArg'] . ' '  
            . $this->processor->configs['cachefolderArg'] . ' '
            . $this->processor->configs['pdfLangArg'] . ' bar.pdf', $args);
        
        session_unset();
    }
    
    /**
     * Tests the creation of the metadata array.
     */
    function testCreateMetadataArray()
    {
        $_POST['title'] = "A Title";
        $_POST['creator'] = "foo; bar";
        
        $metadataArray = $this->processor->createMetadataArray();
        
        $this->assertTrue(!empty($metadataArray));
        $this->assertEquals("A Title", $metadataArray['title']);
        $this->assertEquals("foo; bar", $metadataArray['creator']);       
    }
    
    /**
     * Tests the return string filter function.
     */
    function testFilterReturnValue() 
    {
        $returnValue = 'Progress	88	%\n'
            . 'Progress	89	%\n'
            . 'Progress	90	%\n'
            . 'Hit	PDFA	Syntax problem: Stream dictionary improperly formatted\n'
            . 'Fix	PDFA	PDF/A entry missing\n'
            . 'Hit	PDFA	Syntax problem: Indirect object “endobj” keyword not preceded by an EOL marker\n'
            . 'Progress	100	% Summary\n'
            . 'Errors	1	Syntax problem: Indirect object “endobj” keyword not preceded by an EOL marker\n'
            . 'Errors	159	CMYK used but PDF/A OutputIntent not CMYK\n'
            . 'Errors	239	Annotation has no Flags entry\n'
            . 'Errors	239	Annotation not set to print\n'
            . 'Summary	Corrections	0\n'
            . 'Summary	Errors	653\n'
            . 'Summary	Warnings	0\n'
            . 'Summary	Infos	0\n'
            . 'Finished	/tmp/pdf_upload/29c817fab1ae22089439c41b99932d02.pdf\n'	
            . 'Duration	00:02	';
        
        $expected = 'Fix	PDFA	PDF/A entry missing\n'
            . 'Errors	1	Syntax problem: Indirect object “endobj” keyword not preceded by an EOL marker\n'
            . 'Errors	159	CMYK used but PDF/A OutputIntent not CMYK\n'
            . 'Errors	239	Annotation has no Flags entry\n'
            . 'Errors	239	Annotation not set to print\n'
            . 'Summary	Corrections	0\n'
            . 'Summary	Errors	653\n'
            . 'Summary	Warnings	0\n'
            . 'Summary	Infos	0\n';
            
        $filtered = $this->processor->filterReturnValue($returnValue);
        $this->assertEquals($expected, $filtered);
        
    }
    
    /**
     * Tests the return ok (no errors) function.
     */
    function testReturnOk() 
    {
        $returnValue = 'Progress	88	%\n'
            . 'Progress	89	%\n'
            . 'Summary	Corrections	0\n'
            . 'Summary	Errors	653\n'
            . 'Finished	/tmp/pdf_upload/29c817fab1ae22089439c41b99932d02.pdf\n'
            . 'Duration	00:02	';
        $ok = $this->processor->returnOk($returnValue);
        $this->assertFalse($ok);
        
        $returnValue = 'Progress	88	%\n'
            . 'Progress	89	%\n'
            . 'Summary	Corrections	0\n'
            . 'Summary	Errors	0\n'
            . 'Finished	/tmp/pdf_upload/29c817fab1ae22089439c41b99932d02.pdf\n'
            . 'Duration	00:02	';
        $ok = $this->processor->returnOk($returnValue);
        $this->assertTrue($ok);
            
    }
    
}

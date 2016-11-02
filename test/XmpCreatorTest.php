<?php
require_once 'PHPUnit/Autoload.php';
include_once 'classes/XmpCreator.php';

class XmpCreatorTest extends PHPUnit_Framework_TestCase
{

    var $xmpCreator = NULL;
    
    function __construct() {
        $this->xmpCreator = new XmpCreator(parse_ini_file("ini/xmp_fragments.ini"));
    }
    
    function testCreateDcSubject()
    {
        $valueArray = ['foo', 'bar', 'kilroy'];
        $xmpKeywords = $this->xmpCreator->createDcSubject($valueArray);
        
        $expected = "<dc:subject><rdf:Bag><rdf:li>foo</rdf:li><rdf:li>bar</rdf:li>"
            . "<rdf:li>kilroy</rdf:li></rdf:Bag></dc:subject>";
        
        $this->assertEquals($expected, $xmpKeywords);
    }

    function testCreateDcCreator()
    {
        $valueArray = ['Mozart, Wolfgang Amadeus', 'Kant, Immanuel'];
        $xmpKeywords = $this->xmpCreator->createDcCreator($valueArray);
    
        $expected = "<dc:creator><rdf:Seq><rdf:li>Mozart, Wolfgang Amadeus</rdf:li>"
            . "<rdf:li>Kant, Immanuel</rdf:li></rdf:Seq></dc:creator>";
    
            $this->assertEquals($expected, $xmpKeywords);
    }
    
    function testCreatePdfKeywords()
    {
        $commaSeparatedKeywords = 'foo, bar, kilroy';
        $xmpKeywords = $this->xmpCreator->createPdfKeywords($commaSeparatedKeywords);
    
        $expected = "    <rdf:Description rdf:about='' xmlns:pdf='http://ns.adobe.com/pdf/1.3/'>\n"
            . "      <pdf:Keywords>foo, bar, kilroy</pdf:Keywords>\n"
            . "    </rdf:Description>";
    
        $this->assertEquals($expected, $xmpKeywords);
    }
    
    function testCreateDublinCore() 
    {
        $content = 'some content';
        $result = $this->xmpCreator->createDublinCore($content);
        $expected = "    <rdf:Description rdf:about='' xmlns:dc='http://purl.org/dc/elements/1.1/'>\n"
            . "    some content\n"
            . "    </rdf:Description>";
        
        $this->assertEquals($expected, $result);
    }
        
    function testCreateDcLangTag()
    {        
        $expected = "<rdf:Alt><rdf:li xml:lang='x-default'>content</rdf:li></rdf:Alt>";        
        $result = $this->xmpCreator->createDcLangTag('content');
        
        $this->assertEquals($expected, $result);
    }
    
    function testCreateXmp()
    {
        $xmpContent = array('keywords' => 'foo;bar;', 'creator' => 'A, B; C, D', 'title' => 'My Title');
        
        $expected = "<?xpacket begin='' id='' ?>\n"
            . "<x:xmpmeta xmlns:x='adobe:ns:meta/' x:xmptk='Adobe XMP Core 4.0-c316 44.253921, Sun Oct 01 2006 17:14:39'>\n"
            . "  <rdf:RDF xmlns:rdf='http://www.w3.org/1999/02/22-rdf-syntax-ns#'>\n"
            . "    <rdf:Description rdf:about='' xmlns:pdf='http://ns.adobe.com/pdf/1.3/'>\n"
            . "      <pdf:Keywords>foo,bar</pdf:Keywords>\n"
            . "    </rdf:Description>\n"
            . "    <rdf:Description rdf:about='' xmlns:dc='http://purl.org/dc/elements/1.1/'>\n"
            . "    <dc:subject><rdf:Bag><rdf:li>foo</rdf:li><rdf:li>bar</rdf:li></rdf:Bag></dc:subject>\n"
            . "<dc:creator><rdf:Seq><rdf:li>A, B</rdf:li><rdf:li>C, D</rdf:li></rdf:Seq></dc:creator>\n"
            . "<dc:title><rdf:Alt><rdf:li xml:lang='x-default'>My Title</rdf:li></rdf:Alt></dc:title>\n"
            . "    </rdf:Description>\n"                
            . "  </rdf:RDF>\n"
            . "</x:xmpmeta>";
        
        $result = $this->xmpCreator->createXmp($xmpContent);
        
        $this->assertEquals($expected, $result);
    }
    
    function testCreateXmpEmptyValues()
    {
        $xmpContent = array('keywords' => '', 'creator' => '', 'title' => 'My Title');
    
        $expected = "<?xpacket begin='' id='' ?>\n"
            . "<x:xmpmeta xmlns:x='adobe:ns:meta/' x:xmptk='Adobe XMP Core 4.0-c316 44.253921, Sun Oct 01 2006 17:14:39'>\n"
            . "  <rdf:RDF xmlns:rdf='http://www.w3.org/1999/02/22-rdf-syntax-ns#'>\n"
            . "    <rdf:Description rdf:about='' xmlns:dc='http://purl.org/dc/elements/1.1/'>\n"
            . "    <dc:title><rdf:Alt><rdf:li xml:lang='x-default'>My Title</rdf:li></rdf:Alt></dc:title>\n"
            . "    </rdf:Description>\n"
            . "  </rdf:RDF>\n"
            . "</x:xmpmeta>";
    
        $result = $this->xmpCreator->createXmp($xmpContent);

        $this->assertEquals($expected, $result);
    }

    function testCreateXmpEmptyArray()
    {
        // Case 1: all empty
        $xmpContent = array();
        $expected = "";
        $result = $this->xmpCreator->createXmp($xmpContent);
        $this->assertEquals($expected, $result);
        
        // Case 2: all values empty
        $xmpContent = array('keywords' => '', 'creator' => '');
        $result = $this->xmpCreator->createXmp($xmpContent);
        $this->assertEquals($expected, $result);
        
    }
    
}

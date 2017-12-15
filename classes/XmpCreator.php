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
 * A class for the creation of am xmp file with additional metadata.
 *
 */
class XmpCreator
{
    
    /**
     * The array with xmp configurations.
     */
    var $xmpConfigs = NULL;
    
    /**
     * Contructor loading the configuration.
     */
    function __construct ($xmpConfigs)
    {
        $this->xmpConfigs = $xmpConfigs;
    }
    
    /**
     * Creates an xmp string out of the values in the associative content array.
     * 
     * @param $contentArray
     * @return string
     */
    public function createXmp($contentArray) 
    {        
        $dublinCore = "";
        $xmpContent = "";
        
        if (empty(array_filter($contentArray))) {
            return "";
        }
        
        foreach ($contentArray as $key => $value) {
            if (empty($value)) {
                continue;
            }
            switch($key) {
                case "keywords":
                    $keywordArray = array_filter(explode(';', $value));
                    $xmpContent .= $this->createPdfKeywords(join(',', $keywordArray)) . "\n";
                    $dublinCore .= $this->createDcSubject($keywordArray) . "\n";
                    break;
                    
                case "creator":
                    $creatorArray = explode(';', $value);
                    $dublinCore .= $this->createDcCreator($creatorArray) . "\n";
                    break;
                    
                default:
                    $langTag = $this->createDcLangTag($value);
                    $dublinCore .= $this->packDynamicTag('xmpDcTag', $key, $langTag);
            }
        }
        
        $content = $xmpContent . $this->createDublinCore($dublinCore);
        return $this->packContent('xmp', $content);
    }
    
    /**
     * Creates a dublin core parent tag with content.  
     * 
     * @param string $content
     * @return string
     */
    public function createDublinCore($content) 
    {
        return $this->packContent('xmpDublinCore', $content);
    }
    
    /**
     * Creates a dc subject tag with all the values in the array.
     * 
     * @param $valueArray
     * @return string
     */
    public function createDcSubject($valueArray)
    {
        $content = $this->createRdfSet('Bag', $valueArray);
        return $this->packDynamicTag('xmpDcTag', 'subject', $content);
    }

    /**
     * Creates a dc creator tag with all the values in the array.
     *
     * @param $valueArray
     * @return string
     */
    public function createDcCreator($valueArray)
    {
        $content = $this->createRdfSet('Seq', $valueArray);
        return $this->packDynamicTag('xmpDcTag', 'creator', $content);
    }
    
    /**
     * Creates a rdf description section with the pdf keywords.
     * 
     * @param string $commaSeparatedKeywords
     * @return string
     */
    public function createPdfKeywords($commaSeparatedKeywords) 
    {
        return $this->packContent('xmpPdfKeywords', $commaSeparatedKeywords);
    }
    
    /**
     * Creates an rdf set (Seq or Bag) with rdf:li entries for all values in the array.
     *
     * @param $type - the rdf type, 'Seq' or 'Bag'
     * @param $valueArray - the values
     * @return string
     */
    public function createRdfSet($type, $valueArray)
    {
        $xmpString = '';
        
        foreach ($valueArray as $value) {
            $xmpString .= $this->packDynamicTag('xmpRdfTag', 'li', $value);
        }
        
        return $this->packDynamicTag('xmpRdfTag', $type, $xmpString);    
    }
    
    /**
     * Creates a dc language tag.
     *  
     * @param string $content
     * @return string
     */
    public function createDcLangTag($content)
    {
        return $this->packContent('xmpAlt', $content);
    }
        
    /**
     * Packs the given content in a configured an xmp context.
     * 
     * @param string $config    the config key
     * @param string $content   the content
     * @return string
     */
    private function packContent($config, $content) 
    {
        return sprintf($this->xmpConfigs[$config], trim($content));
    }
    
    /**
     * Packs the given content into a configured tag of a certain type.
     * 
     * @param string $type      the tag type key
     * @param string $tag       the tag name
     * @param string $content   the content 
     * @return string
     */
    private function packDynamicTag($type, $tag, $content) 
    {
        return sprintf($this->xmpConfigs[$type], $tag, trim($content), $tag);
    }
        
}

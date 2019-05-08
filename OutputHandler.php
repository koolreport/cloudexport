<?php
/**
 * OutputHandler class
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
namespace koolreport\cloudexport;
use \koolreport\core\Utility;

/**
 * OutputHandler class
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */class OutputHandler
{
    /**
     * The exported content
     * 
     * @param string $content The exported content
     */
    protected $content;

    /**
     * Constructor
     * 
     * @param string $content The exported content
     */
    public function __construct($content)
    {
        $this->content = $content;
    }
    
    /**
     * Return the mime type of filename
     * 
     * @param string $filename The name of the file you want to export to
     * 
     * @return string The mime type of the file
     */
    protected function mimeType($filename)
    {
        $dotpos =strrpos($filename, ".");
        $ext = strtolower(substr($filename, $dotpos+1));
        $map =array(
            "pdf"=>"application/pdf",
            "png"=>"image/png",
            "jpg"=>"image/jpeg",
            "bmp"=>"image/bmp",
            "tiff"=>"image/tiff",
            "gif"=>"image/gif",
            "ppm"=>"image/x-portable-pixmap",
        );
        return Utility::get($map, $ext);
    }
    
    /**
     * Push the file to browser
     * 
     * @param string $filename      The filename you want to push to browser
     * @param bool   $openOnBrowser If true the file will be open instead of downloaded
     * 
     * @return OutputHandler Return itself for cascade
     */
    public function toBrowser($filename,$openOnBrowser=false)
    {

        $disposition = "attachment";
        if (gettype($openOnBrowser)=="string") {
            $disposition = $openOnBrowser;
        } else if ($openOnBrowser) {
            $disposition = "inline";
        }
                
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Type: ".$this->mimeType($filename));
        header("Content-Disposition: $disposition; filename=\"$filename\"");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " . strlen($this->content));
        
        echo $this->content;

        return $this;
    }

    /**
     * Return content in base 64
     * 
     * @return string The content in base64
     */
    public function toBase64()
    {
        return base64_encode($this->content);
    }

    /**
     * Save the file to local drive
     * 
     * @param string $filename The filename containing path to location to store the file
     * 
     * @return OutputHandler Return itself for cascade
     */
    public function saveAs($filename)
    {
        if (file_put_contents($filename, $this->content)===false) {
            throw new \Exception("Could not save file $filename");
        }
        return $this;
    }
}

<?php
/**
 * FileHandler class
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
 * FileHandler class
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
class FileHandler
{
    /**
     * The path to exported file
     * 
     * @param string $path The path to exported file
     */
    protected $path;
    
    /**
     * Constructor
     * 
     * @param string $path The path to exported file
     */
    public function __construct($path)
    {
        $this->path = $path;
    }
    
    /**
     * Get the mime type
     * 
     * @param string $filename The filename
     * 
     * @return string The mime type
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
     * @param bool   $openOnBrowser If true the file will be open instead 
     *                              of downloaded
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
        
        $source = realpath($this->path);
        
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Type: ".$this->mime_type($filename));
        header("Content-Disposition: $disposition; filename=\"$filename\"");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " . filesize($source));
        
        $file = @fopen($source, "rb");
        if ($file) {
            while (!feof($file)) {
                print(fread($file, 1024*8));
                flush();
                if (connection_status()!=0) {
                    @fclose($file);
                    die();
                }
            }
            @fclose($file);
        }
        return $this;
    }

    /**
     * Return content in base 64
     * 
     * @return string The content in base64
     */    
    public function toBase64()
    {
        $source = realpath($this->path);
        if (is_file($source)) {
            return base64_encode(file_get_contents($source));
        }
    }

    /**
     * Save the file to local drive
     * 
     * @param string $filename The filename containing path to location
     *                         to store the file
     * 
     * @return OutputHandler Return itself for cascade
     */
    public function saveAs($filename)
    {
        if (copy($this->path, $filename)) {
            return $this;
        } else {
            throw new \Exception("Could not save file $filename");
        }
    }
}

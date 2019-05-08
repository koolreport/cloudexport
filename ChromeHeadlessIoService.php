<?php
/**
 * ChromeHeadlessIoService class
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */

namespace koolreport\cloudexport;

/**
 * ChromeHeadlessIoService class
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
class ChromeHeadlessIoService
{
    /**
     * Parameters for ChomeHeadless.io service
     * 
     * @param array  $params The parameters
     */
    protected $settings;

    /**
     * Parameters for ChomeHeadless.io service
     * 
     * @param string $html   The html that will be sent to export
     * @param array  $params The parameters
     */
    public function __construct($html, $authentication)
    {
        $this->authentication = $authentication;
        $this->settings = [
            'html' => $html,
            'resourcePatterns' => [
                [
                    "regex" => '~((KoolReport.load.resources|KoolReport.widget.init)\([^\)]*)["\']([^"\']+css|[^"\']+js|css[^"\']+|js[^"\']+|(?![^"\',\)\[\]\:]*(css|js))[^"\',\)\[\]\:]*)["\']~',
                    "replace" => "{group1}'{group3}'",
                    "urlGroup" => "{group3}"
                ]
            ]
        ];
    }

    /**
     * Set export setting
     * 
     * @param array $settings The setting for export
     * 
     * @return $this
     */
    public function settings($settings)
    {
        $this->settings = array_merge($this->settings, $settings);
        return $this;
    } 

    /**
     * Export to pdf
     * 
     * @param array $options The option for pdf
     * 
     * @return OutputHandler The output content
     */
    public function pdf($options = [])
    {
        return $this->exportTo('pdf', $options);
    }

    /**
     * Export to jpg
     * 
     * @param array $options The option for jpg
     * 
     * @return OutputHandler The output content
     */
    public function jpg($options = [])
    {
        return $this->exportTo('jpg', $options);
    }

    /**
     * Export to png
     * 
     * @param array $options The option for png
     * 
     * @return OutputHandler The output content
     */
    public function png($options = [])
    {
        return $this->exportTo('png', $options);
    }

    /**
     * Export to pdf
     * 
     * @param string $format  Format you want to export to "pdf","jpg"
     * @param array  $options The option for pdf
     * 
     * @return OutputHandler The output content
     */
    public function exportTo($format, $options = [])
    {
        $service = new \chromeheadlessio\Service($this->authentication);
        $exportContent = $service
            ->export($this->settings)
            ->{$format}($options)
            ->toString();
        return new OutputHandler($exportContent);
    }
}
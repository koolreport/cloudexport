<?php
/**
 * Service Hub
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
 * Service Hub
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
class ServiceHub
{
    /**
     * The html content of report
     * 
     * @param string $html The html content of report
     */
    protected $html;

    /**
     * Construction
     * 
     * @param string $html The html content of report
     */
    public function __construct($html)
    {
        $this->html = $html;
    }


    /**
     * Use ChromeHeadless.io service
     * 
     * @param array $settings The array contain settings for Chromeheadles.io service
     * 
     * @return ChromeHeadlessIoService The ChromeHeadlessIo service object
     */
    public function chromeHeadlessio($authentication = "", $serviceHost = null)
    {
        $service = new ChromeHeadlessIoService($this->html, $authentication);
        $service->settings([
            'serviceHost' => $serviceHost
        ]);
        return $service;
    }


    public function khtml($authentication = "", $serviceHost = null)
    {
        $service = new ChromeHeadlessIoService($this->html, $authentication);
        $service->settings([
            'engine' => 'wkhtmltopdf',
            'serviceHost' => $serviceHost
        ]);
        return $service;
    }

    public function phantomjs($authentication = "", $serviceHost = null)
    {
        $service = new ChromeHeadlessIoService($this->html, $authentication);
        $service->settings([
            'engine' => 'phantomjs',
            'serviceHost' => $serviceHost
        ]);
        return $service;
    }
}
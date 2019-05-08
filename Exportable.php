<?php
/**
 * This file contain trait for KoolReport to export PDF and other using Cloud Service
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
 * Trait for KoolReport to export PDF and other using Cloud Service
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
trait Exportable
{
    /**
     * Send the report content in html to ServiceHub
     * 
     * @param string $view The view you want to export, if no view is specified,
     *                     we we default view of report
     * 
     * @return ServiceHub The serice hub entrance
     */
    public function cloudExport($view=null)
    {
        return new ServiceHub($this->render($view, true));
    }  
}

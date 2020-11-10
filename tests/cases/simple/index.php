<?php
require_once "../../../../core/autoload.php";
require_once "Report.php";

$report = new Report;
//$report->run()->render();
$report->run()->cloudExport()
->chromeHeadlessio("token-key")
->pdf(array(
    "displayHeaderFooter"=>true,
    "headerTemplate"=>"<div style='font-size:14px !important'>Sale Report</div>",
    "footerTemplate"=>"<span class='pageNumber'  style='font-size:14px !important'></span>",
))
->toBrowser("test.pdf");
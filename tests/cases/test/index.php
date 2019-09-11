<?php
require_once "../../../../autoload.php";
require_once "MyReport.php";

$report = new MyReport;
$report->run()
->cloudExport("MyReportPDF")
->chromeHeadlessio("token-key")
->pdf()
->toBrowser("myreport.pdf");
# Introduction

`CloudExport` helps to export your report to PDF or other format using export service providers. [ChromeHeadless.io](https://chromeheadless.io) is the first cloud service we implemented for CloudExport. ChromeHeadless.io is developed by us so you can assure the compatibility as well as the quality of result.

# Installation

## By downloading .zip file

1. [Download](https://www.koolreport.com/packages/cloudexport)
2. Unzip the zip file
3. Copy the folder `cloudexport` into `koolreport` folder so that look like below

```bash
koolreport
├── core
├── cloudexport
```

## By composer

```
composer require koolreport/cloudexport
```

# ChromeHeadless.io

## Overview

__ChomeHeadless.io__ is an online service helps to convert HTML to PDF and other image format. Running on highly optimized hardware and software, the ChomeHeadless.io will save your time in installing headless browsers like Phantomjs or Google Chrome. It also saves you server resources which you may reserve for other crucial tasks. The Chromeheadless.io is in beta version so all are free.

## Get Token Key

1. Register an account in [ChromeHeadless.io](https://chromeheadless.io). An email with title _"Welcome to ChromeHeadless.io"_ will be sent to you in few minutes after your sign up.
2. Use account credential in welcome email to log in our system.
3. Go to [tokens management](https://chromeheadless.io/account/tokens) page
4. Hit `Generate` button to generate token key.

## Example

__MyReport.php__

```
class MyReport extends \koolreport\KoolReport
{
    //Register cloud export service in your report
    use \koolreport\cloudexport\Exportable;
}
```

__MyReportPDF.view.php__

```html
<html>
    <body>
        <h1>This is my first export using Chromeheadless.io</h1>
        <p>Chromeheadless.io save your time and resources in exporting html, report to PDF.</p>
    </body>
</html>
```

__index.php__

```
require_once "../koolreport/core/autoload.php";
require_once "MyReport.php";

$report = new MyReport;
$report->run()
->cloudExport("MyReportPDF")
->chromeHeadlessio("token-key")
->pdf()
->toBrowser("myreport.pdf");
```

## Export engines

ChromeHeadless.io has two pdf export engines which are headless chrome and wkhtmltopdf. Here's an example to use either of them:

```
$report->run()
->cloudExport("MyReportPDF")
->chromeHeadlessio("token-key")
->pdf($chromePDFOptions)
->toBrowser("myreport.pdf");

$report->run()
->cloudExport("MyReportPDF")
->khtml("token-key")
->pdf($khtmlPDFOption)
->toBrowser("myreport.pdf");
```

Headless chrome has more features but wkhtmltopdf is faster for big files.

## Extra settings

You may add some extra settings to guide ChromeHeadless.io to load your page.

```
require_once "../koolreport/core/autoload.php";
require_once "MyReport.php";

...
->chromeHeadlessio("token-key")
->settings([
    "waitUntil"=>"load"
])
...
```

|Name|Type|Default|Description|
|---|---|---|---|
|`timeout`|number|30|Maximum navigation time in milliseconds, defaults to 30 seconds, pass 0 to disable timeout.|
|`waitUntil`|string|"load"|When to consider navigation succeeded. Other options are `"domcontentloaded"` page finished when all DOM is loaded; `"networkidle0"` page finished when there are no more than 0 network connections for at least 500 ms; `"networkidle2"` page finished when  there are no more than 2 network connections for at least 500 ms.|


## Export options

### PDF for headless chrome engine

The `pdf()` method will help to generate pdf file. It takes an array as parameter defining options for your PDF. Below are available options.

|Name|Type|Default|Description|
|---|---|---|---|
|`scale`|number|1|Scale of the webpage rendering. Defaults to 1. Scale amount must be between 0.1 and 2|
|`displayHeaderFooter`|bool|false|Display header and footer.|
|`headerTemplate`|string||HTML template for the print header. Should be valid HTML markup with following classes used to inject printing values into them: `pageNumber` current page number; `totalPages` total pages in the document; |
|`footerTemplate`|string||HTML template for the print footer. Should use the same format as the `headerTemplate`|
|`printBackground`|bool|false|Print background graphics.|
|`landscape`|bool|false|Paper orientation.|
|`pageRanges`|string||Paper ranges to print, e.g., '1-5, 8, 11-13'. Defaults to the empty string, which means print all pages.|
|`format`|string||Paper format. If set, takes priority over width or height options. Defaults to 'Letter'.|
|`width`|string/number||Paper width, accepts values labeled with units.|
|`height`|string/number||Paper height, accepts values labeled with units.|
|`margin`|object||Paper margins, defaults to none. It has 4 sub properties: `top`, `right`, `bottom`, `left` which can take number or string with units|

All options could be found at this link [Headless Chrome pdf options](https://github.com/GoogleChrome/puppeteer/blob/master/docs/api.md#pagepdfoptions)

__Example:__

```
...
->pdf([
    "scale"=>1,
    "format"=>"A4",
    "landscape"=>true
])
...
```

### PDF for wkhtmltopdf engine

All options could be found at this link, section Global Options [Wkhtmltopdf Docs](https://wkhtmltopdf.org/usage/wkhtmltopdf.txt)

__Example:__

```
...
->pdf([
    "--collate"=>true,
    "--page-size"=>"A4",
    "--orientation"=>"Landscape",
    "--margin-top"=>"100px"
])
...
```

### PDF options in view file for both headless chrome and wkhtmltopdf engines

Some pdf options could be set directly in the PDF view file instead of pdf() method.

#### header and footer

In the view file, use header and footer tags to set pdf's header and footer template:

__Example:__

```
<!-- Headless chrome pdf template -->
<header>
    <div id="header-template" 
        style="font-size:10px !important; color:#808080; padding-left:10px">
        <span>Header: </span>
        {date}
        {title}
        {url}
        {pageNumber}
        {totalPages}
        <span id='pageNum' class="pageNumber"></span>
        <img src='http://www.chromium.org/_/rsrc/1438879449147/config/customLogo.gif?revision=3' />
    </div>
</header>
<footer>
...
</footer>
```
Headless chrome: If either header or footer tag exists, pdf options' displayHeaderFooter will be true. PDF options' headerTemplate and footerTemplate options take priority over view file's header and footer tags. With header and footer tags, if there's no font-size style, a default style "font-size:10x" is used. Header and footer tags supports place holders like {date}, {title}, etc and img tag with link-type src. For img tag pdf options' headerTemplate and footerTemplate only support base64-type src.

```
<!-- Wkhtmltopdf pdf template -->
<header>
    <div>
        {page}{frompage}{topage}{webpage}{section}{subsection}{date}{isodate}{time}{title}{doctitle}{sitepage}{sitepages}
    </div>
</header>
<footer>
...
</footer>
```

Wkhtmltopdf: The exact html content of the header and footer tags including img tags will be used as pdf header and footer with some substituted variables.

#### margin

In the view file, use the body tag's margin style to set pdf margin:

__Example:__

```
//MyReportPDF.view.php
<body style='margin: 1in 0.5in 1in 0.5in'>
...
</body>

```
If either header or footer tag exists but there's no body's margin top or bottom, a default margin top or bottom of 1 inch will be used

#### No template option

If you don't have any header/footer/margin in your template files, you could speed up pdf generating with `noTemplateOption` property:

```
...
->pdf([
    "noTemplateOption"=>true,
    ...
])
...
```

### JPG for headless chrome

The `jpg()` help to generate JPG file. It take an array as parameter defining options for your JPG. Below are list of properties:

|Name|Type|Default|Description|
|---|---|---|---|
|`quality`|number||The quality of the image, between 0-100.|
|`fullPage`|bool|false|When true, takes a screenshot of the full scrollable page.|
|`clip`|object||An object which specifies clipping region of the page. Should have the following fields: `x` is the x-coordinate of top-left corner of clip area, `y` is y-coordinate of top-left corner of clip area, `width` is the width of clipping area and `height` is the height of clipping area.|
|`omitBackground`|bool|false|Hides default white background and allows capturing screenshots with transparency. |
|`encoding`|string|"binary"|The encoding of the image, can be either `base64` or `binary`|

__Example:__

```
...
->jpg([
    "quality"=>80
    "clip"=>[
        "x"=>100,
        "y"=>100,
        "width"=>500,
        "height"=>1000,
    ]
])
...
```


### PNG for headless chrome

The `png()` help to generate PNG file. It take an array as parameter defining options for your PNG. Below are list of properties:

|Name|Type|Default|Description|
|---|---|---|---|
|`fullPage`|bool|false|When true, takes a screenshot of the full scrollable page.|
|`clip`|object||An object which specifies clipping region of the page. Should have the following fields: `x` is the x-coordinate of top-left corner of clip area, `y` is y-coordinate of top-left corner of clip area, `width` is the width of clipping area and `height` is the height of clipping area.|
|`omitBackground`|bool|false|Hides default white background and allows capturing screenshots with transparency. |
|`encoding`|string|"binary"|The encoding of the image, can be either `base64` or `binary`|

__Example:__

```
...
->png([
    "clip"=>[
        "x"=>100,
        "y"=>100,
        "width"=>500,
        "height"=>1000,
    ]
])
...
```


## Get results

In all above examples we use method `toBrowser()` to send the file to browser for user to download. Here are all options:

|Method|Return|Description|
|---|---|---|
|`toBrowser($filename,$openOnBrowser)`||Force user to download file or open the file on browser if `$openOnBrowser` is set to `true`|
|`toString()`|string|Return filename as string|
|`toBase64()`|string|Return content of file in base64|
|`saveAs($path)`||Save the file to specific location|

__Examples:__

```
$report->run()
->cloudExport("MyReportPDF")
->chromeHeadlessio("token-key")
->pdf()
->saveAs("../stores/myreport.pdf");
```
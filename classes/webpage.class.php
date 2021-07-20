<?php
/**
* Creates an HTML webpage using the given params
* 
* @author Nicholas Coyles
* 
*/
abstract class WebPage {
 private $main; 
 private $pageStart;
 protected $header; 
 private $css; 
 private $footer; 
 private $pageEnd;

 /**
 *
 * Constuctor for the page
 * @param $pageTitle - A string to appear as web page title
 * @param $css - link for a css file
 * @param $pageHeading1 - a string to appear as an <h1>
 * @param $footerText - footer text should include any html tags
 *
 */
public function __construct($pageTitle, $pageHeading1, $footerText) {
   $this->main = "";
   $this->set_css();
   $this->set_pageStart($pageTitle,$this->css);
   $this->set_header($pageHeading1);
   $this->set_footer($footerText);
   $this->set_pageEnd();
 }

/**
 * Creates the head for the page adding title and css
 * 
 * @param $pageTitle - A string to appear as web page title
 * @param $css - link for a css file
 * @access private
 */
private function set_pageStart($pageTitle,$css) {
   $this->pageStart = <<<PAGESTART
<!DOCTYPE html>
<html lang="en">
<head>
 <meta name="viewport" content="width=device-width, initial-scale=1,charset="utf-8" />
 <title>$pageTitle</title>
 <link rel="stylesheet" href="$css">
</head>
<body>
PAGESTART;
 }

/**
 * Sets the path for the CSS file
 * 
 * @access private
 */
 private function set_css() {
   $this->css = BASEPATH.CSSPATH; 
 }

/**
 * Creates the header for the page
 * @param $pageHeading1 - a string to appear as an <h1>
 * @access protected
 */
 protected function set_header($pageHeading1) {
   $this->header = <<<HEADER
<header>
 <h1>$pageHeading1</h1>
</header>
HEADER;
 }

/**
 * Creates the main section for the page
 * @param $main - the contents to appear in <main>
 * @access private
 */
 private function set_main($main) {
   $this->main = <<<MAIN
<main>
<div class="wrapper">
  <article class="text">
 $main
 </article>
 </div>
</main>
MAIN;
 }

/**
 * Creates the footer
 * 
 * @param $footerText - footer text should include any html tags
 * @access private
 */
 private function set_footer($footerText) {
   $this->footer = <<<FOOTER
<footer>
 $footerText
</footer>
FOOTER;
 }

/**
 * Adds the end tags to the page
 * 
 * @access private
 */
 private function set_pageEnd() {
   $this->pageEnd = <<<PAGEEND
</body>
</html>
PAGEEND;
 }

/**
 * Adds the text contents to the main body
 * 
 * @param $text - text contents 
 * @access public
 */
 public function addToBody($text) {
   $this->main .= $text;
 }

/**
 * Gets all the contents that has been created in the webpage
 * 
 * @return object retuens all the webpage contents
 * @access public
 */
 public function get_page() {
   $this->set_main($this->main);
   return 
     $this->pageStart.
     $this->header.
     $this->main.
     $this->footer.
     $this->pageEnd; 
 }
}
?>
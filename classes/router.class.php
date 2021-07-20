<?php
/**
* This router will return the documentation or about page
* 
* @author Nicholas Coyles
*
*/
class Router {
  private $page;
  private $type = "HTML";

/**
 * Shows a human readable HTML page or a client/machine readable JSON page
 * @param $pageType - can be "documentation" or "about"
 * 
 * @access public
 */
 public function __construct($recordset) {
   $url = $_SERVER["REQUEST_URI"];
   $path = parse_url($url)['path'];

   $path = str_replace(BASEPATH,"",$path);
   $pathArr = explode('/',$path);
   $path = (empty($pathArr[0])) ? "documentation" : $pathArr[0];

   ($path == "api") 
     ? $this->api_route($pathArr, $recordset) 
     : $this->html_route($path);

}

/**
* Creates a page containing JSON data
* @access public
*/
public function api_route($pathArr, $recordset) {
   $this->type = "JSON";
   $this->page = new JSONpage($pathArr, $recordset);
}

 /**
 * Creates the HTML page adding the page info and headings
 * @access public
 */
public function html_route($path) {
  $ini['routes'] = parse_ini_file("config/routes.ini",true);
  $pageInfo = isset($path, $ini['routes'][$path]) ? $ini['routes'][$path] : $ini['routes']['error'];

  $this->page = new WebPageWithNav($pageInfo['title'], $pageInfo['heading1'], $pageInfo['footer']);
  $this->page->addToBody($pageInfo['text']);
 }
 
 /**
 * Gets the type of page either HTML or JSON and returns the result
 * 
 * @access public
 * @return string the type of page being returned 
 */

public function get_type() {
  return $this->type ; 
}

 /**
 * Produces the html page
 * @access public
 * @return object the HTML or JSON page that has been created is returned  
 */
public function get_page() {
  return $this->page->get_page(); 
  }
}
?>
<?php
/**
* This class sets aprroriate headers
* 
* @author Nicholas Coyles
*
*/
class View {
/**
 * Contructs the page
 * @param $page - contains either HTML or JSON page
 * 
 * @access public
 */
 public function __construct($page) {
   $page->get_type() == "JSON" 
     ? $this->JSONheaders()
     : $this->HTMLheaders();

     echo $page->get_page();
 }
/**
 * JSON headers are created
 * 
 * @access private
 */
 private function JSONheaders() {
   header("Access-Control-Allow-Origin: *"); 
   header("Content-Type: application/json; charset=UTF-8"); 
   header("Access-Control-Allow-Methods: GET, POST");
 }
/**
 * HTML headers are created
 * 
 * @access private
 */
 private function HTMLheaders() {
   header("Content-Type: text/html; charset=UTF-8");
 }
}
?>
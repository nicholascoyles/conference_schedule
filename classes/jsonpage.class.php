<?php
/**
* Creates a JSON page based on the parameters
* PHP version 5.6
* @author Nicholas Coyles
*/
class JSONpage {
private $page; 
private $recordset;
private $count;
private $data;
private $status;
private $msg;
private $total;
private $nextPage;

/**
 * __construct  creates the structure for the json page,
 * with an array holding the cases for each of the endpoints
 * endings and setting up the connection to the recordset.
 * 
 * @param $pathArr - an array containing the route information
 * @param $recordset - stores data within the mySQLite database
*/
public function __construct($pathArr, $recordset) {
  $this->recordset = $recordset;
  $path = (empty($pathArr[1])) ? "api" : $pathArr[1];

  switch ($path) {
    case 'api':
      $this->page = $this->json_welcome();
      break;
    case 'authors':
      $this->page = $this->json_authors();
      break;
    case 'slots':
      $this->page = $this->json_slots();
      break;
    case 'chairs':
      $this->page = $this->json_chairs();
      break;
    case 'awards':
      $this->page = $this->json_awards();
      break;
    case 'sessions':
      $this->page = $this->json_sessions();
      break;
    case 'login':
      $this->page = $this->json_login();
      break;
    case'update':
    $this->page = $this->json_update();
      break;
      case'register':
        $this->page = $this->json_register();
        break;
    default:
      $this->page = $this->json_error();
      break;
  }
}

/**
 * an arbitrary max length of 20 is set,
 * santises strings getting rid of unwated characters
 * 
 * @param string $x inputed string
 * @return string $x the resulting string onced it has been sinitised 
 */
private function sanitiseString($x) {
  return substr(trim(filter_var($x, FILTER_SANITIZE_STRING)), 0, 20);
}

/**
 * an arbitrary max range of 25000 is set to include all the data within the database
 * 
 * @param int $x  inputted int value
 * @return int $x the resulting int once it has been valitaded
 */
private function sanitiseNum($x) {
  return filter_var($x, FILTER_VALIDATE_INT, array("options"=>array("min_range"=>0, "max_range"=>25000)));
}

/**
 * json_welcome
 * 
 * The function dispalys generic information about the available enpoints
 * and a welcome message.
 * 
 * @uses jsonEncode
 * @return array $output returns an array containing a welcome message, authors name and the available endpoints 
 */
private function json_welcome() {
  $endpoints = array("api/","api/authors","api/slots","api/chairs","api/awards","api/sessions","api/login","api/update");

  $output = array("message"=>"welcome", "author"=>"Nicholas Coyles", "status" =>200,"Available endpoints" => $endpoints);

  return json_encode($output);
 
}

/**
 * json_error
 * 
 * Returns error message
 * 
 * @uses jsonEncode
 * @return array $msg returns an array containing error message and status code.
 */
private function json_error() {
  $msg = array("message"=>"Unknown endpoint, bad request","status" => "400");
  return json_encode($msg);
}

/**
* json_authors
*
* Authors endpoint slecects data about authors from the database depening on the paramiters
* and passes the results to buildOutput function to retutns the results in a human readable fashion 
* @uses jsonEncode
* @uses jsonDecode
*
* @return object passes objects to the builOutput functuon and returns the result
*/ 
private function json_authors() {
  $query  = "SELECT authors.name AS author_name,
             authors.authorId AS authorId
             FROM authors 
             ";
  
  $params = [];
  $nextPage = null;

  if (isset($_REQUEST['search'])) {
    $query .= " WHERE authors.name LIKE :term";
    $term = $this->sanitiseString("%".$_REQUEST['search']."%");
    $params = ["term" => $term];
  }  
  if (isset($_REQUEST['authorId'])) {
      $query .= " WHERE authors.authorId = :term";
      $term = $this->sanitiseNum($_REQUEST['authorId']);
      $params = ["term" => $term];
  }
  if (isset($_REQUEST['page'])) {
    $query .= " ORDER BY authors.name";
    $query .= " LIMIT 10 ";
    $query .= " OFFSET ";
    $query .= 10 * ($this->sanitiseNum($_REQUEST['page'])-1);
    $nextPage = "http://unn-w18011589.newnumyspace.co.uk".BASEPATH."api/authors?page=".$this->sanitiseNum($_REQUEST['page']+1);
  }

  $queryTotal  = "SELECT *
             FROM authors";
  $params2 = [];

  $res = json_decode($this->recordset->getJSONRecordSet($query, $params),true);
  $res2 = json_decode($this->recordset->getJSONRecordSet($queryTotal, $params2),true);
  
  return $this->buildOutput($res,$res2,$nextPage);
}

/**
* json_slots
*
* Slots endpoint slecects relevant data about time slots from the database depening on the paramiters
* and passes the results to buildOutput function to retutns the results in a human readable fashion 
* @uses jsonEncode
* @uses jsonDecode
*
* @return object passes objects to the builOutput functuon and returns the result
*/ 
private function json_slots() {
  
  $query  = "SELECT slots.type AS Session_type,
  slots.dayString AS Conference_Day,
  slots.startHour ||':'||slots.startMinute||'-'||slots.endHour ||':'||slots.endMinute AS Time_Slot,
  slots.slotId
  FROM slots
  WHERE slots.dayInt < 5";
  $params = [];


  if (isset($_REQUEST['conference_days'])) {
    
    $query  = "SELECT slots.dayString AS Conference_Day
    FROM slots
    WHERE slots.dayInt < 5
    GROUP by slots.dayString
    ORDER BY slots.dayInt";

    $params = [];
  } 

  if (isset($_REQUEST['day_slots'])) {
    
    $query  = "SELECT slots.type AS Session_type,
    slots.startHour ||':'||slots.startMinute||'-'||slots.endHour ||':'||slots.endMinute AS Time_Slot,
    slots.slotId
    FROM slots
    WHERE slots.dayInt < 5 AND slots.dayString LIKE :term
    ORDER BY slots.slotId";

    $term = $this->sanitiseString("%".$_REQUEST['day_slots']."%");
    $params = ["term" => $term];
  } 

  $nextPage = null;

  $query2  = "SELECT *
  FROM slots";
             
  $params2 = [];
  
  $res = json_decode($this->recordset->getJSONRecordSet($query, $params),true);
  $res2 = json_decode($this->recordset->getJSONRecordSet($query2, $params2),true);

  return $this->buildOutput($res,$res2,$nextPage);
 
}

/**
* json_chairs
*
* Chairs endpoint slecects relevant data about session chairs from the database depening on the paramiters
* and passes the results to buildOutput function to retutns the results in a human readable fashion 
* @uses jsonEncode
* @uses jsonDecode
*
* @return object passes objects to the builOutput functuon and returns the result
*/  
private function json_chairs() {
  $query  = "SELECT authors.name as 'Session_chair',
  sessions.name as 'Session_title',
  rooms.name as 'Room',
  sessions.sessionId,
  sessions.slotId
  FROM authors
  INNER JOIN sessions on (authors.authorId = sessions.chairId)
  INNER JOIN rooms on (sessions.roomId = rooms.roomId)";

  $params = [];

  $nextPage = null;

  if (isset($_REQUEST['search'])) {
    $query .= " WHERE authors.name LIKE :term";
    $term = $this->sanitiseString("%".$_REQUEST['search']."%");
    $params = ["term" => $term];
  } 
  
  else {
    if (isset($_REQUEST['sessionId'])) {
      $query .= " WHERE sessions.sessionId = :term";
      $term = $this->sanitiseNum($_REQUEST['sessionId']);
      $params = ["term" => $term];
    }
  }
  
  $query2  = "SELECT *
  FROM authors
  INNER JOIN sessions on (authors.authorId = sessions.chairId);";
             
  $params2 = [];
  
  $res = json_decode($this->recordset->getJSONRecordSet($query, $params),true);
  $res2 = json_decode($this->recordset->getJSONRecordSet($query2, $params2),true);

  return $this->buildOutput($res,$res2,$nextPage);
}

/**
* json_awards
*
* Slots endpoint slecects only presentations with awards from the database depening on the paramiters
* and passes the results to buildOutput function to retutns the results in a human readable fashion 
* @uses jsonEncode
* @uses jsonDecode
*
* @return object passes objects to the builOutput functuon and returns the result
*/ 
private function json_awards() {
  $query  = "SELECT title,
  abstract as 'Abstract',
  award as 'Award',
  group_concat(authors.name) as 'Author',
  content.contentId
  FROM content
  INNER JOIN content_authors on (content.contentId = content_authors.contentId)
  INNER JOIN authors on (content_authors.authorId = authors.authorId)
  WHERE content.award IS NOT NULL AND trim(content.award) != \"\"";
  

  $params = [];
  $nextPage = null;
  
  if (isset($_REQUEST['title'])) {
    $query .= " AND content.title LIKE :term ";
    $term = $this->sanitiseString("%".$_REQUEST['title']."%");
    $params = ["term" => $term];
  } 
  
  $query .= "group by content.contentId";
  $query .= " ORDER by content.title";

  if (isset($_REQUEST['contentId'])) {
    $query  = "SELECT content.title as 'Title',
    content.abstract as 'Abstract',
    content.award as 'Award',
    group_concat(authors.name) as 'Author',
    sessions_content.contentId as 'contentId'
    FROM content
    INNER JOIN content_authors on (content.contentId = content_authors.contentId)
    INNER JOIN authors on (content_authors.authorId = authors.authorId)
    INNER JOIN sessions_content on (content.contentId = sessions_content.contentId)
    WHERE  sessions_content.contentId = :contentId
    group by content.contentId";

    $term = $this->sanitiseNum($_REQUEST['contentId']);
    $params = ["contentId" => $term];

  }


  $query2  = "SELECT *
            FROM content";             
  $params2 = [];
  
  $res = json_decode($this->recordset->getJSONRecordSet($query, $params),true);
  $res2 = json_decode($this->recordset->getJSONRecordSet($query2, $params2),true);

  return $this->buildOutput($res,$res2,$nextPage);

}

/**
* json_sessions
*
* Sessions endpoint slecects relevant data about sessions from the database depening on the paramiters
* and passes the results to buildOutput function to retutns the results in a human readable fashion 
* @uses jsonEncode
* @uses jsonDecode
*
* @return object passes objects to the builOutput functuon and returns the result
*/ 
private function json_sessions() {
  $query  = "SELECT sessions.name as 'Session_Title',
  session_types.name as 'Presentation_Type',
  rooms.name as 'Room',
  authors.name as 'Session Chair',
  slots.startHour ||':'||slots.startMinute||'-'||slots.endHour ||':'||slots.endMinute AS Time_Slot,
  slots.dayString as 'Conference_day',
  sessions.sessionId,
  slots.slotId
  FROM sessions
  LEFT JOIN authors on (sessions.chairId = authors.authorId)
  INNER JOIN session_types on (sessions.typeId = session_types.typeId)
  INNER JOIN rooms on (sessions.roomId = rooms.roomId)
  INNER JOIN slots on (sessions.slotId = slots.slotId)
  WHERE   slots.dayInt < 5
  GROUP by sessions.sessionId";

  $params = [];
  $nextPage = null;

  if (isset($_REQUEST['authorId'])) {
    $query = " SELECT 
      sessions.name as 'Session_Title',
      session_types.name as 'Presentation_Type',
      rooms.name as 'Room',
      slots.dayString as 'Conference_day',
      slots.startHour ||':'||slots.startMinute||'-'||slots.endHour ||':'||slots.endMinute AS Time_Slot,
      sessions.sessionId as 'sessionId',
      slots.slotId
      FROM sessions
      INNER JOIN session_types on (sessions.typeId = session_types.typeId)
      INNER JOIN rooms on (sessions.roomId = rooms.roomId)
      INNER JOIN slots on (sessions.slotId = slots.slotId)
      inner join sessions_content on (sessions.sessionId = sessions_content.sessionId)
      inner join content on (sessions_content.contentId = content.contentId)
      inner join content_authors on (content.contentId = content_authors.contentId)
      inner join authors on (content_authors.authorId = authors.authorId) 
      WHERE authors.authorId = :authorId and slots.dayInt < 5
      GROUP by sessions.sessionId
      ORDER by slots.slotId";

    $term = $this->sanitiseNum($_REQUEST['authorId']);
    $params = ["authorId" => $term];

  }

  if (isset($_REQUEST['slotId'])) {
    $query = " SELECT sessions.name as 'Session_Title',
    session_types.name as 'Presentation_Type',
    rooms.name as 'Room',
    authors.name as 'Session_Chair', 
    slots.slotId as'slotId',
    sessions_content.contentId as 'contentId'
    FROM sessions
    LEFT JOIN authors on (sessions.chairId = authors.authorId)
    INNER JOIN session_types on (sessions.typeId = session_types.typeId)
    INNER JOIN rooms on (sessions.roomId = rooms.roomId)
    INNER JOIN slots on (sessions.slotId = slots.slotId)
    Inner Join sessions_content on (sessions.sessionId = sessions_content.sessionId)
    WHERE slots.slotId = :slotId AND slots.dayInt < 5
    GROUP by sessions.sessionId";

    $term = $this->sanitiseNum($_REQUEST['slotId']);
    $params = ["slotId" => $term];

  }
  
  $query2 = "SELECT *
  FROM sessions
  INNER JOIN slots on (sessions.slotId = slots.slotId)
  WHERE slots.dayInt < 5";
             
  $params2 = [];
  

  $res = json_decode($this->recordset->getJSONRecordSet($query, $params),true);
  $res2 = json_decode($this->recordset->getJSONRecordSet($query2, $params2),true);

  return $this->buildOutput($res,$res2,$nextPage);

}

/**
* json_login
*
* The login endpoint is not used directly by the client, instead it handles login details when the 
* login form is submitted. If the client tries to access an error message will be shown.
* @uses jsonEncode
* @uses jsonDecode
*
* @return array of $status - the relevent http status
*                  $msg - human readable message 
*                  $admin - admin status of the user 
*                  $token - Jwt token
*/ 
private function json_login() {
  $msg = "Invalid request. Username and password required";
  $status = 400;
  $token = null;
  $input = json_decode(file_get_contents("php://input"));
 
  if ($input) {
    
    if (isset($input->email) && isset($input->password)) {  
      $query  = "SELECT username, password, admin FROM users WHERE email LIKE :email";
      $params = ["email" => $input->email];
      $res = json_decode($this->recordset->getJSONRecordSet($query, $params),true);
      $password = ($res['count']) ? $res['data'][0]['password'] : null;
 
      if (password_verify($input->password, $password)) {
        $msg = "User authorised. Welcome ". $res['data'][0]['username'];
        $status = 200;

        $admin = $res['data'][0]['admin'];
        $token = array();
        
        $token['email'] = $input->email;
        $token['username'] = $res['data'][0]['username'];
        $token['admin'] = $res['data'][0]['admin'];
        $token['iat'] = time();
        $token['exp'] = time() + (60 * 60);

        $jwtkey = JWTKEY;
        $token = \Firebase\JWT\JWT::encode($token, $jwtkey);

        
      } else { 
        $msg = "username or password are invalid";
        $status = 401;
      }
    }
  } 
 
  return json_encode(array("status" => $status, "message" => $msg,"admin" =>$admin, "token" => $token));
 }

/**
* json_update
*
* The update endpoint is not used directly by the client, instead it handles updating the databse when an admin edits a session
* title. If the client tries to access this edpoint an error message will be shown.
*
* @uses jsonEncode
* @uses jsonDecode
*
* @return array of $status - the relevent http status
*                  $msg - human readable message 
*
*/ 
private function json_update() {
  $input = json_decode(file_get_contents("php://input"));

  if (!$input) {
    return json_encode(array("status" => 400, "message" => "Invalid request"));
  }
  if (!isset($input->token)) {
    return json_encode(array("status" => 401, "message" => "Not authorised"));
  }
  if (!isset($input->name) || !isset($input->sessionId)) {  
    return json_encode(array("status" => 400, "message" => "Invalid request"));
  }

  try {
    $jwtkey = JWTKEY;
    $tokenDecoded = \Firebase\JWT\JWT::decode($input->token,$jwtkey, array('HS256'));
  }
  catch (UnexpectedValueException $e) {        
    return json_encode(array("status" => 401, "message" => $e->getMessage()));
  }

  $query  = "UPDATE sessions SET name = :name WHERE sessionId = :sessionId";
  $params = ["name" => $input->name, "sessionId" => $input->sessionId];
  $res = $this->recordset->getJSONRecordSet($query, $params);    
  return json_encode(array("status" => 200, "message" => "update successful"));
}

/**
* json_register
*
* The register endpoint is not used directly by the client, instead it handles inserting new user information
* into the database when the register form is passed. If the client tries to access this edpoint an error message will be shown.
*
* @uses jsonEncode
* @uses jsonDecode
*
* @return array of $status - the relevent http status
*                  $msg - human readable message 
*
*/ 
private function json_register() {
  $msg = "Invalid request. email, username and password required";
  $status = 400;
  $input = json_decode(file_get_contents("php://input"));
 
  if ($input) {
    if (isset($input->registerEmail) && isset($input->registerPassword) && isset($input->registerUsername)) {  

      if(empty($input->registerEmail)||empty($input->registerPassword)||empty($input->registerUsername)){
      return json_encode(array("status" => 401, "message" => "Invaild request. Please enter vaild details"));
    }else{
    
      $databaseEmail  = "SELECT email
      FROM users
      WHERE email = :email";
      $emailParams = ["email" => $input->registerEmail];

      $databaseUsername  = "SELECT username
      FROM users
      WHERE username = :username";
      $usernameParams = ["username" => $input->registerUsername];

      $res = json_decode($this->recordset->getJSONRecordSet($databaseEmail, $emailParams),true);
      $res2 = json_decode($this->recordset->getJSONRecordSet($databaseUsername, $usernameParams),true);

      if($email = $res['data'][0]['email']){
        return json_encode(array("status" => 400, "message" => "Email ".$email." is already in use"));
      }
      if($username = $res2['data'][0]['username']){
        return json_encode(array("status" => 400, "message" => "Username ".$res2['data'][0]['username']." is already in use"));
      }else{

      $email = ($input->registerEmail);
      $password = password_hash($input->registerPassword, PASSWORD_DEFAULT);
      $username = ($input->registerUsername);

      $query  = "INSERT INTO users (username, email,admin,password)
      VALUES ('$username','$email','0','$password')";
      $params = [];
      $result = $this->recordset->getJSONRecordSet($query, $params);    
      return json_encode(array("status" => 200, "message" => "User Registered, welcome ".$username));
      }
    }
  }
  } 
  return json_encode(array("status" => $status, "message" => $msg));
 }

/**
* buildOutput
*
* buildOutput has the results of sql queries passed into it, to create a re useable response.
* The results of the response are passed back to the endpoints to be displayed.
*
* @param $res - The result of the query selecting the relveant data to be shown
* @param $res2 - The result of seclecting the total count of the relevent tables to get the count.
* @param $nextpage - If next page is required a link to the next page will be shown.
*
* @uses jsonEncode
*
* @return array $count - the count of the data returned
*               $data - the returned data from the database 
*               $status - the successful http status code
*               $msg - a human readable message
*               $total - the total number of data the database 
*               $nextPage - link to the next page if pages are required
*/ 
private function buildOutput($res,$res2,$nextPage){

  $data = $res['data'];
  $count = $res['count'];
  $status = 200;
  $msg = "ok";
  $total = $res2['count'];
  

  return json_encode(array(
    "count" => $count,
    "data" => $data,
    "status" => $status,
    "message" => $msg,
    "total" => $total,
    "next_Page" =>$nextPage    
  ));  
}


/**
 * getPage
 * 
 * getPage returns the desired json page
 * 
 * @return page depending on which enpoint is access the result will be displayed
 */
public function get_page() {
  return $this->page;
}
}
?>
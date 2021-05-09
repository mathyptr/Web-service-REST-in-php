<?php
$method = $_SERVER["REQUEST_METHOD"];

require_once '../../../vendor/autoload.php';
//require_once("class/myLogger.inc");

Logger::configure('class/myLogger.xml');
$log = Logger::getLogger('myLogger');

include('./class/Student.php');

try {
$student = new Student();

switch($method) {
  case 'GET':
    if (isset($_GET['id'])){
      $id = $_GET['id'];		
	  $log->debug("GET METHOD ID= ". $id );
      $student = $student->find($id);
      //$js_encode = json_encode(array('state'=>TRUE, 'student'=>$student),true);
	  $js_encode = json_encode(array('student'=>$student),true);
    }else{
	  $log->debug("GET METHOD ID= ALL");
      $students = $student->all();
     // $js_encode = json_encode(array('state'=>TRUE, 'students'=>$students),true);
	 $js_encode = json_encode($students);
    }
    header("Content-Type: application/json");
    echo($js_encode);
    break;

	case 'POST':
  $log->debug("POST METHOD (INSERT STUDENT)");
	$body = file_get_contents("php://input");
	$js_decoded = json_decode($body, true);
	$student = new Student();
	$student->_name = $js_decoded["name"];
	$student->_surname = $js_decoded["surname"];
	$student->_sidiCode = $js_decoded["sidi_code"];
	$student->_taxCode = $js_decoded["tax_code"];
	$student->insert($student);
  $log->debug("END POST METHOD (INSERT STUDENT)");
	break;

  case 'DELETE':
    if (isset($_GET['id'])){
	  $id = $_GET['id'];
      $student = $student->delete($id);
      $js_encode = json_encode(array('state'=>TRUE, 'student'=>$student),true);
    }else{
      $js_encode = json_encode("ERRORE",true);
    }
    header("Content-Type: application/json");
    echo($js_encode);
    break;	

  case 'PUT':
    if (isset($_GET['id'])){
	  $id = $_GET['id'];
	  $body = file_get_contents("php://input");
	  $js_decoded = json_decode($body, true);
	  $log->debug("PUT METHOD".json_encode($js_decoded ));
	  
	  $student->_name = $js_decoded["name"];
	  $student->_surname = $js_decoded["surname"];
	  $student->_sidiCode = $js_decoded["sidi_code"];
	  $student->_taxCode = $js_decoded["tax_code"];
     $student->update($id, $student);
	 $log->debug("Aggiornamento studente :".$student->_name);
      //$js_encode = json_encode(array('state'=>TRUE, 'student'=>$student),true);
	  //$js_encode = json_encode($student);
    }else{
      $js_encode = json_encode("ERRORE",true);
    }
   // header("Content-Type: application/json");
  //  echo($js_encode);
    break;

  default:
    break;
}
}
 catch(Exception $e) {
  header("Content-Type: application/json");
  $js_encode=json_encode($e->getMessage());
  echo($js_encode);
  $log->debug("Exception:".$e->getMessage());
}



?>

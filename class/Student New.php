<?php
include("DBConnection.php");

require_once '../../../vendor/autoload.php';
//require_once("class/myLogger.inc");

Logger::configure('class/myLogger.xml');


class Student 
{
  private $db;
  public $_id;
  public $_name;
  public $_surname;
  public $_sidiCode;
  public $_taxCode;
  private $_errorSTR="";  

  public function __construct() {
    $this->db = new DBConnection();
    if($this->db==null)
      $this->_errorSTR="Errore Connessione al database";
    else
     $this->db = $this->db->returnConnection();    
	self::log()->debug("Creazione Oggetto Studente");
  }

    // return errorCode
    public function getErrorCode() {
      return $this->_errorSTR;
  }

  
  public  function setLogger(LogInterface $logger){
		self::$log = $logger;
}

	private  function log(){
		return Logger::getLogger( "myLogger" );
	}

  public function find($id){
	self::log()->debug("Ricerca Studente con id: ".$id);
    $sql = "SELECT * FROM student WHERE id=:id";
	self::log()->debug($sql);
    $stmt = $this->db->prepare($sql);
    $data = [
      'id' => $id
    ];
    $stmt->execute($data);
    $result = $stmt->fetch(\PDO::FETCH_ASSOC);
    return $result;
  }
  
  public function all(){
	self::log()->debug("Ricerca Studenti");
    $sql = "SELECT * FROM student";
	self::log()->debug($sql);
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    return $result;
  }
  
  public function insert($student){
  	self::log()->debug("Inserimento Studente");
    $sql="insert into student (name,surname,sidi_code,tax_code) values (:name,:surname,:sidiCode,:taxCode)";
	self::log()->debug($sql);
    $stmt = $this->db->prepare($sql);
    $data = [
      'name' => $student->_name,
	  'surname' => $student->_surname,
	  'sidiCode' => $student->_sidiCode,
	  'taxCode' => $student->_taxCode
    ];
    $stmt->execute($data);
	self::log()->debug("Inserimento effettuato");
  }
  
  
  
   public function update($id, $student){
	self::log()->debug("Aggiornamento Studente con id: ".$id);
    $sql = "UPDATE student set name=:name,surname=:surname,sidi_code=:sidiCode,tax_code=:taxCode WHERE id=:id";
	self::log()->debug($sql);
    $stmt = $this->db->prepare($sql);
    $data = [
	  'id' => $id,
      'name' => $student->_name,
	  'surname' => $student->_surname,
	  'sidiCode' => $student->_sidiCode,
	  'taxCode' => $student->_taxCode
    ];
    $stmt->execute($data);
	self::log()->debug("Aggiornamento effettuato ".$js_encode = json_encode($student));
  }
  
  
  
  
  public function delete($id){
	self::log()->debug("Cancellazione Studente con id: ".$id);
	$sql="DELETE FROM student_class where id_student=:id";
	self::log()->debug($sql);
	$stmt = $this->db->prepare($sql);
	$stmt->bindParam(':id', $id);
	$stmt->execute();
	$sql="DELETE FROM student where id=:id";
	self::log()->debug($sql);
	$stmt = $this->db->prepare($sql);
	$stmt->bindParam(':id', $id);
	$stmt->execute();
	self::log()->debug("Cancellazione Studente con id: ".$id. " avvenuta");
  }
}
?>

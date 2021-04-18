	<?php


class Logger {

	static $log;
	static $loggerName;
	static $logger;

	public  function Logger($name){
		self::$log = self::configure($name);
	}

	public static function configure( $loggerName){
		self::$loggerName = $loggerName;
	}

	public static function getLogger( $loggerName){
		
	   if(!isset(self::$logger)) 
		self::$logger = new Logger($loggerName);
         
	   return self::$logger ;
	}	
	 
	public static function trace($t){
		
	}
	public static function debug($t){
		
	}
	public static function info($t){
		
	}
	public static function warn($t){
		
	}
	public static function error($t){
		
	}
	public static function fatal($t){
		
	}			

	
	}
?>
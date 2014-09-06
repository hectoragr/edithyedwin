<?php

$databases = array(
	'default' => 'LOCAL',
	
	//Se establecen los parametros de conexión de la BBDD developmetn
	'DESARROLLO' => array(
		'dbms' => "mysql",
		'host' => 'localhost',
		'name' => 'u148390976_eye',
		'user' => 'u148390976_eye',
		'password' => 'gohe1106',
		'encoding' => 'utf8',
		'port' => '',
		'persistant' => false,
	),'LOCAL' => array(
		'dbms' => "mysql",
		'host' => 'localhost',
		'name' => 'edithyedwin',
		'user' => 'root',
		'password' => 'root',
		'encoding' => 'utf8',
		'port' => '',
		'persistant' => false,
	),
);

	class Database {
		//Se establece la variable que contendrá la conexión con la BBDD
		protected $_conexion = null;
		protected $query_where = "";
		
		public function __construct() {
			global $databases;
			
			//Se obtiene el array con los parametros de conexión
			$dbDefault = $databases[$databases["default"]];
			
			//Se determina que DBMS se va a utilizar para establecer la conexión
			switch ($dbDefault["dbms"]) {
				//Se conecta con MySQL
				case 'mysql':
					$this->conexionMysql($dbDefault);
					break;
				
				//Se conecta con SQL
				case 'sql':
					$this->conexionSql($dbDefault);
					break;
				
				//Se conecta con ORACLE
				case 'oracle':
					$this->conexionOracle($dbDefault);
					break;
			}
			
		}

		public function delete($table, $fields = null) {
			
				 
			//Se verifica si el usuario desea borrar todos los registros o solo un registro
			if (is_null($fields)) {
				//Se prepara la consulta
				$consulta = $this->_conexion->prepare("DELETE FROM ".$table);
			} else {
				//Se prepara la consulta
				$key = key($fields);
				$consulta = $this->_conexion->prepare("DELETE FROM WHERE ".$key." = :valor");
				$consulta->bindParam(":valor", $fields );
			}
			
			//Se realiza la consulta
			try {
				$consulta->execute();
				
				//Se verifica si se ha ejecutado la consulta
				if ($consulta->rowCount() > 0) {
					//Se devuelve la respuesta
					return true;
				} else {
					//Se devuelve la respuesta
					return false;
				}
			} catch(PDOException $e) {
				die($e->getMessage());
			}
		}
		
		public function authUser($params, 
								 $encrypt = null, 
								 $credentials = null) {
			//Se obtienen los parametros separados
			$tabla = $params["table"];
			$fields = array(key(array_slice($params, 1, 1)), key(array_slice($params, 2, 1)));
			$user = $params[$fields[0]];
			$password = $params[$fields[1]];
			
			//print_r($params);
			
			//Se verifica si el usuario desea encriptar la contraseña
			if (!is_null($encrypt)) {
				//Se encripta la cadena con la función que el usuario desea
				switch ($encrypt) {
					case 'md5':
						$password = md5($password);
						break;
					
					case 'sha1':
						$password = sha1($password);
						break;
						
					case 'crc':
						$password = crc32($password);
						break;
				}
			}
			
			//Se establece la variable que contendrá la respuesta que se regresará
			$auth = array();
			
			//Se prepará la consulta
			$consulta = $this->_conexion->prepare("SELECT * FROM ".$tabla." WHERE ".$fields[1]." = :password AND ".$fields[0]." = :user");
			$consulta->bindParam(":password", $password);
			$consulta->bindParam(":user", $user);
			
			//Se verifica si el usuario a mostrado datos correctos
			try {
				$consulta->execute();
				if ($consulta->rowCount() == 1) {
					//Se obtienen las credenciales solicitas
					$credentials = implode(", ", $credentials);
					$getCredentials = $this->_conexion->prepare("SELECT ".$credentials." FROM ".$tabla." WHERE ".$fields[1]." = :password AND ".$fields[0]." = :user");
					$getCredentials->bindParam(":password", $password);
					$getCredentials->bindParam(":user", $user);
					
					try {
						$getCredentials->execute();
						
						//Se obtiene los datos de las credenciales
						$auth["credentials"] = $getCredentials->fetch(PDO::FETCH_ASSOC);
					} catch(PDOException $e) {
						die($e->getMessage());
					}
					
					$auth["userAuth"] = true;
				} else {
					$auth["userAuth"] = false;
				}
			} catch(PDOException $e) {
				die($e->getMessage());
			}
			
			//Se devuelve el resultado
			return $auth;
		}
		
		public function insert($table, $params) {
			//Se establecen las variables de control
			$i = 1;
			$campos = "";
			$valores = "";
			$datos = array();
						
			foreach ($params as $clave => $valor) {
				$campos .= (count($params) == $i) ? stripslashes($clave) : stripslashes($clave).", ";
				$valores .= (count($params) == $i) ? "?" : "?, ";
				$datos[] = $valor;
				$i++;
			}
			
			//Se forma el query
			$query = "INSERT INTO ".$table." (".$campos.") VALUES (".$valores.")";
			
			//Se ejecuta la consulta
			try {
				$consulta = $this->_conexion->prepare($query);
				
				return ($consulta->execute($datos)) ? true : false;
			} catch(PDOException $e) {
				die($e->getMessage());
			}
		}
		
		public function update($table, $params, $where) {
			//Se prepara la consulta
			$query = "UPDATE ".$table." SET ";
			$i = 1;
			$valores = array();
			$keyWhere = key($where);
			$keyWhere = explode(" ", $keyWhere);
			$operador = (count($keyWhere) == 1) ? "=" : $keyWhere[1];
			
			foreach ($params as $clave => $valor) {
				$query .= ($i == count($params)) ? $clave." = ?" : $clave." = ?, ";
				$valores[] = $valor;
			}
		}
		
		public function isInDb($table, $field, $value) {
			//Se prepara la consulta
			$consulta = $this->_conexion->prepare("SELECT * FROM ".$table." WHERE ".$field." = :valor");
			$consulta->bindParam(':valor', $value);
			
			//Se ejecuta la consulta
			try {
				$consulta->execute();
				
				if ($consulta->rowCount() > 0) {
					return true;
				} else {
					return false;
				}
			} catch(PDOException $e) {
				die($e->getMessage());
			}
		}

		/*
		 * @author: Héctor Gómez <hector.gomez@metodika.mx>
		 * @version. 0.1 2013-06-16
		 * 
		 * @param $params array. Contiene los campos y valores a evaluar.
		 * 		$params['table'] - La tabla donde se van a cotejar los campos
		 * 		$param['nombreDelCampoDeLaTabla'] - El identificador de la fila indica el nombre del campo en la BBDD y el valor
		 * 											indica el valor a cotejar.
		 * @param $singleString bool. Si el valor el tru se cotejarán todos los campos y valores como una cadena en conjunta.
		 * 
		 * @return mixed. Si el usuario solicita una validación de string (multiple o simple) regresará verdadero o falso (bool)
		 * 				  de lo contrario devolvera un array, siendo cada renglon la comparativa individual.
		 * 
		 * Metodo que regrese el resultado que se encuentra en la BBDD.
		 */
		public function selectOT($table, $field = '', $value = '', $order='') {
			//Se prepara la consulta
			$sql="SELECT * FROM ".$table.(!empty($field)?" WHERE ".$field." = ":"").(!empty($value)?" :valor ":"").(!empty($order)?$order:"");
			$consulta = $this->_conexion->prepare($sql);
			$consulta->bindParam(':valor', $value);
			
			//Se ejecuta la consulta
			try {
				$consulta->execute();
				
				if ($consulta->rowCount() > 0) {
					return $consulta->fetchAll(PDO::FETCH_ASSOC);
				} else {
					return array();
				}
			} catch(PDOException $e) {
				die($e->getMessage().$sql);
			}
		}
		
		private function conexionMysql($params) {
			//Se crea el DSN para la conexión
			$dsn = "mysql:host=".$params["host"].";dbname=".$params["name"];
			$dsn .= (strlen(trim($params["encoding"])) == 0) ? "" : ";charset=".$params["encoding"];
			$dsn .= (strlen(trim($params["port"])) == 0) ? "" : ";port=".$params["port"];
			
			try {
				//Se intenta establecer la conexion con la BBDD
				$this->_conexion = new PDO($dsn,$params["user"],$params["password"],array(PDO::ATTR_PERSISTENT => $params["persistant"]));
				//Se activa las excepciones de PDO
				$this->_conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch(PDOException $e) {
				//Si no se puedo establecer la conexion se mata el script y se informa al usuario
				die($e->getMessage());
			}
		}
		
		public function __destruct() {
			//Se cierra la conexión a la BBDD
			$this->_conexion = null;
		}
	}

?>

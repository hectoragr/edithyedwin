<?php
require_once('Database.php');

class Libs extends Database
{
	private function isEmail($email) {
		return (!preg_match("/^[a-z]([\w\.]*)@[a-z]([\w\.-]*)\.[a-z]{2,3}$/", $email)) ? false : true;
	}

	function sendMail($to, $mensaje, $jsonc = null) {
		$json = array();
		$json['error'] = false;
		$json['msg'] = "Se ha enviado el correo con éxito";

		$emails = explode(", ", $to);
		
		foreach ($emails as $email) {
			if (!isset($to) || !$this->isEmail($email)) {
				$json['error'] = true;
	 			$json['msg'] = "El e-mail ".$email." no es válido";
			}
		}

		if ($json['error'] == false) {
			@mail($to, "Boda Edith y Edwin | Asistencia", utf8_decode($mensaje));
		}

		if ($jsonc) {
			echo json_encode($json);
		}
	}

	function confirmar() {
		$json = array();
		$json['error'] = false;
		$json['msg'] = "Tu respuesta ha sido registrada con éxito.";

		if (!isset($_POST['folio']) || empty($_POST['folio'])) {
			$json['error'] = true;
			$json['msg'] = "Folio no válido.";
		}

		if (!isset($_POST['nombre']) || empty($_POST['nombre'])) {
			$json['error'] = true;
			$json['msg'] = "Confirme el nombre suyo, o de su familia.";
		}

		if (!isset($_POST['cantidad']) || empty($_POST['cantidad'])) {
			$json['error'] = true;
			$json['msg'] = "Confirme la cantidad de invitados.";
		}

		if (!isset($_POST['correo']) || !$this->isEmail($_POST['correo'])) {
			$json['error'] = true;
			$json['msg'] = "El correo proporcionado no es válido.";
		}

		if (!isset($_POST['confirmar']) || empty($_POST['confirmar'])) {
			$json['error'] = true;
			$json['msg'] = "Confirma si asistirás al evento o no.";
		}

		if ($json['error'] == false) {
			$data = array($_POST['nombre'],
						  $_POST['correo'],
						  (isset($_POST['cantidad']) && $_POST['cantidad'] > 0)? $_POST['cantidad']:0,
						  $_POST['confirmar'] == 'on'?1:0,
						  $_POST['mensaje'],
						  $_POST['folio']);
			$sql = "UPDATE invitados 
					SET nombre = ?,
						email = ?,
						confirmados = ?,
						asistira = ?,
						mensaje = ?
					WHERE folio = ?";
			$consulta = $this->_conexion->prepare($sql);
			$consulta->execute($data);
			$done = $consulta !== false ? true : false;
			if (!$done) {
				$json['error'] = true;
				$json['msg'] = "Tu respuesta no ha podido ser guardada. Intenta más tarde.";
			}else {
				if ($_POST['confirmar'] == 'on') {
					$cmsg = "Hola ".$_POST['nombre'].", \n Hemos confirmado tu asistencia a nuestra boda. Confirmaste la asistencia de ".$_POST['cantidad']." invitados.\n Si deseas corregir esta información, regresa a la página de confirmar y ajusta el número con el mismo folio que ya tienes.\nTe esperamos el día 18 de Octubre del 2014.\n\n Saludos de Edith y Edwin.";
					$this->sendMail($_POST['correo'], $cmsg);

					$cmsg = "Un invitado ha confirmado su asistencia con la siguiente información: \n";
					foreach ($_POST as $key => $value) {
						if ( in_array($key, array('correo', 'nombre', 'cantidad', 'mensaje')) ) {
							$cmsg .= "\t -".ucfirst($key)." : ".$value."\n";
						}
					}
					$this->sendMail("hector.agr@gmail.com, mr.tupac@gmail.com", $cmsg);
				}else {
					$cmsg = "Un invitado ha rechazado su asistencia con la siguiente información: \n";
					foreach ($_POST as $key => $value) {
						if (in_array($key, array('correo', 'nombre', 'cantidad', 'mensaje')) && !empty($value)) {
							$cmsg .= "\t -".ucfirst($key)." : ". $value."\n";
						}
					}
					$this->sendMail("hector.agr@gmail.com, mr.tupac@gmail.com", $cmsg);
				}

			}
		}

		echo json_encode($json);

	}

	function buscar() {
		$json = array();

		if (!isset($_POST['folio']) || empty($_POST['folio'])) {
			$json['error'] = true;
			$json['msg'] = "Folio no válido";
		}else {
			$sql = "SELECT * FROM invitados WHERE folio = :folio LIMIT 1";
			$consulta = $this->_conexion->prepare($sql);
			$consulta->bindParam(":folio", $_POST['folio']);
			$consulta->execute();
			if ($consulta->rowCount()) {
				$data = $consulta->fetch(PDO::FETCH_ASSOC);
				$json['error'] = false;
				$json['msg'] = "Usuario encontrado.";
				$data['cantidad'] = '<select id="cantidad" name="cantidad" class="form-control">';
				for ($i = 1; $i <= $data['max']; $i++) { 
					$data['cantidad'].= '<option value="'.$i.'" '.($data['confirmados'] == $i?"selected":"").'>'.$i.'</option>';	
				}
				$data['cantidad'].= '</select>';
				$json['data'] = $data;
			}else {
				$json['error'] = true;
				$json['msg'] = "Folio no encontrado. Verifique el folio.";
			}
		}

		echo json_encode($json);

	}
}

if (isset($_REQUEST['accion'])) {
	$libs = new Libs();
	switch ($_REQUEST['accion']) {
		case 'sendMail':
			$libs->sendMail();
			break;
		case 'confirmar':
			$libs->confirmar();
			break;
		case 'buscarFolio':
			$libs->buscar();
			break;
		default:
			die("Acción no definida".print_r($_REQUEST));
			break;
	}
}else {
	die("Acción no definida");
}

?>
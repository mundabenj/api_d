<?php
	class fncs{
		public function setMsg($name, $values, $class){
			if(is_array($values)){
				$_SESSION[$name] = $values;
			}else{
				$_SESSION[$name] = '<span class="'.$class.'">'. $values .'</span>';
			}
		}

		public function getMsg($name){
			if(isset($_SESSION[$name])){
				$session = $_SESSION[$name];
				unset($_SESSION[$name]);
				return $session;
			}
		}

		public function verify_profile(){
			if(isset($_SESSION["consort"]) & ($_SESSION["consort"]["genderId"] == 0 || $_SESSION["consort"]["roleId"] == 0)){
				header("Location: complete_reg.php");
				exit();
			}
		}

		// check user is signed in to view protected pages
		public function checksignin() {
			if (!isset($_SESSION["consort"]) || !is_array($_SESSION["consort"])) {
				$this->setMsg('msg', 'User must signin.', 'invalid-feedback');
				@header("Location: signin.php");
				exit ();
			}
		}
	}
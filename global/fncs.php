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
	}
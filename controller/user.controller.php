<?php


class User
{
    private $user;
    
    private $role_table = array(
        'register' => array(
                            'roles' => array('admin'),
                            'not-auth-path' => 'home/forbidden'
                      ),
        'login' => array(
                        'roles' => array(),
                        'not-auth-path' => 'allow-all'
                  ),
        'all' => array(
                            'roles' => array('admin'),
                            'not-auth-path' => 'home/forbidden'
                 ),
        'delete' => array(
                            'roles' => array('admin'),
                            'not-auth-path' => 'home/forbidden'
                 ),
        'edit' => array(
                            'roles' => array('admin', 'user'),
                            'not-auth-path' => 'home/forbidden'
                      )
    );
    
    public function __construct() {
         $this->user = new Users();
         if (!session_id()) session_start();
    }
    
    public function __call($method, $args) {
        // First we check if the page does not need authentication
        if ($this->role_table[$method]['not-auth-path'] == 'allow-all') {
            $this->$method($args);
            exit();
        }
        // Then we check if the user is logged in
        if (Helper::sessionValidate()) {
            // Then we check if has the proper role for the requested page
            if (in_array($_SESSION['role'] ,$this->role_table[$method]['roles'])) {
                $this->$method($args);
                exit();
            }
        }
        // If nothing from above happened we show forbidden page.
        header("Location: ".Config::$base_url.$this->role_table[$method]['not-auth-path']);        
    }
    
    protected function login() {
        if(!empty($_POST)){            
            $userData = $this->user->login($_POST['email'], $_POST['password']);
            if ($userData) {
                $_SESSION['user'] = $userData['user'];
                $_SESSION['hash'] = $userData['hash'];
                $_SESSION['role'] = $userData['role'];
                header("Location: ".Config::$base_url);                
            }
            else {
                $login = new View('template_name','template_file');
                $login->render(array('error' => 'Login Failed'));
            }
        }
        else {
            $login = new View('template_name','template_file');
            $login->render(array());
        }
    }
    
    protected function register() {       
        if (empty($_POST)) {
            $register = new View('template_name', 'template_file');
            $register->render();
        }
        else {
            if ($this->user->register($_POST)) {
                // With user registered succefuly message
                header("Location: ".Config::$base_url."user/all");
                exit();
            } else {
                // With already exists message
                header("Location: ".Config::$base_url."user/login");
                exit();                     
            }
            exit();
        }
    }
    
    protected function edit($params = '') {
		if ($params[0]['user']) {
			$id = $this->user->getUserIDByUsername($params[0]['user']);
			if (($params[0]['user'] === $_SESSION['user']) || ($_SESSION['role'] == 'admin')) {
				$params[0]['id'] = $id[0]['idusers'];
			}
			else {
				header("Location: ".Config::$base_url.'home/forbidden');
				exit();
			}
		}
	
		if (empty($_POST) && $params[0]['id'] != '') 
		{
			$data = $this->user->getUserById($params[0]['id']);
			$user_edit = new View('template_name', 'template_file');
			$user_edit->render(array('data' => $data[0]));
		}
		else
		{
			if (!empty($_POST['name'])) {
				$this->user->update($_POST);
				header('HTTP/1.0 200 OK', true, 200);
			}
			else {
				header('HTTP/1.0 400 Bad Request', true, 400);
				echo "Something bad happened, try again!";
			}
		}

    }
    
    protected function delete($params = '') {
		$this->user->delete($params[0]['id']);
		header("Location: ".Config::$base_url.'user/all');
		exit();
	}
    
    protected function all() {        
        $data = $this->user->getUsers();       
        $all_users = new View('template_name', 'template_file');
        $all_users->render(array('data' => $data));
    }
    
    public function logout() {
        session_unset(); 
        session_destroy();
        header("Location: ".Config::$base_url);
        exit();
    }

    
}

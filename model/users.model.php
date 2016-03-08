<?php


class Users extends Base
{

    public function __construct(){
        parent::__construct();
    }

    public function getUsers() {
        $sql = 'SELECT idusers, username, email, r.rolename as role, lastLogin FROM users as u
                INNER JOIN roles as r ON u.roleID=r.idroles';
        return $this->query($sql);   
    }

    public function getUserByID($id) {
        $sql = 'SELECT username, email, r.rolename as role FROM users as u
                INNER JOIN roles as r ON u.roleID=r.idroles
                WHERE idusers=?';
        $params = array($id);
        return $this->query($sql, $params);
    }

	public function getUserIDByUsername($user) {
        $sql = 'SELECT idusers FROM users WHERE username=?';
        $params = array($user);
        return $this->query($sql, $params);
    }

    /**
     * @param $email
     * @param $password
     * @return userData for session ID on successful login | false otherwise
     */
    public function login($email, $password) {
        $sql = 'SELECT u.username, u.password, r.rolename as role FROM users as u
                INNER JOIN roles as r ON u.roleID=r.idroles
                WHERE email=? OR username=?';
        $params = array($email, $email);
        $user = $this->query($sql, $params);
        if (password_verify($password, $user[0]['password'])) {
            $userData['user'] = $user[0]['username'];
            $userData['hash'] = $this->sessionHash($user[0]['username']);
            $userData['role'] = $user[0]['role'];
            return $userData;
        }
        else {
            return False;
        }
    }

    public function delete($id) {
		$sql = 'DELETE FROM users WHERE idusers=?';
        $params = array($id);
        return $this->query($sql, $params);
	}
    
    public function update($data) {
        extract($data);
        if ($name == 'roleID') {
            $sql = "UPDATE users SET $name=(SELECT idroles FROM roles WHERE rolename=?) WHERE username=?";
        }
        else {
            $sql = "UPDATE users SET $name=? WHERE username=?";
        }
        if ($name == 'password') {
            $params = array(password_hash($value, PASSWORD_BCRYPT, array('cost' => 9)), $pk);
        }
        else {
            $params = array($value, $pk);
        }
        return $this->nonquery($sql, $params);
    }

    /**
     * @param $email
     * @param $password
     */
    public function register($data) {
        extract($data);
        $sql = 'INSERT INTO users (username, email, password, lastLogin, roleID) SELECT ?,?,?,?,idroles FROM roles WHERE rolename=?';
        $params = array (
            $name,
            $email,
            password_hash($password, PASSWORD_BCRYPT, array('cost' => 9)),
            date('Y-m-d H:i:s'),
            $role
        );
        return ($this->nonquery($sql, $params)) ? True : False;
    }

    private function sessionHash($username) {
        $hash = hash('SHA256', time().$username);
        $sql = 'UPDATE users SET sessionID=?, lastLogin=? WHERE username=?';
        $params = array(
            $hash,
            date('Y-m-d H:i:s'),
            $username
        );
        return ($this->nonquery($sql, $params))? $hash : False;
    }
        
    
    public function validSession($username, $sessionID) {
        $sql = 'SELECT EXISTS(SELECT 1 FROM users WHERE username=? AND sessionID=?)';
        $params = array(
            $username,
            $sessionID
        );
        return $this->query($sql, $params);
    }
}

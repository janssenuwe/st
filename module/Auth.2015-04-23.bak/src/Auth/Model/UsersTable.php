<?php
namespace Auth\Model;

use Zend\Db\TableGateway\TableGateway;

class UsersTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
	
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getUser($user_id)
    {
        $user_id  = (int) $user_id;
        $rowset = $this->tableGateway->select(array('user_id' => $user_id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

	public function getUserByToken($token)
    {
        $rowset = $this->tableGateway->select(array('user_registration_token' => $token));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $token");
        }
        return $row;
    }
	
    public function activateUser($usr_id)
    {
		$data['user_active'] = 1;
		$data['user_email_confirmed'] = 1;
		$this->tableGateway->update($data, array('user_id' => (int)$usr_id));
    }	

    public function getUserByEmail($user_email)
    {
        $rowset = $this->tableGateway->select(array('user_email' => $user_email));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $user_email");
        }
        return $row;
    }

    public function changePassword($user_id, $password)
    {
		$data['password'] = $password;
		$this->tableGateway->update($data, array('user_id' => (int)$usr_id));
    }
	
    public function saveUser($auth)
    {
		// for Zend\Db\TableGateway\TableGateway we need the data in array not object
        $data = array(
            'user_passwort'                     => $auth->user_passwort,
            'user_email'  			=> $auth->user_email,
            'user_firmenname'                   => $auth->user_firmenname,
            'user_anrede'                       => $auth->user_anrede,
            'user_titel'                        => $auth->user_titel,
            'user_vorname'                      => $auth->user_vorname,
            'user_nachname'                     => $auth->user_nachname,
            'user_land'                         => $auth->user_land,
            'user_strasse'                      => $auth->user_strasse,
            'user_hausnummer'                   => $auth->user_hausnummer,
            'user_plz'                          => $auth->user_plz,
            'user_ort'                          => $auth->user_ort,
            'user_laendervorwahl'               => $auth->user_laendervorwahl,
            'user_vorwahl'                      => $auth->user_vorwahl,
            'user_telefon'                      => $auth->user_telefon,
 
        );
		// If there is a method getArrayCopy() defined in Auth you can simply call it.
		// $data = $auth->getArrayCopy();

        $user_email = (int)$auth->user_email;
        if ($user_email == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getUser($user_email)) {
                $this->tableGateway->update($data, array('user_email' => $user_email));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }
	
    public function deleteUser($email)
    {
        $this->tableGateway->delete(array('user_email' => $email));
    }	
}
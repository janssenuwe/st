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

    public function getUser($usr_id)
    {
        $usr_id  = (int) $usr_id;
        $rowset = $this->tableGateway->select(array('usr_id' => $usr_id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $usr_id");
        }
        return $row;
    }

	public function getUserByToken($token)
    {
        $rowset = $this->tableGateway->select(array('usr_registration_token' => $token));
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
		$this->tableGateway->update($data, array('usr_id' => (int)$usr_id));
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

    public function changePassword($usr_id, $password)
    {
		$data['password'] = $password;
		$this->tableGateway->update($data, array('usr_id' => (int)$usr_id));
    }
	
    public function saveUser(Auth $auth)
    {
		// for Zend\Db\TableGateway\TableGateway we need the data in array not object
     $data = array(
            'user_passwort'                      => $auth->user_passwort,
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
            'usr_email_confirmed'               => $auth->usr_email_confirmed,
        );
		// If there is a method getArrayCopy() defined in Auth you can simply call it.
		// $data = $auth->getArrayCopy();

        $usr_id = (int)$auth->usr_id;
        if ($usr_id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getUser($usr_id)) {
                $this->tableGateway->update($data, array('usr_id' => $usr_id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }
	
    public function deleteUser($id)
    {
        $this->tableGateway->delete(array('user_id' => $id));
    }	
}
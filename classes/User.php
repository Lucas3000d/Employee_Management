<?php
/**
 * User class.
 * This class handles basic admin login data.
 */
class User extends Person
{
    private $id;
    private $username;
    private $password;

    public function __construct($username, $password, $id = null)
    {
        parent::__construct('', '');
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
    }

    public function getFullName()
    {
        return $this->username;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }
}
?>

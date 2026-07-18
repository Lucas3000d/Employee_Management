<?php
/**
 * Abstract class Person.
 * This is the base class for all people in the system.
 */
abstract class Person
{
    protected $firstName;
    protected $lastName;

    public function __construct($firstName, $lastName)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    abstract public function getFullName();
}
?>

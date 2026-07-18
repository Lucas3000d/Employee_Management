<?php
/**
 * Employee class.
 * This class extends Person and stores employee details.
 */
class Employee extends Person
{
    private $employeeId;
    private $gender;
    private $departmentId;
    private $phone;
    private $email;
    private $salary;
    private $hireDate;

    public function __construct($firstName, $lastName, $gender, $departmentId, $phone, $email, $salary, $hireDate, $employeeId = null)
    {
        parent::__construct($firstName, $lastName);
        $this->employeeId = $employeeId;
        $this->gender = $gender;
        $this->departmentId = $departmentId;
        $this->phone = $phone;
        $this->email = $email;
        $this->salary = $salary;
        $this->hireDate = $hireDate;
    }

    public function getFullName()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getEmployeeId()
    {
        return $this->employeeId;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function getDepartmentId()
    {
        return $this->departmentId;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getSalary()
    {
        return $this->salary;
    }

    public function getHireDate()
    {
        return $this->hireDate;
    }
}
?>

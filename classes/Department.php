<?php
/**
 * Department class.
 * This class handles department data.
 */
class Department
{
    private $departmentId;
    private $departmentName;

    public function __construct($departmentName, $departmentId = null)
    {
        $this->departmentId = $departmentId;
        $this->departmentName = $departmentName;
    }

    public function getDepartmentId()
    {
        return $this->departmentId;
    }

    public function getDepartmentName()
    {
        return $this->departmentName;
    }
}
?>

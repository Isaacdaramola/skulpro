<?php
class teacher extends skulpro{

private $course_taken = array();
private $role = "TEACHER";
private $department;
private $subject_taken;
private $employment_date;
private $qualification;
private $class_supervising;
private $salary;
private $work_experience;
private $children = array();
public $user_id;
private $tax_pin;
private $next_of_kin;


	public function add_teacher_metadata($user_id)
	{
		$this->user_id = (int) $user_id;
		$query = $this->conn->query("INSERT  INTO teachers_metadata(user_id)  VALUES('$this->user_id') ");
	}
		
	public function  update_teacher_metadata()
	{

	}

	public function get_teachers(){
		$query = $this->conn->query("SELECT* FROM users WHERE user_role='teacher' ");
		return $query;
	}

}

?>
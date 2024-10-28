<?php

namespace App\Models;

use App\Models\BaseModel;
use \PDO;

class Course extends BaseModel
{
    public function find($course_code)
    {
        $sql = "SELECT * FROM courses WHERE course_code = :course_code";
        $statement = $this->db->prepare($sql);
        $statement->execute(['course_code' => $course_code]);
        return $statement->fetchObject('\App\Models\Course');
    }

    public function all()
    {
        $sql = "SELECT c.*, COUNT(ce.course_code) AS enrollees
                FROM courses AS c
                LEFT JOIN CourseEnrolments AS ce ON c.course_code = ce.course_code
                GROUP BY c.course_code";
        $statement = $this->db->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_CLASS, '\App\Models\Course');
    }
    
}

<?php

namespace App\Models;

use \PDO;

class CourseEnrolment
{
    protected $db; // Change protected variable name to $db

    // Constructor should accept the database connection as a parameter
    public function __construct($db)
    {
        $this->db = $db; // Correctly store the PDO instance
    }

    public function enroll($course_code, $student_code, $enrollment_date)
    {
        $query = "INSERT INTO enrolments (course_code, student_code, enrollment_date) VALUES (:course_code, :student_code, :enrollment_date)";
        $stmt = $this->db->prepare($query); // Use the stored PDO instance
        $stmt->bindParam(':course_code', $course_code);
        $stmt->bindParam(':student_code', $student_code);
        $stmt->bindParam(':enrollment_date', $enrollment_date);
        return $stmt->execute();
    }

    // Method to get enrollees
    public function getEnrollees($course_code)
    {
        $sql = "SELECT s.student_code, CONCAT(s.first_name, ' ', s.last_name) AS student_name 
                FROM CourseEnrolments AS ce
                JOIN students AS s ON ce.student_code = s.student_code
                WHERE ce.course_code = :course_code";
        $statement = $this->db->prepare($sql);
        $statement->execute(['course_code' => $course_code]);
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }
}

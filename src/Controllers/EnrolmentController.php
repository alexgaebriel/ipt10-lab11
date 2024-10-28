<?php

namespace App\Controllers;

use App\Models\Course;
use App\Models\CourseEnrolment;
use App\Models\Student;
use App\Controllers\BaseController;
use App\Models\DatabaseConnection;

class EnrolmentController extends BaseController
{
    protected $db;

    public function __construct()
{
    // Create a new database connection
    $dbConnection = new DatabaseConnection('mysql', 'localhost', '3306', 'lab11', 'your_username', 'your_password');
    $this->db = $dbConnection->connect(); // Initialize the PDO connection

    // Check if the database connection is successful
    if (!$this->db) {
        die("Database connection failed."); // Stop execution and display error message
    }
}


    public function enrollmentForm()
    {
        $courseObj = new Course();
        $studentObj = new Student();

        $template = 'enrollment-form';
        $data = [
            'courses' => $courseObj->all(),
            'students' => $studentObj->all()
        ];

        return $this->render($template, $data);
    }

    public function enroll()
    {
        $course_code = $_POST['course_code'] ?? null;
        $student_code = $_POST['student_code'] ?? null;
        $enrollment_date = $_POST['enrollment_date'] ?? null;

        if (!$course_code || !$student_code || !$enrollment_date) {
            die("All fields are required. Please go back and fill out the form correctly.");
        }

        // Create an instance of CourseEnrolment with the database connection
        $enrollmentObj = new CourseEnrolment($this->db);
        $enrollmentObj->enroll($course_code, $student_code, $enrollment_date);

        header("Location: /courses/{$course_code}");
        exit();
    }
}


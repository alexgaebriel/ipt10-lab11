<?php

namespace App\Controllers;

use App\Models\Course;
use App\Models\CourseEnrolment;
use FPDF; // Ensure FPDF is imported here
use App\Controllers\BaseController;

class CourseController extends BaseController
{
    public function list()
    {
        $courseObj = new Course();
        $courses = $courseObj->all();

        $template = 'courses';
        $data = ['items' => $courses];
        return $this->render($template, $data);
    }

    public function viewCourse($course_code)
    {
        $courseObj = new Course();
        $course = $courseObj->find($course_code);
        $enrollees = $courseObj->getEnrollees($course_code);

        $template = 'single-course';
        $data = [
            'course' => $course,
            'enrollees' => $enrollees
        ];
        return $this->render($template, $data);
    }

    public function exportCourse($course_code) 
    {
        $courseObj = new Course();
        $course = $courseObj->find($course_code);
        $enrolmentObj = new CourseEnrolment();
        $enrollees = $enrolmentObj->getEnrollees($course_code);

        if (!$course) {
            die("Course not found.");
        }

        // Initialize FPDF and create PDF content
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(190, 10, "Course Information", 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(50, 10, 'Course Code:', 0, 0);
        $pdf->Cell(50, 10, $course->course_code, 0, 1);
        $pdf->Cell(50, 10, 'Course Name:', 0, 0);
        $pdf->Cell(50, 10, $course->course_name, 0, 1);
        $pdf->Ln(10);

        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(190, 10, "List of Enrollees", 0, 1, 'C');
        $pdf->Ln(5);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(30, 10, 'Student Code', 1);
        $pdf->Cell(80, 10, 'Student Name', 1);
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 12);
        foreach ($enrollees as $enrollee) {
            $pdf->Cell(30, 10, $enrollee->student_code, 1);
            $pdf->Cell(80, 10, $enrollee->student_name, 1);
            $pdf->Ln();
        }

        $pdf->Output('D', 'Course_'.$course_code.'_Details.pdf');
    }
}

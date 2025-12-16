<?php

class MycoursesController extends Controller
{
    private $enrollmentsModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $this->enrollmentsModel = $this->requireModel('EnrollmentsModel');
    }

    public function index()
    {
        $courses = $this->enrollmentsModel->getEnrolledCourses($_SESSION['user_id']);
        $this->requireView('my-courses/my-courses', ['courses' => $courses]);
    }
}
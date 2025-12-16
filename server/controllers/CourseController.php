<?php

class CourseController extends Controller
{
  private $model;
  public function __construct()
  {
    $this->model = $this->requireModel('CoursesModel');
  }
  public function index($courseId)
  {
    $course = $this->model->getCourseDetails($courseId);
    
    if (!$course) {
        // Redirect to courses page if course not found
        header('Location: ' . BASE_URL . '/not-found');
        exit;
    }

    $lessons = $this->model->getCourseLessons($courseId);
    $reviews = $this->model->getCourseReviews($courseId);

    $data = [
        'title' => 'Skillspire - ' . $course['title'],
        'course' => $course,
        'lessons' => $lessons,
        'reviews' => $reviews
    ];

    $this->requireView('course/course', $data);
  }
}
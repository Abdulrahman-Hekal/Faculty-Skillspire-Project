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
        header('Location: ' . BASE_URL . '/not-found');
        exit;
    }

    $lessons = $this->model->getCourseLessons($courseId);
    $reviews = $this->model->getCourseReviews($courseId);
    
    $isEnrolled = false;
    $hasReviewed = false;
    if (isset($_SESSION['user_id'])) {
        $enrollmentModel = $this->requireModel('EnrollmentsModel');
        $isEnrolled = $enrollmentModel->isEnrolled($_SESSION['user_id'], $courseId);
        
        $reviewsModel = $this->requireModel('ReviewsModel');
        $hasReviewed = $reviewsModel->hasReviewed($_SESSION['user_id'], $courseId);
    }

    $data = [
        'title' => 'Skillspire - ' . $course['title'],
        'course' => $course,
        'lessons' => $lessons,
        'reviews' => $reviews,
        'is_enrolled' => $isEnrolled,
        'has_reviewed' => $hasReviewed
    ];

    $this->requireView('course/course', $data);
  }

  public function enroll($courseId)
  {
      if (!isset($_SESSION['user_id'])) {
          header('Location: ' . BASE_URL . '/login');
          exit;
      }

      $enrollmentModel = $this->requireModel('EnrollmentsModel');
      
      if ($enrollmentModel->isEnrolled($_SESSION['user_id'], $courseId)) {
          header('Location: ' . BASE_URL . '/my-courses');
          exit;
      }

      if ($enrollmentModel->enrollUser($_SESSION['user_id'], $courseId)) {
          header('Location: ' . BASE_URL . '/my-courses');
      } else {
          die('Something went wrong');
      }
  }
  public function submitReview($courseId)
  {
      if (!isset($_SESSION['user_id'])) {
          header('Location: ' . BASE_URL . '/login');
          exit;
      }

      $enrollmentModel = $this->requireModel('EnrollmentsModel');
      if (!$enrollmentModel->isEnrolled($_SESSION['user_id'], $courseId)) {
        header('Location: ' . BASE_URL . '/course/index/' . $courseId);
        exit;
      }

      $reviewsModel = $this->requireModel('ReviewsModel');
      if ($reviewsModel->hasReviewed($_SESSION['user_id'], $courseId)) {
        header('Location: ' . BASE_URL . '/course/index/' . $courseId);
        exit;
      }

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
          
          $data = [
              'user_id' => $_SESSION['user_id'],
              'course_id' => $courseId,
              'rating' => trim($_POST['rating']),
              'comment' => trim($_POST['comment'])
          ];

          if (!empty($data['rating']) && !empty($data['comment'])) {
              if ($reviewsModel->createReview($data)) {
                  header('Location: ' . BASE_URL . '/course/index/' . $courseId);
              } else {
                  die('Something went wrong');
              }
          } else {
              header('Location: ' . BASE_URL . '/course/index/' . $courseId);
          }
      }
  }
}
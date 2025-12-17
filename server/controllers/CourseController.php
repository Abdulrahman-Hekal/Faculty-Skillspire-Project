<?php

/**
 * CourseController
 * Handles course details viewing, enrollment, and reviews.
 */
class CourseController extends Controller
{
  private $model;

  public function __construct()
  {
    $this->model = $this->requireModel('CoursesModel');
  }

  /**
   * Display course details page.
   *
   * @param int $courseId
   */
  public function index($courseId)
  {
    $course = $this->model->getCourseDetails($courseId);
    
    if (!$course) {
        header('Location: ' . BASE_URL . '/not-found');
        exit;
    }

    // Fetch Lessons
    $lessonsModel = $this->requireModel('LessonsModel');
    $lessons = $lessonsModel->getLessonsByCourseId($courseId);

    // Fetch Reviews
    $reviewsModel = $this->requireModel('ReviewsModel');
    $reviews = $reviewsModel->getReviewsByCourseId($courseId);
    
    $isEnrolled = false;
    $hasReviewed = false;
    if (isset($_SESSION['user_id'])) {
        $enrollmentModel = $this->requireModel('EnrollmentsModel');
        $isEnrolled = $enrollmentModel->isEnrolled($_SESSION['user_id'], $courseId);
        
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

  /**
   * Enroll the currently logged-in user in the course.
   *
   * @param int $courseId
   */
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
          // In a real app, set a flash message here
          header('Location: ' . BASE_URL . '/courses'); 
      }
  }

  /**
   * Submit a review for the course.
   *
   * @param int $courseId
   */
  public function submitReview($courseId)
  {
      if (!isset($_SESSION['user_id'])) {
          header('Location: ' . BASE_URL . '/login');
          exit;
      }

      $enrollmentModel = $this->requireModel('EnrollmentsModel');
      // Must be enrolled to review
      if (!$enrollmentModel->isEnrolled($_SESSION['user_id'], $courseId)) {
        header('Location: ' . BASE_URL . '/course/index/' . $courseId);
        exit;
      }

      $reviewsModel = $this->requireModel('ReviewsModel');
      // Include check to prevent duplicate reviews
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
              $reviewsModel->createReview($data);
          }
          
          // Redirect back to course page regardless of success/fail for now
          header('Location: ' . BASE_URL . '/course/index/' . $courseId);
      }
  }
}
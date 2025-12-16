<?php

class CoursesController extends Controller
{
  private $model;
  public function __construct()
  {
    $this->model = $this->requireModel('CoursesModel');
  }
  public function index()
  {
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 9;
    
    $filters = [
        'search' => $_GET['search'] ?? '',
        'category' => $_GET['category'] ?? '',
        'min_price' => $_GET['min_price'] ?? '',
        'max_price' => $_GET['max_price'] ?? '',
        'min_rating' => $_GET['min_rating'] ?? '',
        'sort_by' => $_GET['sort_by'] ?? 'created_at',
        'sort_dir' => $_GET['sort_dir'] ?? 'DESC'
    ];

    $courses = $this->model->getPublicCourses($page, $limit, $filters);
    $totalCourses = $this->model->getTotalPublicCourses($filters);
    $totalPages = ceil($totalCourses / $limit);

    $data = [
        'title' => 'Skillspire - Browse Courses',
        'courses' => $courses,
        'filters' => $filters,
        'pagination' => [
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_items' => $totalCourses
        ]
    ];

    $this->requireView('courses/courses', $data);
  }
}
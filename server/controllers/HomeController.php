<?php

class HomeController extends Controller
{
  public $model;
  public function __construct()
  {
    $this->model = $this->requireModel('CoursesModel');
  }
  public function index()
  {
    $topCourses = $this->model->getPublicCourses(1, 10, ['sort_by' => 'avg_rating', 'sort_dir' => 'DESC']);
    
    $this->requireView('home/home', [
        'title' => 'Skillspire',
        'topCourses' => $topCourses
    ]);
  }
}
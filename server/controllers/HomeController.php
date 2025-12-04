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
    $this->requireView('home/home', [
      'courses' => $this->model->getPublicCourses(),
    ]);
  }
}
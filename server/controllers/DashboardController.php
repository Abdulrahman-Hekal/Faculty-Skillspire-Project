<?php

class DashboardController extends Controller
{
  public $model;
  public function __construct()
  {
    $this->model = $this->requireModel('CoursesModel');
  }
  public function index()
  {
    $this->requireView('dashboard/dashboard');
  }
}
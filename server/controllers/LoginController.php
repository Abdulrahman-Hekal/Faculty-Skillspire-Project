<?php

class LoginController extends Controller
{
  private $userModel;

  public function __construct()
  {
    $this->userModel = $this->requireModel('UsersModel');
  }

  public function index()
  {
    $data = [
      'email' => '',
      'password' => '',
      'email_err' => '',
      'password_err' => ''
    ];

    $this->requireView('login/login', $data);
  }

  public function login()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Sanitize POST data
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

      $data = [
        'email' => trim($_POST['email']),
        'password' => trim($_POST['password']),
        'email_err' => '',
        'password_err' => ''
      ];

      // Validate Email
      if (empty($data['email'])) {
        $data['email_err'] = 'Please enter email';
      }

      // Validate Password
      if (empty($data['password'])) {
        $data['password_err'] = 'Please enter password';
      }

      // Make sure errors are empty
      if (empty($data['email_err']) && empty($data['password_err'])) {
        // Check and set logged in user
        $loggedInUser = $this->authenticate($data['email'], $data['password']);

        if ($loggedInUser) {
          // Create Session
          $this->createUserSession($loggedInUser);
        } else {
          $data['email_err'] = 'Email or password is incorrect';
          $data['password_err'] = 'Email or password is incorrect';
          // Load view with errors
          $this->requireView('login/login', $data);
        }
      } else {
        // Load view with errors
        $this->requireView('login/login', $data);
      }
    } else {
      // Redirect to login
      header('Location: ' . BASE_URL . '/login');
    }
  }

  public function authenticate($email, $password) {
    $user = $this->userModel->findUserByEmail($email);
    if(password_verify($password, $user['password'])){
        return $user;
    } else {
        return false;
    }
  }

  public function createUserSession($user)
  {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_role'] = $user['role'];
    header('Location: ' . BASE_URL);
  }

  public function logout() {
    unset($_SESSION['user_id']);
    unset($_SESSION['user_name']);
    unset($_SESSION['user_role']);
    session_destroy();
    header('Location: ' . BASE_URL . '/login');
  }
}
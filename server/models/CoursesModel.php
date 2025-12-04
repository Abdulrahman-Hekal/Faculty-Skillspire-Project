<?php

class CoursesModel extends Model
{
  public function getPublicCourses()
  {
    return $this->conn->execute_query("SELECT * FROM courses WHERE is_published = 1")->fetch_all(MYSQLI_ASSOC);
  }
}
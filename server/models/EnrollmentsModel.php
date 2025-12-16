<?php

class EnrollmentsModel extends Model
{
    public function enrollUser($userId, $courseId)
    {
        $exists = $this->isEnrolled($userId, $courseId);
        if ($exists) return false;

        $query = "INSERT INTO enrollments (user_id, course_id) VALUES (?, ?)";
        return $this->conn->execute_query($query, [$userId, $courseId]);
    }

    public function isEnrolled($userId, $courseId)
    {
        $query = "SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?";
        $result = $this->conn->execute_query($query, [$userId, $courseId]);
        return $result->num_rows > 0;
    }

    public function getEnrolledCourses($userId)
    {
        $query = "SELECT c.*, e.enrolled_at FROM courses c 
                  JOIN enrollments e ON c.id = e.course_id 
                  WHERE e.user_id = ?";
        return $this->conn->execute_query($query, [$userId])->fetch_all(MYSQLI_ASSOC);
    }
}

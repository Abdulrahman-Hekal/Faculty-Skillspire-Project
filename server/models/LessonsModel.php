<?php

class LessonsModel extends Model
{
    public function getLessonsByCourseId($courseId)
    {
        return $this->conn->execute_query("SELECT * FROM lessons WHERE course_id = ? ORDER BY `order` ASC", [$courseId])->fetch_all(MYSQLI_ASSOC);
    }

    public function getLessonById($lessonId)
    {
         return $this->conn->execute_query("SELECT * FROM lessons WHERE id = ?", [$lessonId])->fetch_assoc();
    }

    public function createLesson($data)
    {
        $query = "INSERT INTO lessons (course_id, title, video_url, content, `order`) VALUES (?, ?, ?, ?, ?)";
        $this->conn->execute_query($query, [
            $data['course_id'],
            $data['title'],
            $data['video_url'],
            $data['content'],
            $data['order']
        ]);
        return $this->conn->insert_id;
    }
    
    public function updateLesson($lessonId, $data)
    {
         $query = "UPDATE lessons SET title = ?, video_url = ?, content = ?, `order` = ? WHERE id = ?";
         return $this->conn->execute_query($query, [
            $data['title'],
            $data['video_url'],
            $data['content'],
            $data['order'],
            $lessonId
        ]);
    }

    public function deleteLesson($lessonId)
    {
        return $this->conn->execute_query("DELETE FROM lessons WHERE id = ?", [$lessonId]);
    }
}

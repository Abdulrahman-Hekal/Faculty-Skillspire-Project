<?php

/**
 * LessonsModel
 * Handles CRUD operations for lessons.
 */
class LessonsModel extends Model
{
    /**
     * Get all lessons for a specific course, ordered by sequence.
     * 
     * @param int $courseId
     * @return array
     */
    public function getLessonsByCourseId($courseId)
    {
        return $this->conn->execute_query("SELECT * FROM lessons WHERE course_id = ? ORDER BY `order` ASC", [$courseId])->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get a single lesson by ID.
     * 
     * @param int $lessonId
     * @return array|null
     */
    public function getLessonById($lessonId)
    {
         return $this->conn->execute_query("SELECT * FROM lessons WHERE id = ?", [$lessonId])->fetch_assoc();
    }

    /**
     * Create a new lesson.
     * 
     * @param array $data
     * @return int Insert ID
     */
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
    
    /**
     * Update an existing lesson.
     * 
     * @param int $lessonId
     * @param array $data
     * @return bool|mysqli_result
     */
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

    /**
     * Delete a lesson.
     * 
     * @param int $lessonId
     * @return bool|mysqli_result
     */
    public function deleteLesson($lessonId)
    {
        return $this->conn->execute_query("DELETE FROM lessons WHERE id = ?", [$lessonId]);
    }
}

<?php
require_once 'db_config.php';

class Quiz extends Bdd {
    public function __construct() {
        parent::__construct();
    }

    //1: recup tous les quizz
    public function getAllQuizzes() {
        $sql = 'SELECT * FROM quiz';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //2: recup quiz par utilisateur
    public function getQuizzesByUser($user_id) {
        $sql = 'SELECT * FROM quiz WHERE create_id = :create_id';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':create_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //3 créer quizz
    public function createQuiz($title, $description, $create_id, $category_id) {
        $sql = 'INSERT INTO quiz (title, description, create_id, category_id) VALUES (:title, :description, :create_id, :category_id)';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':create_id', $create_id, PDO::PARAM_INT);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->execute();
        return $this->conn->lastInsertId();
    }

    //4: recup quizz par son ID
    public function getQuizById($quiz_id) {
        $sql = 'SELECT quiz.*, categories.name AS category_name, categories.icon AS category_icon 
                FROM quiz 
                LEFT JOIN categories ON quiz.category_id = categories.id 
                WHERE quiz.id = :id';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $quiz_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //5: supprimer un quiz
    public function deleteQuiz($quiz_id) {
        try {
            $this->conn->beginTransaction();
    
            // suppr réponses associées aux questions du quiz
            $sql = 'DELETE FROM answers WHERE question_id IN (SELECT id FROM questions WHERE quiz_id = :quiz_id)';
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':quiz_id', $quiz_id, PDO::PARAM_INT);
            $stmt->execute();
    
            // suppr questions associées au quiz
            $sql = 'DELETE FROM questions WHERE quiz_id = :quiz_id';
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':quiz_id', $quiz_id, PDO::PARAM_INT);
            $stmt->execute();
    
            // Supprimer le quiz
            $sql = 'DELETE FROM quiz WHERE id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $quiz_id, PDO::PARAM_INT);
            $stmt->execute();
    
            $this->conn->commit();
        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw $e;
        }}
    }
?>

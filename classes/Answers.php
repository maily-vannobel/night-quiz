<?php
require_once 'db_config.php';

class Answers extends Bdd {
    public function __construct() {
        parent::__construct();
    }

    //1: 
    public function getAnswersByQuestion($question_id) {
        $sql = 'SELECT * FROM answers WHERE question_id = :question_id';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //2: 
    public function deleteAnswersByQuestion($question_id) {
        $sql = 'DELETE FROM answers WHERE question_id = :question_id';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    //3: Ajouter une réponse
    public function addAnswer($question_id, $answer_text) {
        $sql = 'INSERT INTO answers (question_id, answer) VALUES (:question_id, :answer)';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);
        $stmt->bindParam(':answer', $answer_text);
        return $stmt->execute();
    }

    //4: Mettre à jour une réponse
    public function updateAnswer($question_id, $answer_text) {
        $sql = 'UPDATE answers SET answer = :answer WHERE question_id = :question_id';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);
        $stmt->bindParam(':answer', $answer_text);
        return $stmt->execute();
    }
}
?>

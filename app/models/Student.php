<?php
namespace App\Models;

use PDO;

class Student {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM student ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByCode( $id): array
    {
        $stmt = $this->db->prepare("SELECT * FROM student WHERE id = ?");
        $stmt->execute([$id]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$student) {
            Helpers::jsonResponse(
                null,
                'error',
                404,
                [
                    'code' => 'STUDENT_NOT_FOUND',
                    'message' => "L'étudiant avec le code '$id' n'existe pas."
                ]
            );
        }

        return $student;
    }
}
<?php
namespace App\Models;

use PDO;

class Semester {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM semester ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retourne la liste des étudiants inscrits pour un semestre donné (par semester.id)
     */
    public function getStudentsBySemester($semesterId) {
        $sql = "
            SELECT DISTINCT st.*
            FROM student_semester stsem
            JOIN semester_year sy ON stsem.semester_year_id = sy.id
            JOIN student st ON stsem.student_id = st.id
            WHERE sy.semester_id = ?
            ORDER BY st.name ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$semesterId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

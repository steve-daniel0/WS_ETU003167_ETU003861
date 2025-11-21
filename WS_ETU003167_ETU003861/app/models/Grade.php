<?php
namespace App\Models;

use PDO;

class Grade
{
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    

    /**
     * Notes d’un étudiant sur un semestre donné
     */
    public function getGradesBySemester($idSemester, $idStudent)
    {
        $sql = "
            SELECT 
                sj.id AS subject_id,
                sj.name AS subject_name,
                ss.credit,
                so.label AS option_name,
                sg.grade,
                es.date AS exam_date
            FROM student_semester stsem
            JOIN semester_year sy ON stsem.semester_year_id = sy.id
            JOIN semester_subject ss ON ss.semester_id = sy.semester_id
            JOIN subject sj ON sj.id = ss.subject_id
            LEFT JOIN student_option so ON so.id = ss.option_id
            LEFT JOIN student_grade sg ON sg.student_semester_id = stsem.id 
                AND sg.subject_id = sj.id
            LEFT JOIN exam_session es ON es.id = sg.exam_session_id
            WHERE sy.semester_id = ?
              AND stsem.student_id = ?
            ORDER BY sj.name ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idSemester, $idStudent]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Notes d’un étudiant sur une année donnée
     */
    public function getGradesByYear($year, $idStudent)
    {
        $sql = "
            SELECT 
                sj.id AS subject_id,
                sj.name AS subject_name,
                ss.credit,
                so.label AS option_name,
                sg.grade,
                ss.semester_id,
                es.date AS exam_date
            FROM student_semester stsem
            JOIN semester_year sy ON stsem.semester_year_id = sy.id
            JOIN semester_subject ss ON ss.semester_id = sy.semester_id
            JOIN subject sj ON sj.id = ss.subject_id
            LEFT JOIN student_option so ON so.id = ss.option_id
            LEFT JOIN student_grade sg ON sg.student_semester_id = stsem.id 
                AND sg.subject_id = sj.id
            LEFT JOIN exam_session es ON es.id = sg.exam_session_id
            JOIN semester s ON sg.semester_id=s.id
            WHERE s.year = ?
                AND stsem.student_id = ?
            ORDER BY sj.name ASC 
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$year, $idStudent]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retourne la liste des options pour un semestre donné
     */
    public function getOptionsBySemester($semesterId)
    {
        $sql = "
            SELECT DISTINCT 
                so.id AS option_id,
                so.label AS option_name
            FROM semester_subject ss
            LEFT JOIN student_option so ON so.id = ss.option_id
            WHERE ss.semester_id = ?
            AND ss.option_id IS NOT NULL
            ORDER BY so.label ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$semesterId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Calcule la moyenne d’un étudiant sur un semestre et une option donnée
     */
    public function getAverage($semesterId, $optionId, $studentId)
    {
        $sql = "
            SELECT 
                sg.grade,
                ss.credit
            FROM student_grade sg
            JOIN student_semester stsem ON sg.student_semester_id = stsem.id
            JOIN semester_subject ss ON ss.subject_id = sg.subject_id 
                AND ss.semester_id = sg.semester_id
            WHERE sg.semester_id = ?
            AND stsem.student_id = ?
            AND (
                    (? IS NULL AND ss.option_id IS NULL)
                    OR
                    (? IS NOT NULL AND ss.option_id = ?)
                )
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $semesterId,
            $studentId,
            $optionId,   // pour NULL case
            $optionId,   // pour match case
            $optionId
        ]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($rows)) {
            return null; // aucune note
        }

        $totalWeighted = 0;
        $totalCredits = 0;

        foreach ($rows as $r) {
            $totalWeighted += $r['grade'] * $r['credit'];
            $totalCredits  += $r['credit'];
        }

        return ($totalCredits > 0) ? round($totalWeighted / $totalCredits, 2) : null;
    }

    /**
     * Retourne la mention correspondant à une moyenne
     */
    public function getMentionByAverage($average)
    {
        $sql = "
            SELECT description
            FROM grade_mention
            WHERE ? BETWEEN note_min AND note_max
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$average]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $result['description'] : null;
    }

    /**
     * Retourne les notes, moyenne et mention pour un étudiant pour le semestre 4 et une option donnée
     */
    public function getS4ByOption($studentId, $optionId)
    {
        $semesterId = 4;

        // Récupération des notes pour ce semestre et cette option
        $sql = "
            SELECT 
                sj.name AS subject_name,
                sg.grade,
                ss.credit
            FROM student_semester stsem
            JOIN semester_subject ss ON ss.semester_id = ? 
                AND ss.option_id = ?
                AND ss.subject_id IN (
                    SELECT subject_id FROM student_grade WHERE student_semester_id = stsem.id
                )
            JOIN subject sj ON sj.id = ss.subject_id
            JOIN student_grade sg ON sg.student_semester_id = stsem.id 
                AND sg.subject_id = ss.subject_id
            WHERE stsem.student_id = ?
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$semesterId, $optionId, $studentId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($rows)) {
            return null; // pas de notes
        }

        // Calcul de la moyenne pondérée
        $totalWeighted = 0;
        $totalCredits = 0;
        foreach ($rows as $r) {
            $totalWeighted += $r['grade'] * $r['credit'];
            $totalCredits  += $r['credit'];
        }
        $average = ($totalCredits > 0) ? round($totalWeighted / $totalCredits, 2) : null;

        // Mention correspondante
        $mention = $average !== null ? $this->getMentionByAverage($average) : null;

        return [
            "grades"  => $rows,
            "average" => $average,
            "mention" => $mention
        ];
    }


}

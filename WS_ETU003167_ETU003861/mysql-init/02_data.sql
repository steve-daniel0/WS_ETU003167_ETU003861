INSERT INTO student VALUES
(1, 'Smith', 'John', '2002-05-10', 'ETU001'),
(2, 'Johnson', 'Emma', '2001-08-20', 'ETU002');

INSERT INTO semester VALUES
(1, 'Semester 1', 1),
(2, 'Semester 2', 1),
(3, 'Semester 3', 2),
(4, 'Semester 4', 2);

INSERT INTO academic_year VALUES
(1, '2024-01-01', '2024-12-31'),
(2, '2025-01-01', '2025-12-31');

INSERT INTO subject VALUES
-- Semester 1
(1, 'Mathematics I', 'UE1'),
(2, 'Programming Basics', 'UE1'),
(3, 'Communication Skills', 'UE2'),

-- Semester 2
(4, 'Algorithms', 'UE1'),
(5, 'Database Basics', 'UE2'),
(6, 'Operating Systems', 'UE2'),

-- Semester 3
(7, 'Advanced Programming', 'UE1'),
(8, 'Computer Networks', 'UE2'),
(9, 'Statistics', 'UE1'),

-- Semester 4 (initial subjects)
(10, 'Web Development', 'UE1'),
(11, 'Mobile Development', 'UE2'),
(12, 'Database Optimization', 'UE2'),

-- NEW subjects for options (2 each)
-- DEV OPTION (id 1)
(13, 'Software Architecture', 'UE3'),
(14, 'Clean Code Principles', 'UE3'),

-- WEB OPTION (id 2)
(15, 'Frontend Frameworks', 'UE3'),
(16, 'UX/UI Advanced', 'UE3'),

-- DB OPTION (id 3)
(17, 'SQL Advanced', 'UE3'),
(18, 'Data Warehouse Concepts', 'UE3');

INSERT INTO grade_mention VALUES
(1, 0, 5, 'Très insuffisant', TRUE),
(2, 5.01, 10, 'Insuffisant', FALSE),
(3, 10.01, 12, 'Passable', FALSE),
(4, 12.01, 14, 'Assez bien', FALSE),
(5, 14.01, 16, 'Bien', FALSE),
(6, 16.01, 20, 'Très bien', FALSE);

INSERT INTO student_option VALUES
(1, 'dev'),
(2, 'web'),
(3, 'db');

INSERT INTO semester_year VALUES
(1, 1, 1, '2024-02-01', '2024-06-30'),
(2, 2, 1, '2024-07-01', '2024-12-15'),
(3, 3, 2, '2025-02-01', '2025-06-30'),
(4, 4, 2, '2025-07-01', '2025-12-15');

/* Semester 1 */
INSERT INTO semester_subject VALUES
(1, 1, 1, NULL, 4),
(2, 1, 2, NULL, 4),
(3, 1, 3, NULL, 2);

/* Semester 2 */
INSERT INTO semester_subject VALUES
(4, 2, 4, NULL, 4),
(5, 2, 5, NULL, 3),
(6, 2, 6, NULL, 3);

/* Semester 3 */
INSERT INTO semester_subject VALUES
(7, 3, 7, NULL, 4),
(8, 3, 8, NULL, 3),
(9, 3, 9, NULL, 3);

/* Semester 4 with options */
-- initial subjects
INSERT INTO semester_subject VALUES
(10, 4, 10, 1, 4),
(11, 4, 11, 2, 4),
(12, 4, 12, 3, 4),

-- DEV option (new)
(13, 4, 13, 1, 3),
(14, 4, 14, 1, 3),

-- WEB option (new)
(15, 4, 15, 2, 3),
(16, 4, 16, 2, 3),

-- DB option (new)
(17, 4, 17, 3, 3),
(18, 4, 18, 3, 3);

INSERT INTO student_semester VALUES
(1, 1, 1, '2024-02-01'),
(2, 1, 2, '2024-07-01'),
(3, 1, 3, '2025-02-01'),
(4, 1, 4, '2025-07-01'),

(5, 2, 1, '2024-02-01'),
(6, 2, 2, '2024-07-01'),
(7, 2, 3, '2025-02-01'),
(8, 2, 4, '2025-07-01');

INSERT INTO exam_session VALUES
(1, '2024-06-20'),
(2, '2024-12-10'),
(3, '2025-06-20'),
(4, '2025-12-10');

/* ================================
   INSERTION DES NOTES (student_grade)
================================ */

-- John Smith
INSERT INTO student_grade (subject_id, grade, student_semester_id, exam_session_id, semester_id) VALUES
-- Semester 1
(1, 14, 1, 1, 1), (2, 12, 1, 1, 1), (3, 15, 1, 1, 1),
-- Semester 2
(4, 13, 2, 2, 2), (5, 11, 2, 2, 2), (6, 16, 2, 2, 2),
-- Semester 3
(7, 14, 3, 3, 3), (8, 10, 3, 3, 3), (9, 17, 3, 3, 3),
-- Semester 4 BASE subjects
(10, 15, 4, 4, 4), (11, 14, 4, 4, 4), (12, 16, 4, 4, 4),
-- Semester 4 NEW DEV option subjects
(13, 17, 4, 4, 4),
(14, 16, 4, 4, 4);

-- Emma Johnson
INSERT INTO student_grade (subject_id, grade, student_semester_id, exam_session_id, semester_id) VALUES
-- Semester 1
(1, 11, 5, 1, 1), (2, 13, 5, 1, 1), (3, 12, 5, 1, 1),
-- Semester 2
(4, 15, 6, 2, 2), (5, 14, 6, 2, 2), (6, 10, 6, 2, 2),
-- Semester 3
(7, 16, 7, 3, 3), (8, 12, 7, 3, 3), (9, 11, 7, 3, 3),
-- Semester 4 BASE subjects
(10, 14, 8, 4, 4), (11, 15, 8, 4, 4), (12, 13, 8, 4, 4),
-- Semester 4 NEW WEB & DB option subjects
(15, 16, 8, 4, 4),
(16, 17, 8, 4, 4),
(17, 15, 8, 4, 4),
(18, 14, 8, 4, 4);

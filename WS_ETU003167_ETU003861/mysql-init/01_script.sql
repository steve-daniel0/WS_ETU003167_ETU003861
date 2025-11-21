CREATE TABLE student (
    id INT PRIMARY KEY,
    name VARCHAR(100),
    first_name VARCHAR(100),
    birth DATE,
    student_code VARCHAR(20)
);

CREATE TABLE semester (
    id INT PRIMARY KEY,
    label VARCHAR(50),
    year INT
);

CREATE TABLE subject (
    id INT PRIMARY KEY,
    name VARCHAR(100),
    ue VARCHAR(50)
);

CREATE TABLE grade_mention (
    id INT PRIMARY KEY,
    note_min FLOAT,
    note_max FLOAT,
    description VARCHAR(50),
    is_eliminatory BOOLEAN
);

CREATE TABLE academic_year (
    id INT PRIMARY KEY ,
    date_start DATE,
    date_end DATE
);

CREATE TABLE semester_year (
    id INT PRIMARY KEY,
    semester_id INT,
    academic_year_id INT,
    date_start DATE,
    date_end DATE,
    FOREIGN KEY (semester_id) REFERENCES semester(id),
    FOREIGN KEY (academic_year_id) REFERENCES academic_year(id)
);

CREATE TABLE student_option (
    id INT PRIMARY KEY,
    label VARCHAR(100)
);

CREATE TABLE semester_subject (
    id INT PRIMARY KEY,
    semester_id INT,
    subject_id INT,
    option_id INT DEFAULT NULL,
    credit INT,
    FOREIGN KEY (option_id) REFERENCES student_option(id),
    FOREIGN KEY (semester_id) REFERENCES semester(id),
    FOREIGN KEY (subject_id) REFERENCES subject(id)
);

CREATE TABLE student_semester (
    id INT PRIMARY KEY,
    student_id INT,
    semester_year_id INT,
    registration_date DATE,
    FOREIGN KEY (student_id) REFERENCES student(id),
    FOREIGN KEY (semester_year_id) REFERENCES semester_year(id)
);

CREATE TABLE exam_session (
    id INT PRIMARY KEY,
    date DATE
);

CREATE TABLE student_grade (
    id INT PRIMARY KEY AUTO_INCREMENT,
    subject_id INT,
    grade FLOAT,
    student_semester_id INT,
    exam_session_id INT,
    semester_id INT,
    FOREIGN KEY (semester_id) REFERENCES semester(id),
    FOREIGN KEY (student_semester_id) REFERENCES student_semester(id),
    FOREIGN KEY (subject_id) REFERENCES subject(id),
    FOREIGN KEY (exam_session_id) REFERENCES exam_session(id)
);

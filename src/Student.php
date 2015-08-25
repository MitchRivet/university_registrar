<?php

    class Student
    {
        private $student_name;
        private $enrollment_date;
        private $id;

        function __construct($student_name, $enrollment_date, $id = null)
        {
            $this->student_name = $student_name;
            $this->enrollment_date = $enrollment_date;
            $this->id = $id;
        }

        //Setters
        function setStudentName($new_student_name)
        {
            $this->student_name = (string) $new_student_name;
        }

        function setEnrollmentDate($new_enrollment_date)
        {
            $this->enrollment_date = (string) $new_enrollment_date;
        }

        //Getters
        function getStudentName()
        {
            return $this->student_name;
        }

        function getEnrollmentDate()
        {
            return $this->enrollment_date;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO students (student_name, enrollment_date) VALUES (
                '{$this->getStudentName()}',
                '{$this->getEnrollmentDate()}'
            );");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function addCourse($course)
        {
            $GLOBALS['DB']->exec("INSERT INTO students_courses (student_id, course_id) VALUES ({$this->getId()},
            {$course->getId()});");
        }

        function getCourses()
        {
            $matching_courses = $GLOBALS['DB']->query(
                "SELECT courses.* FROM
                    students JOIN students_courses ON (students.id = students_courses.student_id)
                             JOIN courses ON (students_courses.course_id = courses.id)
                 WHERE students.id = {$this->getId()};
                "
            );
            $courses = array();
            foreach($matching_courses as $course) {
                $course_name = $course['course_name'];
                $course_number = $course['course_number'];
                $id = $course['id'];
                $new_course = new Course($course_name, $course_number, $id);
                array_push($courses, $new_course);
            }
            return $courses;
        }

        static function getAll()
        {
            $returned_students = $GLOBALS['DB']->query("SELECT * FROM students ORDER BY enrollment_date");
            $students = array();
            foreach ($returned_students as $student) {
                $student_name = $student['student_name'];
                $enrollment_date = $student['enrollment_date'];
                $id = $student['id'];
                $new_student = new Student($student_name, $enrollment_date, $id);
                array_push($students, $new_student);
            }
            return $students;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM students;");
            $GLOBALS['DB']->exec("DELETE FROM students_courses;");
        }

        static function find($search_id)
        {
            $found_student = null;
            $students = Student::getAll();
            foreach ($students as $student) {
                $student_id = $student->getId();
                if ($student_id == $search_id) {
                    $found_student = $student;
                }
            }
            return $found_student;
        }

        function update($new_student_name)
        {
            $GLOBALS['DB']->exec("UPDATE students SET student_name = '{$new_student_name}' WHERE id = {$this->getId()};");
            $this->setStudentName($new_student_name);
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM students WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM students_courses WHERE student_id = {$this->getId()};");
        }
    }

?>

<?php
    class Course
    {
        private $course_name;
        private $course_number;
        private $id;

        function __construct($course_name, $course_number, $id = null)
        {
            $this->course_name = $course_name;
            $this->course_number = $course_number;
            $this->id = $id;
        }

        function setCourseName($new_course_name)
        {
            $this->course_name = (string) $new_course_name;
        }

        function getCourseName()
        {
            return $this->course_name;
        }

        function getId()
        {
            return $this->id;
        }

        function setCourseNumber($new_course_number)
        {
            $this->course_number = (string) $new_course_number;
        }

        function getCourseNumber()
        {
            return $this->course_number;
        }

        function addStudentName($student_name)
        {
            $GLOBALS['DB']->exec("INSERT INTO students_courses (student_id, course_id) VALUES ({$this->getId()}, {$student_name->getId()});");
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO courses (course_name) VALUES ('{$this->getCourseName()}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function update($new_course_name)
        {
            $GLOBALS['DB']->exec("DELETE FROM courses WHERE id = {$this->getId()};");
            $this->setCourseName($new_course_name);
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM courses WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM students_courses WHERE course_id = {$this->getId()};");
        }

        function getStudentNames()
        {
            //get and array of student_ids which match this object's course id
            $query = $GLOBALS['DB']->query("SELECT student_id FROM students_courses WHERE course_id = {$this->getId()};");
            $student_ids = $query->fetchAll(PDO::FETCH_ASSOC);

            //for each student_id matching this course, get the actual student info from
            // the students table and convert each row into a Student object.
            //Finally, return these matching student objects.

            $students = array();
            foreach ($student_ids as $id) {
                $student_id = $id['student_id'];
                $student_query = $GLOBALS['DB']->query("SELECT * FROM students WHERE id = {$student_id};");
                $returned_student = $student_query->fetchAll(PDO::FETCH_ASSOC);

                //access only one result at a time from the returned_student array using [0]
                $student_name = $returned_student[0]['student_name'];
                $id = $returned_student[0]['id'];
                $enrollment_date = $returned_student[0]['enrollment_date'];
                $new_student = new Student($student_name, $enrollment_date, $id);
                array_push($students, $new_student);
            }
            return $students;
        }

        static function getAll()
        {
            $returned_courses = $GLOBALS['DB']->query("SELECT * FROM courses;");
            $courses = array();
            foreach ($returned_courses as $course) {
                $course_name = $course['course_name'];
                $course_number = $course['course_number'];
                $id = $course['id'];
                $new_course = new Course($course_name, $course_number, $id);
                array_push($courses, $new_course);

            }
            return $courses;
        }
        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM courses;");
        }

        static function find($search_id)
        {
            $found_course = null;
            $courses = Course::getAll();
            foreach ($courses as $course) {
            $course_id = $course->getId();
            if ($course_id == $search_id){
                $found_course = $course;
            }
            }
            return $found_course;
        }

    }
?>

<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Student.php";
    require_once "src/Course.php";

    $server = 'mysql:host=localhost;dbname=university_registrar_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class StudentTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Student::deleteAll();
            Course::deleteAll();
        }

        function test_getStudentName()
        {
            //Arrange
            $student_name = "Batman";
            $enrollment_date = "1932-12-12";
            $student = new Student($student_name, $enrollment_date);

            //Act
            $result = $student->getStudentName();

            //Assert
            $this->assertEquals($student_name, $result);
        }

        function test_setStudentName()
        {
            //Arrange
            $student_name = "Bruce";
            $enrollment_date = "1932-12-11";
            $student = new Student($student_name, $enrollment_date);

            //Act
            $student->setStudentName("Batman");
            $result = $student->getStudentName();

            //Assert
            $this->assertEquals("Batman", $result);
        }


        function test_getId()
        {
            //Arrange
            $student_name = "Mitchell";
            $enrollment_date = "1800-02-08";
            $student = new Student($student_name, $enrollment_date);
            $student->save();

            //Act
            $result = $student->getId();

            //Assert
            $this->assertEquals(true, is_numeric($result));
        }

        function test_save()
        {
            //Arrange
            $student_name = "Kevin";
            $enrollment_date = "1989-12-12";
            $id = 1;
            $student = new Student($student_name, $enrollment_date, $id);

            //Act
            $student->save();

            //Assert
            $result = Student::getAll();
            $this->assertEquals($student, $result[0]);
        }

        function test_saveSetsId()
        {
            //Arrange
            $student_name = "Superman";
            $id = 1;
            $student = new Student($student_name,$id);

            //Act
            $student->save();

            //Assert
            $this->assertEquals(true, is_numeric($student->getId()));
        }

        function test_getAll()
        {
            //Arrange
            $student_name = "Spiderman";
            $enrollment_date = "2015-09-16";
            $id = 1;
            $student = new Student($student_name, $enrollment_date, $id);
            $student->save();

            $student_name2 = "John";
            $enrollment_date2 = "2014-09-09";
            $id2 = 2;
            $student2 = new Student($student_name2, $enrollment_date2, $id2);
            $student2->save();

            //Act
            $result = Student::getAll();

            //Assert
            //$student2 comes before $student because the array is ordered
            //by the enrollment date.
            $this->assertEquals([$student2, $student], $result);
        }

        function test_deleteAll()
        {
            //Arrange
            $student_name = "Joker";
            $enrollment_date = "6000-12-14";
            $id = 1;
            $student = new Student($student_name, $enrollment_date, $id);
            $student->save();

            $student_name2 = "Riddler";
            $enrollment_date2 = "7000-08-09";
            $id2 = 2;
            $student2 = new Student($student_name2, $enrollment_date2, $id2);
            $student2->save();

            //Act
            Student::deleteAll();

            //Assert
            $result = Student::getAll();
            $this->assertEquals([], $result);
        }

        function test_find()
        {
            //Arrange
            $student_name = "Joker";
            $enrollment_date = "6000-12-14";
            $id = 1;
            $student = new Student($student_name, $enrollment_date, $id);
            $student->save();

            $student_name2 = "Riddler";
            $enrollment_date2 = "7000-08-09";
            $id2 = 2;
            $student2 = new Student($student_name2, $enrollment_date2, $id2);
            $student2->save();

            //Act
            $result = Student::find($student->getId());

            //Assert
            $this->assertEquals($student, $result);
        }

        function test_update()
        {
            //Arrange
            $student_name = "Joker";
            $enrollment_date = "6000-12-14";
            $id = 1;
            $student = new Student($student_name, $enrollment_date, $id);
            $student->save();

            $new_student_name = "Spot";

            //Act
            $student->update($new_student_name);

            //Assert
            $this->assertEquals("Spot", $student->getStudentName());
        }

        function test_deleteStudent()
        {
            //Arrange
            $student_name = "Joker";
            $enrollment_date = "6000-12-14";
            $id = 1;
            $student = new Student($student_name, $enrollment_date, $id);
            $student->save();

            $student_name2 = "Riddler";
            $enrollment_date2 = "7000-08-09";
            $id2 = 2;
            $student2 = new Student($student_name2, $enrollment_date2, $id2);
            $student2->save();

            //Act
            $student->delete();

            //Assert
            $this->assertEquals([$student2], Student::getAll());
        }

        // *Test not currently working
        //
        function test_addCourse()
        {
            //Arrange
            $course_name = "Being lazy";
            $course_number = 20;
            $test_course = new Course($course_name, $course_number);
            $test_course->save();

            $student_name = "Jeff Lebowski";
            $enrollment_date = "6000-12-14";
            $test_student = new Student($student_name, $enrollment_date);
            $test_student->save();

            //Act
            $test_student->addCourse($test_course);

            //Assert
            $this->assertEquals([$test_course], $test_student->getCourses());
        }


            // *also currently not working
            //
            function test_getCourses()
            {
                //Arrange
                $course_name = "Being a bum";
                $id = 1;
                $test_course = new Course($course_name, $id);
                $test_course->save();

                $course_name2 = "Getting a toe";
                $id2 = 2;
                $test_course2 = new Course($course_name2, $id2);
                $test_course2->save();

                $student_name = "Jeff Lebowski";
                $id3 = 3;
                $test_student = new Student($student_name, $id3);
                $test_student->save();

                //Act
                $test_student->addCourse($test_course);
                $test_student->addCourse($test_course2);

                //Assert
                $this->assertEquals($test_student->getCourses(), [$test_course, $test_course2]);
            }












    }
?>

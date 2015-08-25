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

    class CourseTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Student::deleteAll();
            Course::deleteAll();
        }

        function testGetCourseName()
        {
            $course_name = "Vampires";
            $course_number = 1;
            $id = 1;
            $test_course = new Course($course_name, $course_number, $id);

            $result = $test_course->getCourseName();

            $this->assertEquals($course_name, $result);
        }

        function testCourseId()
        {
            $course_name = "Vampires";
            $course_number = 1;
            $id = 1;
            $test_course = new Course($course_name, $course_number, $id);

            $result = $test_course->getId();

            $this->assertEquals($id, $result);
        }

        function testSave()
        {
            $course_name = "Vampires";
            $course_number = 1;
            $id = 1;
            $test_course = new Course($course_name, $course_number, $id);
            $test_course->save();

            $result = Course::getAll();

            $this->assertEquals($test_course, $result[0]);
        }

        function testGetAll()
        {
            $course_name = "Vampires";
            $course_number = 1;
            $id = 1;
            $test_course = new Course($course_name, $course_number, $id);
            $test_course->save();

            $course_name2 = "Discrete Math";
            $course_number2 = 1;
            $id2 = 2;
            $test_course2 = new Course($course_name2, $course_number2, $id2);
            $test_course2->save();

            $result = Course::getAll();

            $this->assertEquals([$test_course, $test_course2], $result);

        }

        function testDelete()
        {
            //Arrange
            $course_name = "Vampires";
            $course_number = 1;
            $id = 1;
            $test_course = new Course($course_name, $course_number, $id);
            $test_course->save();

            $course_name2 = "Discrete Math";
            $course_number2 = 1;
            $id2 = 2;
            $test_course2 = new Course($course_name2, $course_number2, $id2);
            $test_course2->save();

            //Act
            $test_course->delete();

            //Assert
            $this->assertEquals([$test_course2], Course::getAll());

        }

        function testFind()
        {
            $course_name = "Vampires";
            $course_number = 1;
            $id = 1;
            $test_course = new Course($course_name, $course_number, $id);
            $test_course->save();

            $course_name2 = "Discrete Math";
            $course_number2 = 1;
            $id2 = 2;
            $test_course2 = new Course($course_name2, $course_number2, $id2);
            $test_course2->save();

            $find_id = $test_course->getId();
            $result = Course::find($find_id);

            $this->assertEquals($test_course, $result);
        }

        function testUpdate()
        {
            $course_name = "Vampires";
            $course_number = 1;
            $id = 1;
            $test_course = new Course($course_name, $course_number, $id);
            $test_course->save();

            $course_name2 = "Discrete Math";
            $course_number2 = 1;
            $id2 = 2;
            $test_course2 = new Course($course_name2, $course_number2, $id2);
            $test_course2->save();

            $test_course->update("BOBBIDY BEE BOO");

            $result = $test_course->getCourseName();

            $this->assertEquals("BOBBIDY BEE BOO", $result);
        }
    }
?>

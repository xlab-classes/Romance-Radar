<?php declare(strict_types=1);

# Import db_api.php
require_once('db_api.php');
use PHPUnit\Framework\TestCase;

final class TestCreateUser extends TestCase
{

    public function setUp(): void
    {
        $host = "oceanus.cse.buffalo.edu";
        $user = "alexeast";
        $db = "cse442_2022_spring_team_j_db";
        $pass = "50252636";

        $connection = new mysqli($host, $user, $pass, $db);
        if ($connection->connect_error) {
            print("Failed to connect in TestCreateUser::setUp()\n");
            return;
        }

        $drops = array(
            "TRUNCATE TABLE IF EXISTS Food",
            "TRUNCATE TABLE IF EXISTS Entertainment",
            "TRUNCATE TABLE IF EXISTS Venue",
            "TRUNCATE TABLE IF EXISTS Date_time",
            "TRUNCATE TABLE IF EXISTS Date_preferences",
            "TRUNCATE TABLE IF EXISTS Date_liked",
            "TRUNCATE TABLE IF EXISTS Date_disliked",
            "TRUNCATE TABLE IF EXISTS Suggested_dates",
            "TRUNCATE TABLE IF EXISTS Date_ideas",
            "TRUNCATE TABLE IF EXISTS Connection_requests",
            "TRUNCATE TABLE IF EXISTS Users"
        );

        for ($i=0; $i<count($drops); ++$i) {
            $connection->$query($drops[$i]);
        }
    }

    public function testUserDoesntExist(): void
    {
        $create_result = create_user(
            "Jon Doe", "jon.doe@gmail.com", "password", "123 Apple Orchard Rd",
            14541, "1980/01/12");
        $this->assertSame(1, $create_result);

        $result = exec_query("SELECT * FROM Users WHERE email=?", ["jon.doe@gmail.com"]);
        $this->assertNotNull($result);
        $this->assertNotFalse($result);
        $this->assertSame(1, $result->num_rows);

        $arr = $result->fetch_assoc();
        $this->assertNotNull($arr);
        $this->assertNotFalse($arr);
        
        $this->assertSame($arr["name"], "Jon Doe");
        $this->assertSame($arr["email"], "jon.doe@gmail.com");
        $this->assertSame($arr["password"], "password");
        $this->assertSame($arr["street_address"], "123 Apple Orchard Rd");
        $this->assertSame($arr["zipcode"], 14541);
        $this->assertSame($arr["birthday"], "1980-01-12");
    }

    public function testUserExists(): void
    {
        $create_result = create_user(
            "Jon Doe", "jon.doe@gmail.com", "password", "123 Apple Orchard Rd", 14541, "1980/01/12");
        
        # The call to create_user should fail
        $this->assertSame(0, $create_result);
    }

}


?>

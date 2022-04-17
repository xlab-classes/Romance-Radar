<?php declare(strict_types=1);

# Import db_api.php
require_once('../db_api.php');
use PHPUnit\Framework\TestCase;

final class TestDateSuggested extends TestCase
{
    private $id;
    private $name = "Jon Doe";
    private $email = "jon_doe@yahoo.co";
    private $address = "123 Side Street";
    private $zip = 12345;
    private $city = "Buffalo";
    private $password = "password";
    private $birthday = "1999-12-12";

    // This function is run *before every unit test*
    function setUp(): void
    {
        create_user(
            $this->name, $this->email, $this->password, $this->address, $this->city, $this->zip, $this->birthday
        );

        // Set the member variable id
        $this->id = get_user_id($this->email);
        $this->assertGreaterThan(0, $this->id, "Error getting ID of user in setUp() function");
    }

    // This function is run *after every unit test*
    function tearDown(): void
    {
        exec_query("DELETE FROM Users WHERE name=? AND email=?", [$this->name, $this->email]);
    }

    function testBasic(): void
    {
        $date_id = 1;
        $query = "SELECT * FROM Date_counts WHERE id=? AND date_id=?";
        $data = [$this->id, $date_id];

        for($i=1; $i<5; $i++) {
            $this->assertEquals(1, date_suggested($this->id, $date_id), "Couldn't increment");
            
            $result = exec_query($query, $data);
            $this->assertNotNull($result, "Couldn't exec query");
            $this->assertEquals(1, $result->num_rows, "Wrong num results from Date_counts");

            $suggested = $result->fetch_assoc()["suggested"];
            $this->assertEquals($i, $suggested, "Wrong number of suggestions");
        }
    }

}
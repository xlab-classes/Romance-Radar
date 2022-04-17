<?php declare(strict_types=1);

require_once('../db_api.php');
use PHPUnit\Framework\TestCase;

final class TestGetTimesSuggested extends TestCase
{
    // Private variables. Can be accessed inside any unit test with
    // $this->id , etc.
    private $id;
    private $prefs;
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
        // Suggest the date 4 times. Make sure the proper number is returned
        // after every suggestion
        for ($i=1; $i<5; $i++) {
            $this->assertEqual(1, date_suggested($this->id, 1), "Couldn't record suggestion");
            $this->assertEqual($i, get_times_suggested($this->id, 1), "Couldn't get times suggested");
        }
    }

}
<?php declare(strict_types=1);

# Import db_api.php
require_once('../db_api.php');
require_once('./utility.php');

use PHPUnit\Framework\TestCase;

final class TestGetTimesSuggested extends TestCase
{
    private $prefs;
    private $address = "123 Side Street";
    private $zip = 12345;
    private $city = "Buffalo";
    private $password = "password";
    private $birthday = "1999-12-12";
 
    private $jon;
    private $jon_email = "jon_doe@yahoo.co";

    private $jane;
    private $jane_email = "jane_doe@yahoo.co";

    // This function is run *before every unit test*
    function setUp(): void
    {
        create_user(
            "Jon Doe", $this->jon_email, $this->password, $this->address, $this->city, $this->zip, $this->birthday
        );

        create_user(
            "Jane Doe", $this->jane_email, $this->password, $this->address, $this->city, $this->zip, $this->birthday
        );

        $this->jon = get_user_id($this->jon_email);
        $this->assertGreaterThan(0, $this->jon, "Error getting ID of user in setUp() function");

        $this->jane = get_user_id($this->jane_email);
        $this->assertGreaterThan(0, $this->jane, "Error getting ID of user in setUp() function");

        // Set the member variable prefs so that only coffee is suggested
        $this->prefs = array(
            "Food" => array(
                "cafe" => 1
            )
        );
    }

    // This function is run *after every unit test*
    function tearDown(): void
    {
        exec_query("DELETE FROM Users WHERE name=? AND email=?", ["Jon Doe", $this->jon_email]);
        exec_query("DELETE FROM Users WHERE name=? AND email=?", ["Jane Doe", $this->jane_email]);
    }

    function testBasic(): void
    {
        // Connect the users
        $this->assertEquals(1, connect_users($this->jon, $this->jane), "Couldn't connect users");

        // Generate dates for Jane and Jon. Should be one date, which is Tim Hortons
        $dates = generate_dates($this->jon, $this->jane);
        $this->assertNotNull($dates, "Couldn't generate dates");
        $this->assertEquals(1, sizeof($dates), "More dates than expected");
        $this->assertEquals(1, $dates[0], "Didn't suggest expected date");

        // Say that this date was suggested for each user
        
    }

}
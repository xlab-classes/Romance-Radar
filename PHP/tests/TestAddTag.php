<?php declare(strict_types=1);

# Import db_api.php
require_once('../db_api.php');
use PHPUnit\Framework\TestCase;

final class TestAddTag extends TestCase
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
        $this->id = get_user_id($this->email_a);
        $this->assertGreaterThan(0, $this->id_a, "Error getting ID of user in setUp() function");

        // Set the member variable prefs
        // Anything with a 1 is "accepted", anything with a 0 in not
        $this->prefs = array(
            "Food" => array(
                "restaurant" => 1,
                "cafe" => 1,
                "fast_food" => 1,
                "alcohol" => 1
            ),
            "Entertainment" => array(
                "concerts" => 1,
                "hiking" => 1,
                "bar" => 1
            ),
            "Venue" => array(
                "indoors" => 1,
                "outdoors" => 1,
                "social_events" => 1
            ),
            "Date_time" => array(
                "morning" => 1,
                "afternoon" => 1,
                "evening" => 1
            ),
            "Date_preferences" => array(
                "cost" => 1000,
                "distance" => 1000,
                "length" => 1000
            ),
        );
    }

    // This function is run *after every unit test*
    function tearDown(): void
    {
        exec_query("DELETE FROM Users WHERE name=? AND email=?", [$this->name, $this->email]);
    }

    function testBasic(): void
    {
        $coffee = get_date_id("Tim Hortons");
        $pasta = get_date_id("Chef's");
        $hiking = get_date_id("Chestnut Ridge");

        $this->assertEquals(add_tag($coffee, "cafe"), 1, "Couldn't add tag for Tim Hortons");
        $this->assertEquals(add_tag($pasta, "restaurant"), 1, "Couldn't add tag for Chef's");
        $this->assertEquals(add_tag($hiking, "outdoors"), 1, "Couldn't add tag for Chestnut Ridge");

        // Check the tag for Tim Horton's
        $query = "SELECT * FROM Date_tags WHERE date_id=?";
        $data = [$coffee];
        $result = exec_query($query, $data);
        
        $this->assertNotNull($result, "Failed to exec query for Tim Hortons");
        $this->assertGreaterThan(0, $result->num_rows, "There are no tags for Tim Hortons");

        $tag = $result->fetch_assoc()["tag"];
        $this->assertEqual("cafe", $tag, "Wrong tag added to Tim Hortons");

        // Check the tag for Chef's
        $query = "SELECT * FROM Date_tags WHERE date_id=?";
        $data = [$pasta];
        $result = exec_query($query, $data);
        
        $this->assertNotNull($result, "Failed to exec query for Chef's");
        $this->assertGreaterThan(0, $result->num_rows, "There are no tags for Chef's");

        $tag = $result->fetch_assoc()["tag"];
        $this->assertEqual("restaurant", $tag, "Wrong tag added to Chef's");

        // Check the tag for Chestnut Ridge
        $query = "SELECT * FROM Date_tags WHERE date_id=?";
        $data = [$hiking];
        $result = exec_query($query, $data);
        
        $this->assertNotNull($result, "Failed to exec query for Chestnut Ridge");
        $this->assertGreaterThan(0, $result->num_rows, "There are no tags for Chestnut Ridge");

        $tag = $result->fetch_assoc()["tag"];
        $this->assertEqual("outdoors", $tag, "Wrong tag added to Chestnut Ridge");

    }

}
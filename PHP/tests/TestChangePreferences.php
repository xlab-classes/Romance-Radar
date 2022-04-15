<?php declare(strict_types=1);

# Import db_api.php
require_once('../db_api.php');
use PHPUnit\Framework\TestCase;

final class TestChangePreferences extends TestCase
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
    function testFoodPreferencesChanged(){

        // Create an array conatinng fast_food and alcohol
        $food_prefs = array("fast_food", "alcohol");

        // Change the food preferences
        change_food_preferences($this->id,$this->prefs, $food_prefs);

        // Get the value of the food preferences
        $prefs = get_preferences($this->id);

        // Check that fast_food and alcohol are now 0
        $this->assertEquals(0, $prefs["Food"]["fast_food"], "fast_food is not 0");
        $this->assertEquals(0, $prefs["Food"]["alcohol"], " Alcohol should be 0");
        }

}

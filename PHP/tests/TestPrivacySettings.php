<?php declare(strict_types=1);


# Import db_api.php
require_once('../db_api.php');
require_once('../privacy_settings.php');
use PHPUnit\Framework\TestCase;

// THE CLASS NAME NEEDS TO BE CHANGED
// It should be the same as the name of the file
final class TestPrivacySettings extends TestCase
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
    private $privacy;

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

        $this->privacy_settings = array();
    }

    // This function is run *after every unit test*
    function tearDown(): void
    {
        exec_query("DELETE FROM Users WHERE name=? AND email=?", [$this->name, $this->email]);
    }

    function testAllPrivacySettings(){
        // Test whether at least one privacy setting has been set. With 1 being off and 0 being on
        $settings = all_privacy_settings_set($this->id);

        $this->assertEquals(0,$settings, "The privacy should be set to 0");        
    }

    function testUpdatePrivacySettingsToTrue(){
        // Set the privacy settings to be hidden (i.e: 0)
        update_privacy($this->id, 0);

        // Get the users privacy settings choice
        $prefs = get_privacy_settings($this->id);

        // Assert that the settings are now at 0
        $this->assertEquals(0,$prefs, "Error: update_privacy_settings returned 0");
    }

}
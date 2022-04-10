<?php declare(strict_types=1);

# Import db_api.php
require_once('../db_api.php');
use PHPUnit\Framework\TestCase;

final class TestGetDateIds extends TestCase
{
    // Private variables. Can be accessed inside any unit test with
    // $this->id , etc.
    private $all_prefs;
    private $cafe_prefs;
    private $rest_prefs;
    private $hike_prefs;

    // This function is run *before every unit test*
    function setUp(): void
    {

        // Set the member variable prefs
        // Anything with a 1 is "accepted", anything with a 0 in not
        $this->all_prefs = array(
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

        $this->cafe_prefs = array(
            "Food" => array(
                "cafe" => 1
            ),
            "Date_time" => array(
                "morning" => 1
            )
        );

        $this->rest_prefs = array(
            "Food" => array(
                "restaurant" => 1
            ),
            "Venue" => array(
                "indoors" => 1
            ),
            "Date_time" => array(
                "evening"
            )
        );

        $this->hike_prefs = array(
            "Entertainment" => array(
                "hiking" => 1
            ),
            "Venue" => array(
                "outdoors" => 1
            ),
            "Date_time" => array(
                "afternoon" => 1
            )
        );
    }

    // This function is run *after every unit test*
    function tearDown(): void
    {
        // Shouldn't need teardown, since we're only getting
    }

    // Expect to get all possible date ID's
    function testAll(): void
    {

    }

}
<?php declare(strict_types=1);

# Import db_api.php
require_once('../db_api.php');
use PHPUnit\Framework\TestCase;

final class TestGenerateDates extends TestCase
{
    private $id_a;
    private $connection_a = "Alex";
    private $email_a = "alex@yahoo.co";
    private $address_a = "123 Side Street";
    private $zip_a = 12345;
    private $city_a = "Buffalo";
    private $password_a = "password";
    private $birthday_a = "1999-12-12";

    private $id_b;
    private $connection_b = "Hazel";
    private $email_b = "hazel@yahoo.co";
    private $address_b = "123 Main Street";
    private $zip_b = 67891;
    private $city_b = "Finger Lakes";
    private $password_b = "password";
    private $birthday_b = "2000-01-21";

    private $id_c;
    private $connection_c = "Heather";
    private $email_c = "heather@yahoo.co";
    private $address_c = "123 Auxiliary Street";
    private $zip_c = 23456;
    private $city_c = "Chandler, AZ";
    private $password_c = "password";
    private $birthday_c = "1950-01-02";

    private $id_d;
    private $connection_d = "Adam";
    private $email_d = "adam@yahoo.co";
    private $address_d = "123 Backup Street";
    private $zip_d = 78910;
    private $city_d = "Cleveland, OH";
    private $password_d = "password";
    private $birthday_d = "1975-04-16";

    function setUp(): void
    {
        // Alex
        create_user(
            $this->connection_a, $this->email_a, $this->password_a, $this->address_a, $this->city_a, $this->zip_a, $this->birthday_a
        );

        // Hazel
        create_user(
            $this->connection_b, $this->email_b, $this->password_b, $this->address_b, $this->city_b, $this->zip_b, $this->birthday_b
        );

        // Heather
        create_user(
            $this->connection_c, $this->email_c, $this->password_c, $this->address_c, $this->city_c, $this->zip_c, $this->birthday_c
        );

        // Adam
        create_user(
            $this->connection_d, $this->email_d, $this->password_d, $this->address_d, $this->city_d, $this->zip_d, $this->birthday_d
        );

        $this->id_a = get_user_id($this->email_a);
        $this->id_b = get_user_id($this->email_b);
        $this->id_c = get_user_id($this->email_c);
        $this->id_d = get_user_id($this->email_d);

        $this->assertGreaterThan(0, $this->id_a, "Error getting ID of user A in setUp() function");
        $this->assertGreaterThan(0, $this->id_b, "Error getting ID of user B in setUp() function");
        $this->assertGreaterThan(0, $this->id_c, "Error getting ID of user C in setUp() function");
        $this->assertGreaterThan(0, $this->id_d, "Error getting ID of user D in setUp() function");

        // Set preferences
        // Alex
        //      OK with anything, any cost, any distance, etc.
        //      People connecting with Alex should ALWAYS have a date
        $alex_prefs = array(
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

        // Hazel
        //      Prefers outdoor venues, does not like fast food
        //      Nothing in the morning, prefers lower cost activities
        $hazel_prefs = array(
            "Food" => array(
                "restaurant" => 1,
                "cafe" => 1,
                "fast_food" => 0,
                "alcohol" => 1
            ),
            "Entertainment" => array(
                "concerts" => 1,
                "hiking" => 1,
                "bar" => 1
            ),
            "Venue" => array(
                "indoors" => 0,
                "outdoors" => 1,
                "social_events" => 1
            ),
            "Date_time" => array(
                "morning" => 0,
                "afternoon" => 1,
                "evening" => 1
            ),
            "Date_preferences" => array(
                "cost" => 20,
                "distance" => 1000,
                "length" => 1000
            ),
        );

        // Heather
        //      More picky, open to less things
        //      No alcohol or fast food, no concerts
        //      Indoors, 1-on-1 only
        //      Afternoon only
        //      Low cost, close by
        $heather_prefs = array(
            "Food" => array(
                "restaurant" => 1,
                "cafe" => 1,
                "fast_food" => 0,
                "alcohol" => 0
            ),
            "Entertainment" => array(
                "concerts" => 0,
                "hiking" => 1,
                "bar" => 0
            ),
            "Venue" => array(
                "indoors" => 1,
                "outdoors" => 0,
                "social_events" => 0
            ),
            "Date_time" => array(
                "morning" => 0,
                "afternoon" => 1,
                "evening" => 0
            ),
            "Date_preferences" => array(
                "cost" => 15,
                "distance" => 10,
                "length" => 1000
            ),
        );

        // Adam
        //      Extremely picky
        //      Really only wants to go on coffee dates in the morning
        //      Can only spare an hour
        $adam_prefs = array(
            "Food" => array(
                "restaurant" => 0,
                "cafe" => 1,
                "fast_food" => 0,
                "alcohol" => 0
            ),
            "Entertainment" => array(
                "concerts" => 0,
                "hiking" => 0,
                "bar" => 0
            ),
            "Venue" => array(
                "indoors" => 1,
                "outdoors" => 0,
                "social_events" => 0
            ),
            "Date_time" => array(
                "morning" => 1,
                "afternoon" => 0,
                "evening" => 0
            ),
            "Date_preferences" => array(
                "cost" => 10,
                "distance" => 5,
                "length" => 1
            ),
        );
    }

    function tearDown(): void
    {
        exec_query("DELETE FROM Users WHERE name=? AND email=?", [$this->connection_a, $this->email_a]);
        exec_query("DELETE FROM Users WHERE name=? AND email=?", [$this->connection_b, $this->email_b]);
    }

    // Set preferences
    // Alex
    //      OK with anything, any cost, any distance, etc.
    //      People connecting with Alex should ALWAYS have a date
    
    // Hazel
    //      restaurant      cafe        alcohol     concerts        hiking
    //      bar             outdoors    social_events   afternoon   evening

    // Heather
    //      restaurant      cafe        hiking      indoors         afternoon

    // Adam
    //      cafe            indoors     morning

    // This test depends on the database being formatted a certain way
    function testBasic(): void
    {
        // a - alex
        // b - hazel
        // c - heather
        // d - adam

        $ab = generate_dates($this->id_a, $this->id_b);
        $ac = generate_dates($this->id_a, $this->id_c);
        $ad = generate_dates($this->id_a, $this->id_d);
        $bc = generate_dates($this->id_b, $this->id_c);
        $bd = generate_dates($this->id_b, $this->id_d);
        $cd = generate_dates($this->id_c, $this->id_d);

        $date_ideas = array($ab, $ac, $ad, $bc, $bd, $cd);
        foreach ($date_ideas as $dates) {
            $this->assertNotNull($dates, "Date generated null");
        }

        // NOTE: 'indoors' tag is ... useless

        // none of the matches with 'a' should be empty
        $a_dates = array("ab"=>$ab, "ac"=>$ac, "ad"=>$ad);
        foreach ($a_dates as $k => $v) {
            $this->assertGreaterThan(0, sizeof($v));
            if ($k == "ab") {
                // a and b match all dates
                $this->assertEquals(6, sizeof($v));
            }
            else if ($k == "ac") {
                // a and c also match all dates
                $this->assertEquals(6, sizeof($v));
            }
            else {
                // a and d match all but 1
                $this->assertEquals(5, sizeof($v));
            }
        }

    }
}
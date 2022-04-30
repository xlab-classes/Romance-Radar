<?php declare(strict_types=1);

// This file is meant to be used as a template for creating unit tests

# Import db_api.php
require_once('../db_api.php');
use PHPUnit\Framework\TestCase;

// THE CLASS NAME NEEDS TO BE CHANGED
// It should be the same as the name of the file
final class TestSortDates extends TestCase
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
    private $prefs_d;

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

        $this->assertNotEquals($this->id_a, $this->id_b);
        $this->assertNotEquals($this->id_a, $this->id_c);
        $this->assertNotEquals($this->id_a, $this->id_d);
        $this->assertNotEquals($this->id_b, $this->id_c);
        $this->assertNotEquals($this->id_b, $this->id_d);
        $this->assertNotEquals($this->id_c, $this->id_d);

        // Set preferences
        // Alex - OK with everything (has all preferences)
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
        $this->assertEquals(1, update_preferences($this->id_a, $alex_prefs));

        // Hazel
        $hazel_prefs = array(
            "Food" => array(
                "restaurant" => 1,
                "cafe" => 1,
                "alcohol" => 1
            ),
            "Entertainment" => array(
                "concerts" => 1,
                "hiking" => 1,
                "bar" => 1
            ),
            "Venue" => array(
                "outdoors" => 1,
                "social_events" => 1
            ),
            "Date_time" => array(
                "afternoon" => 1,
                "evening" => 1
            ),
            "Date_preferences" => array(
                "cost" => 20,
                "distance" => 1000,
                "length" => 1000
            ),
        );
        $this->assertEquals(1, update_preferences($this->id_b, $hazel_prefs));

        // Heather
        $heather_prefs = array(
            "Food" => array(
                "restaurant" => 1,
                "cafe" => 1
            ),
            "Entertainment" => array(
                "hiking" => 1
            ),
            "Venue" => array(
                "indoors" => 1
            ),
            "Date_time" => array(
                "afternoon" => 1
            ),
            "Date_preferences" => array(
                "cost" => 15,
                "distance" => 10,
                "length" => 1000
            ),
        );
        $this->assertEquals(1, update_preferences($this->id_c, $heather_prefs));

        // Adam
        $this->prefs_d = array(
            "Food" => array(
                "cafe" => 1
            ),
            "Venue" => array(
                "indoors" => 1
            ),
            "Date_time" => array(
                "morning" => 1
            ),
            "Date_preferences" => array(
                "cost" => 10,
                "distance" => 5,
                "length" => 1
            ),
        );
        $this->assertEquals(1, update_preferences($this->id_d, $this->prefs_d));
    }

    function tearDown(): void
    {
        exec_query("DELETE FROM Users WHERE name=? AND email=?", [$this->connection_a, $this->email_a]);
        exec_query("DELETE FROM Users WHERE name=? AND email=?", [$this->connection_b, $this->email_b]);
        exec_query("DELETE FROM Users WHERE name=? AND email=?", [$this->connection_c, $this->email_c]);
        exec_query("DELETE FROM Users WHERE name=? AND email=?", [$this->connection_d, $this->email_d]);
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

    function testSortedByCostAlexHazel(){
        
        // The dates between Alex and Hazel
        $ab = generate_dates($this->id_a, $this->id_b);

        // Sort all the dates by cost
        $sorted_date_ids = sort_dates_by_cost($ab,NULL);

        // A date cannot be less than 0
        $max = -1;

        // Check that all the dates are in non-decreasing order
        foreach($sorted_date_ids as $date){
            $cost = get_date_cost($date);
            $this->assertGreaterThanOrEqual($max, $cost, "Error: Dates are not in non-decreasing order");
            $max = $cost;
        }
    }

    function testSortedByCostAlexHeather(){
        
        // The dates between Alex and Heather
        $ac = generate_dates($this->id_a, $this->id_c);

        // Sort all the dates by cost
        $sorted_date_ids = sort_dates_by_cost($ac,NULL);

        // A date cannot be less than 0
        $max = -1;

        // Check that all the dates are in non-decreasing order
        foreach($sorted_date_ids as $date){
            $cost = get_date_cost($date);
            $this->assertGreaterThanOrEqual($max, $cost, "Cost is less than previous cost");
            $max = $cost;
        }
    }

    function testSortedByCostAlexAdam(){
        
        // The dates between Alex and Adam
        $ad = generate_dates($this->id_a, $this->id_d);

        // Sort all the dates by cost
        $sorted_date_ids = sort_dates_by_cost($ad,NULL);

        // A date cannot be less than 0
        $max = -1;

        // Check that all the dates are in non-decreasing order
        foreach($sorted_date_ids as $date){
            $cost = get_date_cost($date);
            $this->assertGreaterThanOrEqual($max, $cost, "Cost is less than previous cost");
            $max = $cost;
        }
    }

    function testSortedByLocationAlexHazel(){
        
        // The dates between Alex and Hazel
        $ab = generate_dates($this->id_a, $this->id_b);

        // Sort all the dates by location
        $sorted_date_ids = sort_dates_by_location($this->id_a,$ab);


        foreach($sorted_date_ids as $date){
            $user_city = get_user_city($this->id_a);
            
            $current_city = get_date_city($sorted_date_ids[0]);
            // If we are at the beginnig of the list, our user's city is the first city 
            if($current_city == $user_city){
                $this->assertEquals($user_city, $current_city);
            }
            else{
                $current_city = get_date_city($date);
                $this->assertNotEquals(0,strcmp($user_city, $current_city));
            }
        }
    }

    function testSortedByEntertainment() {

            // The dates between Alex and Heather
            $ac = generate_dates($this->id_a, $this->id_c);

            $entertainment = ['entertainment','concerts','hiking','bar'];
    
            // Sort all the dates by entertainment
            $sorted_date_ids = sort_dates_by_entertainment($ac);
            $count = countTagType($entertainment, $sorted_date_ids);
            for ($i=0; $i < $count  ; $i++) {
                $current_tag = get_date_tag($sorted_date_ids[$i]);
                $this->assertEquals(in_array($current_tag,$entertainment), true);
            }
            for ($i=$count; $i < count($sorted_date_ids)  ; $i++) { 
                $current_tag = get_date_tag($sorted_date_ids[$i]);
                $this->assertEquals(in_array($current_tag,$entertainment), false);            }
    }

    function testSortedByVenue(){
        
                // The dates between Alex and Heather
                $ac = generate_dates($this->id_a, $this->id_c);
    
                $venue = ['indoors','outdoors','social_events'];
        
                // Sort all the dates by venue
                $sorted_date_ids = sort_dates_by_venues($ac);
                $count = countTagType($venue, $sorted_date_ids);
                for ($i=0; $i < $count  ; $i++) {
                    $current_tag = get_date_tag($sorted_date_ids[$i]);
                    $this->assertEquals(in_array($current_tag,$venue), true);
                }
                for ($i=$count; $i < count($sorted_date_ids)  ; $i++) { 
                    $current_tag = get_date_tag($sorted_date_ids[$i]);
                    $this->assertEquals(in_array($current_tag,$venue), false);                }
    }

    function testSortedByFood(){
            
                // The dates between Alex and Heather
                $ac = generate_dates($this->id_a, $this->id_c);
        
                $food = ['restaurant','cafe','alcohol','fast_food'];
            
                // Sort all the dates by food
                $sorted_date_ids = sort_dates_by_food($ac);
                $count = countTagType($food, $sorted_date_ids);
                    for ($i=0; $i < $count  ; $i++) {
                        $current_tag = get_date_tag($sorted_date_ids[$i]);
                        $this->assertEquals(in_array($current_tag,$food), true);
                    }
                    for ($i=$count; $i < count($sorted_date_ids)  ; $i++) { 
                        $current_tag = get_date_tag($sorted_date_ids[$i]);
                        $this->assertEquals(in_array($current_tag,$food), false);                    
                    }
    }

    function testSortedByTime(){
            
                    // The dates between Alex and Heather
                    $ac = generate_dates($this->id_a, $this->id_c);
        
                    $time = ['morning','afternoon','evening'];
            
                    // Sort all the dates by time
                    $sorted_date_ids = sort_dates_by_time($ac);
                    $count = countTagType($time, $sorted_date_ids);
                    for ($i=0; $i < $count  ; $i++) { 
                        $current_tag = get_date_tag($sorted_date_ids[$i]);
                        $this->assertEquals(in_array($current_tag,$time), true);                    
                    }
                    for ($i=$count; $i < count($sorted_date_ids)  ; $i++) { 
                        $current_tag = get_date_tag($sorted_date_ids[$i]);
                        $this->assertEquals(in_array($current_tag,$time), false);
                    }
    }
}
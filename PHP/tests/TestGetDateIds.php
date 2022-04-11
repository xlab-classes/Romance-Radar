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
                "evening" => 1
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
        $ids = get_date_ids($this->all_prefs);
        $this->assertNotNull($ids, "There was an error getting IDs");

        $query = "SELECT * FROM Date_ideas";
        $data = NULL;
        $result = exec_query($query, $data);
        $this->assertNotNull($result, "Couldn't exec_query");
        $this->assertGreaterThan(0, $result->num_rows, "No date ideas found");
        $num_dates = $result->num_rows;

        $this->assertEquals(sizeof($ids), $num_dates, "Missing dates");
    }

    // Should return dates whose tags are morning or cafe
    // This test only works when the database is formatted a certain way
    // There should be code to ensure the proper format in setup
    function testCafe(): void
    {
        $ids = get_date_ids($this->cafe_prefs);
        $this->assertNotNull($ids, "There was an error getting date IDs");
        $this->assertEquals(sizeof($ids), 1);

        $query = "SELECT * FROM Date_ideas WHERE id=?";
        $data = [$ids[0]];
        $result = exec_query($query, $data);
        $this->assertNotNull($result, "Couldn't exec_query");
        $this->assertGreaterThan(0, $result->num_rows, "No dates with this ID found");
        $this->assertEquals($result->fetch_assoc()["name"], "Tim Hortons");
    }

    // Should return dates whose tags are restaurant, indoors, or evening
    // This test only works when the database is formatted a certain way
    // There should be code to ensure the proper format in setup
    function testRestaurants(): void
    {
        $ids = get_date_ids($this->rest_prefs);
        $this->assertNotNull($ids, "There was an error getting date IDs");
        $this->assertEquals(sizeof($ids), 4);

        $query = "SELECT * FROM Date_ideas WHERE id=? OR id=? OR id=? OR id=?";
        $data = [$ids[0], $ids[1], $ids[2], $ids[3]];
        $result = exec_query($query, $data);
        $this->assertNotNull($result, "Couldn't exec_query");
        $this->assertGreaterThan(0, $result->num_rows, "No dates with this ID found");
        
        $names = array("Mr.Goodbar", "Chef's", "Red Hot Chili Peppers", "Venu");
        $row = $result->fetch_assoc();

        // For every retrieved row, make sure the date name is one we expect
        while ($row != NULL) {
            $this->assertTrue(in_array($row["name"], $names), "Found unexpected date in testRestaurants: " . $row["name"]);
            $row = $result->fetch_assoc();
        }
    }

    // Should return dates whose tags are hiking, outdoors, afternoon
    // This test only works when the database is formatted a certain way
    // There should be code to ensure the proper format in setup
    function testHike(): void
    {
        $ids = get_date_ids($this->rest_prefs);
        $this->assertNotNull($ids, "There was an error getting date IDs");
        $this->assertEquals(sizeof($ids), 1);

        $query = "SELECT * FROM Date_ideas WHERE id=?";
        $data = [$ids[0]];
        $result = exec_query($query, $data);
        $this->assertNotNull($result, "Couldn't exec_query");
        $this->assertGreaterThan(0, $result->num_rows, "No dates with this ID found");
        
        $name = "Chestnut Ridge";
        $row = $result->fetch_assoc();
        $this->assertEquals($row["name"], $name, "Wrong date retrieved");
    }

}
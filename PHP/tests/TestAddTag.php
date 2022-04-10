<?php declare(strict_types=1);

# Import db_api.php
require_once('../db_api.php');
use PHPUnit\Framework\TestCase;

final class TestAddTag extends TestCase
{

    private $coffee;
    private $pasta;
    private $hiking;

    // This function is run *before every unit test*
    function setUp(): void
    {
        $this->coffee = get_date_id("Tim Hortons");
        $this->pasta = get_date_id("Chef's");
        $this->hiking = get_date_id("Chestnut Ridge");
    }

    // This function is run *after every unit test*
    function tearDown(): void
    {
        // Remove the tags that we added
        $query = "DELETE FROM Date_tags WHERE date_id=?";
        $data = [$this->coffee, $this->pasta, $this->hiking];

        foreach ($data as $d) {
            $result = exec_query($query, [$d]);
            $this->assertNotNull($result, "Couldn't cleanup tag");
        }
    }

    function testBasic(): void
    {
        

        $this->assertEquals(add_tag($this->coffee, "cafe"), 1, "Couldn't add tag for Tim Hortons");
        $this->assertEquals(add_tag($this->pasta, "restaurant"), 1, "Couldn't add tag for Chef's");
        $this->assertEquals(add_tag($this->hiking, "outdoors"), 1, "Couldn't add tag for Chestnut Ridge");

        // Check the tag for Tim Horton's
        $query = "SELECT * FROM Date_tags WHERE date_id=?";
        $data = [$this->coffee];
        $result = exec_query($query, $data);
        
        $this->assertNotNull($result, "Failed to exec query for Tim Hortons");
        $this->assertGreaterThan(0, $result->num_rows, "There are no tags for Tim Hortons");

        $tag = $result->fetch_assoc()["tag"];
        $this->assertEquals("cafe", $tag, "Wrong tag added to Tim Hortons");

        // Check the tag for Chef's
        $query = "SELECT * FROM Date_tags WHERE date_id=?";
        $data = [$this->pasta];
        $result = exec_query($query, $data);
        
        $this->assertNotNull($result, "Failed to exec query for Chef's");
        $this->assertGreaterThan(0, $result->num_rows, "There are no tags for Chef's");

        $tag = $result->fetch_assoc()["tag"];
        $this->assertEquals("restaurant", $tag, "Wrong tag added to Chef's");

        // Check the tag for Chestnut Ridge
        $query = "SELECT * FROM Date_tags WHERE date_id=?";
        $data = [$this->hiking];
        $result = exec_query($query, $data);
        
        $this->assertNotNull($result, "Failed to exec query for Chestnut Ridge");
        $this->assertGreaterThan(0, $result->num_rows, "There are no tags for Chestnut Ridge");

        $tag = $result->fetch_assoc()["tag"];
        $this->assertEquals("outdoors", $tag, "Wrong tag added to Chestnut Ridge");

    }

}
<?php declare(strict_types=1);

# Import db_api.php
require_once('../db_api.php');
use PHPUnit\Framework\TestCase;

final class TestGetDateId extends TestCase
{

    // This function is run *before every unit test*
    function setUp(): void
    {
        // No setup needed
    }

    // This function is run *after every unit test*
    function tearDown(): void
    {
        // No teardown needed
    }

    // NOTE: Must run AddDateIdeas.sql script prior to this test
    function testBasic(): void
    {
        $names = array(
            "Tim Hortons",
            "Mr.Goodbar",
            "Chef's",
            "Red Hot Chili Peppers",
            "Chestnut Ridge",
            "Venu"
        );

        $ids = array();
        
        foreach ($names as $name) {
            $id = get_date_id($name);
            $this->assertNotNull($id, "get_date_id returned NULL");
            $this->assertGreaterThan(0, $id, "get_date_id returned 0");
            $this->assertFalse(in_array($id, $ids), "get_date_id returned duplicate ID");
            array_push($ids, $id);
        }
    }

}
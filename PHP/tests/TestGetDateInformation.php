<?php declare(strict_types=1);

# Import db_api.php
require_once('../db_api.php');
use PHPUnit\Framework\TestCase;

final class TestGetDateInformation extends TestCase
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

    // This relies on the database being formatted a certain way
    // Code should be added to setUp to ensure this
    function testAll(): void
    {
        // Test with three date IDs that we know exist
        $coffee = get_date_information(1);
        $goodbar = get_date_information(2);
        $chefs = get_date_information(3);
        $bad = get_date_information(-1);

        $this->assertNull($bad, "Erroneously retrieved date information");

        $this->assertEquals($coffee["name"], "Tim Hortons");
        $this->assertEquals($coffee["picture"], "../assets/coffee.jpg");
        $this->assertEquals($coffee["location"], "Lackawanna, NY");

        $this->assertEquals($goodbar["name"], "Mr.Goodbar");
        $this->assertEquals($goodbar["picture"], "../assets/beer.jpg");
        $this->assertEquals($goodbar["location"], "Buffalo, NY");

        $this->assertEquals($chefs["name"], "Chef's");
        $this->assertEquals($chefs["picture"], "../assets/steak.jpg");
        $this->assertEquals($chefs["location"], "Buffalo, NY");
    }

}
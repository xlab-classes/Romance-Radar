<?php declare(strict_types=1);

# Import db_api.php
require_once('db_api.php');
use PHPUnit\Framework\TestCase;

final class TestCreateUser extends TestCase
{

    public function testBasic(): void
    {
        $create_result = create_user(
            "Jon Doe", "jon.doe@gmail.com", "password", "123 Apple Orchard Rd",
            14541, "12/01/1980");
        $this->assertSame(1, $create_result);

        $result = exec_query("SELECT * FROM Users WHERE email=?", ["jon.doe@gmail.com"]);
        $this->assertNotNull($result);
        $this->assertNotFalse($result);
        $this->assertSame(1, $result->num_rows);

        $arr = $result->fetch_assoc();
        $this->assertNotNull($arr);
        $this->assertNotFalse($arr);
        
        $this->assertSame($arr["name"], "Jon Doe");
        $this->assertSame($arr["email"], "jon.doe@gmail.com");
        $this->assertSame($arr["password"], "password");
        $this->assertSame($arr["street_address"], "123 Apple Orchard Rd");
        $this->assertSame($arr["zipcode"], 14541);
        $this->assertSame($arr["birthday"], "12/01/1980");
    }

}


?>

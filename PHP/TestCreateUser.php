<?php declare(strict_types=1);

# Import db_api.php
require_once('db_api.php');
use PHPUnit\Framework\TestCase;

final class TestCreateUser extends TestCase
{

    public function setUp(): void
    {
        $command = 'mysql'
        . ' -h oceanus'
        . ' -u alexeast'
        . ' --password=50252636'
        . ' --database=cse442_2022_spring_team_j_db'
        . ' --execute="SOURCE /web/CSE442-542/2022-Spring/cse-442j/Database/TableCreateScript.sql"';
        shell_exec($command);
    }

    public function tearDown(): void
    {
        $command = 'mysql'
        . ' -h oceanus'
        . ' -u alexeast'
        . ' --password=50252636'
        . ' --database=cse442_2022_spring_team_j_db'
        . ' --execute="SOURCE /web/CSE442-542/2022-Spring/cse-442j/Database/TableDestroyScript.sql"';
        shell_exec($command);
    }

    public function testUserDoesntExist(): void
    {
        $create_result = create_user(
            "Jon Doe", "jon.doe@gmail.com", "password", "123 Apple Orchard Rd",
            14541, "1980/01/12");
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
        $this->assertSame($arr["birthday"], "1980-01-12");
    }

    public function testUserExists(): void
    {
        $create_result = create_user(
            "Jon Doe", "jon.doe@gmail.com", "password", "123 Apple Orchard Rd", 14541, "1980/01/12");
        
        # The call to create_user should fail
        $this->assertSame(0, $create_result);
    }

}


?>

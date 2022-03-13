<?php declare(strict_types=1);

# Import db_api.php
require_once('db_api.php');
use PHPUnit\Framework\TestCase;


 final class ChangePaswwordTest extends TestCase {

    public function testUpdatePassword(): void
    {
        $create_result = create_user("Jon Doe","password","","","","");
        $this->assertSame(1, $create_result);

        # Check that the current users password is correct
        $result = exec_query("SELECT * FROM Users WHERE email=?", ["Jon Doe"]);
        $this->assertNotNull($result);
        $this-> assertSame($result["password"], "password");

        update_password("Jon Doe","password","newpassword");
        $result = exec_query("SELECT * FROM Users WHERE email=?", ["Jon Doe"]);
        $this->assertNotNull($result);
        $this-> assertSame($result["password"], "newpassword");
    }

    public function testUpdatePasswordSame(): void
    {
        $create_result = create_user("Jon Doe","password","","","","");
        $this->assertSame(1, $create_result);

        # Check that the current users password is correct
        $result = exec_query("SELECT * FROM Users WHERE email=?", ["Jon Doe"]);
        $this->assertNotNull($result);
        $this-> assertSame($result["password"], "password");

        $this-> assertSame(0,update_password("Jon Doe","password","password"));
        ;
    }
}
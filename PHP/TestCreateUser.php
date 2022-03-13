<?php declare(strict_types=1);

# Import db_api.php
require_once('db_api.php');
use PHPUnit\Framework\TestCase;

final class TestCreateUser extends TestCase
{

    public function basicTest(): void
    {
        $create_result = create_user(
            "Jon Doe", "jon.doe@gmail.com", "password", "123 Apple Orchard Rd",
            14541, "12/01/1980");
        $this->assertSame(0, $create_result);
    }

}


?>

#!/bin/bash

read -p "OK to clear database for testing? y/N\n" option

if ["$option" = "y"];
then
    ./reset_database.sh
fi

echo "Testing create_user\n"
./phpunit TestCreateUser.php

echo "Testing sign_in\n"
./phpunit TestSignIn.php

echo "Testing update_preferences\n"
./phpunit TestUpdatePreferences.php

echo "Testing updating personal details\n"
./phpunit TestUpdatePersonalDetails.php

echo "Testing update_password\n"
./phpunit TestChangePassword.php

echo "Testing connection functions:\n"
echo "\tadd_connection_request\n"
echo "\tadd_connection\n"
echo "\tremove_connection_request\n"
echo "\tremove_connection\n"
./phpunit TestConnections.php

echo "Testing get_date_id\n"
./phpunit TestGetDateId.php

echo "Testing get_date_ids\n"
./phpunit TestGetDateIds.php

echo "Testing get_date_information\n"
./phpunit TestGetDateInformation.php

echo "Testing add_tag\n"
./phpunit TestAddTag.php

echo "Testing generate_dates\n"
./phpunit TestGenerateDates.php
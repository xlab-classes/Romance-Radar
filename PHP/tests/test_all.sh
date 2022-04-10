#!/bin/bash

read -p "OK to clear database for testing? Y/N: " option

if [[ $option == "y" || $option == "Y" ]]; then
    echo "Clearing database..."
    ./reset_database.sh
fi

echo "Testing create_user"
./phpunit TestCreateUser.php

echo "Testing sign_in"
./phpunit TestSignIn.php

echo "Testing update_preferences"
./phpunit TestUpdatePreferences.php

echo "Testing updating personal details"
./phpunit TestUpdatePersonalDetails.php

echo "Testing update_password"
./phpunit TestChangePassword.php

echo "Testing connection functions:"
echo "\tadd_connection_request"
echo "\tadd_connection"
echo "\tremove_connection_request"
echo "\tremove_connection"
./phpunit TestConnections.php

echo "Testing get_date_id"
./phpunit TestGetDateId.php

echo "Testing get_date_ids"
./phpunit TestGetDateIds.php

echo "Testing get_date_information"
./phpunit TestGetDateInformation.php

echo "Testing add_tag"
./phpunit TestAddTag.php

echo "Testing generate_dates"
./phpunit TestGenerateDates.php
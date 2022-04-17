#!/bin/bash

read -p "OK to clear database for testing? Y/N: " option

if [[ $option == "y" || $option == "Y" ]]; then
    ./reset_database.sh
fi

echo -e "\n*** TESTING CREATE USER ***\n"
./phpunit TestCreateUser.php
echo "****************************************"


echo -e "\n*** TESTING SIGN IN ***\n"
./phpunit TestSignIn.php
echo "****************************************"

echo -e "\n*** TESTING UPDATE PREFERENCES ***\n"
./phpunit TestUpdatePreferences.php
echo "****************************************"

echo -e "\n*** TESTING UPDATE PERSON DETAILS ***\n"
./phpunit TestUpdatePersonalDetails.php
echo "****************************************"

echo -e "\n*** TESTING UPDATE PASSWORD ***\n"
./phpunit TestChangePassword.php
echo "****************************************"

echo -e "\n*** TESTING CONNECTION FUNCTIONS ***\n"
echo -e "\tadd_connection_request"
echo -e "\tadd_connection"
echo -e "\tremove_connection_request"
echo -e "\tremove_connection"
./phpunit TestConnections.php
echo "****************************************"

echo -e "\n*** TESTING GET DATE ID ***\n"
./phpunit TestGetDateId.php
echo "****************************************"

echo -e "\n*** TESTING GET DATE IDS ***\n"
./phpunit TestGetDateIds.php
echo "****************************************"

echo -e "\n*** TESTING GET DATE INFORMATION ***\m"
./phpunit TestGetDateInformation.php
echo "****************************************"

echo -e "\n*** TESTING GENERATE DATES ***\n"
./phpunit TestGenerateDates.php
echo "****************************************"

echo -e "\n*** TESTING DATE SUGGESTED ***\n"
./phpunit TestDateSuggested.php
echo "****************************************"

echo -e "\n*** TESTING GET TIMES SUGGESTED ***\n"
./phpunit TestGetTimesSuggested
echo "****************************************"

echo -e "\n*** TESTING SUGGESTION LIMIT ***\n"
./phpunit TestSuggestionLimit.php
echo "****************************************"

# This should be last, since it removes tags from the Date_tags table
echo -e "\n*** TESTING ADD TAG ***\n"
./phpunit TestAddTag.php
echo "****************************************"

# Cleanup
echo "Cleaning up..."
./reset_database.sh
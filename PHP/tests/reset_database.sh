#!/bin/bash

echo "Truncating tables...\n"
sleep 1
mysql -h "oceanus" -u "alexeast" --password="50252636" -D "cse442_2022_spring_team_j_db" < "../../Database/TableDestroyScript.sql" | grep -v "[Warning] Using a password"

echo "Creating tables...\n"
sleep 1
mysql -h "oceanus" -u "alexeast" --password="50252636" -D "cse442_2022_spring_team_j_db" < "../../Database/TableCreateScript.sql" | grep -v "[Warning] Using a password"

echo "Adding date ideas...\n"
sleep 1
mysql -h "oceanus" -u "alexeast" --password="50252636" -D "cse442_2022_spring_team_j_db" < "../../Database/AddDateIdeas.sql" | grep -v "[Warning] Using a password"

echo "Done!\n"
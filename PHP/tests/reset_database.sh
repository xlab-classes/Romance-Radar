#!/bin/bash

echo "Truncating tables..."
mysql -h "oceanus" -u "alexeast" --password="50252636" -D "cse442_2022_spring_team_j_db" < "../../Database/TableDestroyScript.sql"

echo "Creating tables..."
mysql -h "oceanus" -u "alexeast" --password="50252636" -D "cse442_2022_spring_team_j_db" < "../../Database/TableCreateScript.sql"

echo "Adding date ideas..."
mysql -h "oceanus" -u "alexeast" --password="50252636" -D "cse442_2022_spring_team_j_db" < "../../Database/AddDateIdeas.sql"

echo "Done!"
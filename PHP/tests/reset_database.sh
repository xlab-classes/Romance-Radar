echo "Truncating tables...\n"
sleep 1
mysql -h "oceanus" -u "alexeast" -p "50252636" "cse442_2022_spring_team_j_db" < "../../Database/TableDestroyScript.sql"

echo "Creating tables...\n"
sleep 1
mysql -h "oceanus" -u "alexeast" -p "50252636" "cse442_2022_spring_team_j_db" < "../../Database/TableCreateScript.sql"

echo "Adding date ideas...\n"
sleep 1
mysql -h "oceanus" -u "alexeast" -p "50252636" "cse442_2022_spring_team_j_db" < "../../Database/AddDateIdeas.sql"

echo "Done!\n"
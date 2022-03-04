import mysql

config = {
    'user': 'root',
    'password': 'diuFTC7#',
    'auth_plugin': 'mysql_native_password',
    'host': 'localhost',
    'database': 'romance_radar',
}

# Establishing the connection to the database
db = mysql.connector.connect(**config)

# Creating a cursor
# cursor = db.cursor()  

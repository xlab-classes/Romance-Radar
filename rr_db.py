import mysql.connector

config = {
    'user': 'root',
    'password': '#Evangelina090',
    'auth_plugin': 'mysql_native_password',
    'host': 'localhost'
}

# Establishing the connection to the database
db = mysql.connector.connect(**config)

cursor = db.cursor()  
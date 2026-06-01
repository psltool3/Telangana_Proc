import mysql.connector
def connect_to_database():
    host = 'localhost'
    user = 'root'
    password = ''
    database = 'telangana_procurement'
    connection = mysql.connector.connect(
        host=host, user=user, password=password, database=database
    )
    return connection

conn = connect_to_database()
cursor = conn.cursor()
cursor.execute("DESCRIBE mill")
for row in cursor.fetchall():
    print(row)
print("---")
cursor.execute("DESCRIBE warehouse")
for row in cursor.fetchall():
    print(row)
conn.close()

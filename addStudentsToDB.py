import mysql.connector
from datetime import datetime

# Connexion à la base de données MySQL
mydb = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="test"
)

# Création de l'objet de curseur pour exécuter des requêtes SQL
mycursor = mydb.cursor()

# Données à insérer dans MySQL
data = {
    "123456": {
        "name": "Gojo Saturu",
        "major": "AI",
        "starting_year": 2014,
        "total_attendance": 6,
        "standing": "G",
        "year": 4,
        "last_attendance_time": "2023-04-10 14:25:00"
    },
    "234567": {
        "name": "Itaduru",
        "major": "Rocket Science",
        "starting_year": 2017,
        "total_attendance": 8,
        "standing": "VG",
        "year": 3,
        "last_attendance_time": "2023-04-08 08:25:00"
    },
    "345678": {
        "name": "OS",
        "major": "Music",
        "starting_year": 2020,
        "total_attendance": 1,
        "standing": "G",
        "year": 1,
        "last_attendance_time": "2023-04-11 12:44:30"
    }
}

# Ajout des données à MySQL
for key, value in data.items():
    sql = "INSERT INTO Students (student_id, name, major, starting_year, total_attendance, standing, year, last_attendance_time) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)"
    val = (key, value["name"], value["major"], value["starting_year"], value["total_attendance"], value["standing"], value["year"], value["last_attendance_time"])
    mycursor.execute(sql, val)

# Valider les changements dans la base de données
mydb.commit()

# Fermer la connexion MySQL
mydb.close()

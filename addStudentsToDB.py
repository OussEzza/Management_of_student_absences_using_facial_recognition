import mysql.connector
from datetime import datetime

# Connexion à la base de données MySQL
mydb = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="managementofstudentabsences"
)

# Création de l'objet de curseur pour exécuter des requêtes SQL
mycursor = mydb.cursor()

# Données à insérer dans MySQL
data = {
    "123456": {
        "first_name": "Gojo",
        "last_name": "Saturu",
        "class_id": 1,
        "face_features": "some_face_features",
        # "last_attendance_time": "2023-04-10 14:25:00"
    },
    "234567": {
        "first_name": "Itaduru",
        "last_name": "Ken",
        "class_id": 2,
        "face_features": "some_face_features",
        # "last_attendance_time": "2023-04-08 08:25:00"
    },
    "345678": {
        "first_name": "OS",
        "last_name": "EZZAHRI",
        "class_id": 3,
        "face_features": "some_face_features",
        # "last_attendance_time": "2023-04-11 12:44:30"
    }
}

# Ajout des données à MySQL
for key, value in data.items():
    sql = "INSERT INTO Students (student_id, first_name, last_name, class_id, face_features) VALUES (%s, %s, %s, %s, %s)"
    val = (key, value["first_name"], value["last_name"], value["class_id"], value["face_features"])
    mycursor.execute(sql, val)

# Valider les changements dans la base de données
mydb.commit()

# Fermer la connexion MySQL
mydb.close()

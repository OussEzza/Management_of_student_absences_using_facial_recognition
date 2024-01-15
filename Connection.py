import cv2
import face_recognition
import pickle
import os
import mysql.connector

# Connexion à la base de données MySQL
mydb = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="test"
)

try:
    if mydb.is_connected():
        print('Connecting to MySQL')

    # Création de l'objet curseur pour exécuter des requêtes SQL
    mycursor = mydb.cursor()

    folderPath = 'Images'
    pathList = os.listdir(folderPath)
    imgList = []
    studentIds = []

    # Fonction pour trouver les encodages
    def findEncodings(imagesList):
        encodeList = []
        for img in imagesList:
            img = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
            face_encodings = face_recognition.face_encodings(img)
            
            # Check if any faces are detected
            if face_encodings:
                encode = face_encodings[0]
                encodeList.append(encode)
            else:
                print(f"No face found in the image: {img}")

        return encodeList

    # Insertion des encodages directement dans la base de données MySQL
    for path in pathList:
        img = cv2.imread(os.path.join(folderPath, path))
        student_id = os.path.splitext(path)[0]
        studentIds.append(student_id)

        encodeList = findEncodings([img])

        if encodeList:
            # Convertir la liste d'encodages en une chaîne JSON pour stockage dans MySQL
            encoded_data = pickle.dumps(encodeList[0])

            # Insertion des données dans la base de données MySQL
            sql = "INSERT INTO StudentsImages (student_id, face_encoding) VALUES (%s, %s)"
            val = (student_id, encoded_data)
            mycursor.execute(sql, val)
            mydb.commit()
            print(f"Face encoding for student {student_id} inserted into the database.")
        else:
            print(f"No face encoding found for student {student_id}.")

    print("Encoding and Insertion Complete")

except mysql.connector.Error as err:
    print("MySQL Error: {}".format(err))

finally:
    # Fermer la connexion MySQL
    if mydb.is_connected():
        mycursor.close()
        mydb.close()
        print("MySQL connection closed")

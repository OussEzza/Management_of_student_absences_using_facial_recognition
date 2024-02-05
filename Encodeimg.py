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
    database="managementofstudentabsences",
    # connect_timeout=120  # Set a higher timeout value (in seconds)
)

try:
    if mydb.is_connected():
        print('Connecting to MySQL')


    # Création de l'objet de curseur pour exécuter des requêtes SQL
    mycursor = mydb.cursor()

    # mycursor.execute('SET GLOBAL max_allowed_packet=67108864')

    folderPath = 'Images'
    # folderPath = './images'
    pathList = os.listdir(folderPath)
    imgList = []
    studentIds = []

    # Insertion des images dans la base de données MySQL
    for path in pathList:
        img = cv2.imread(os.path.join(folderPath, path))
        student_id = os.path.splitext(path)[0]

    #     # Insertion des données dans la base de données MySQL
    #     sql = "INSERT INTO studentsimages (student_id, image) VALUES (%s, %s)"
    #     val = (student_id, pickle.dumps(img))
    #     mycursor.execute(sql, val)

    #     # Valider les changements dans la base de données
    #     mydb.commit()

        imgList.append(img)
        studentIds.append(student_id)

    # print(studentIds)

    # Fonction pour trouver les encodages
    def findEncodings(imagesList):
        encodeList = []
        for img in imagesList:
            img = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
            face_encodings = face_recognition.face_encodings(img)
            print ('face_encodings ', face_encodings)
            
            # Check if any faces are detected
            if face_encodings:
                encode = face_encodings[0]
                encodeList.append(encode)
            else:
                print(f"No face found in the image: {img}")

        return encodeList


    print("Encoding Started ...")
    encodeListKnown = findEncodings(imgList)
    encodeListKnownWithIds = [encodeListKnown, studentIds]
    print("Encoding Complete")

    # Sauvegarde des encodages dans un fichier
    file = open("EncodeFile.p", 'wb')
    pickle.dump(encodeListKnownWithIds, file)
    file.close()
    print("File Saved")

except mysql.connector.Error as err:
    print("MySQL Error: {}".format(err))

finally:
    # Fermer la connexion MySQL
    if mydb.is_connected():
        mycursor.close()
        mydb.close()
        print("MySQL connection closed")

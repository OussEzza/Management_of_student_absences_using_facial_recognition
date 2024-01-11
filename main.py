import cv2
import os
import mysql.connector

# Connexion à la base de données MySQL
mydb = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="storeos"
)

if mydb:
    print('Connect')
# Création de l'objet de curseur pour exécuter des requêtes SQL
mycursor = mydb.cursor()


cap = cv2.VideoCapture(0)

cap.set(3, 640)
cap.set(4, 480)

imgBackground = cv2.imread('Resources/background.png')

folderPath = 'Resources/Modes'
modePathList = os.listdir(folderPath)
imgModeList = []

for path in modePathList:
    imgModeList.append(cv2.imread(os.path.join(folderPath, path)))
print(len(imgModeList))



while True:
    success, img = cap.read()
    imgBackground[162:162+480, 55:55+640] = img
    imgBackground[44:44+633, 808:808+414] = imgModeList[0]
    # cv2.imshow("Webcam", img)
    cv2.imshow("Student Attendance", imgBackground)
    cv2.waitKey(1)
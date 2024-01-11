import cv2
import os
import pickle
import face_recognition
import numpy as np 
import mysql.connector
import cvzone
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

cap = cv2.VideoCapture(0)
cap.set(3, 640)
cap.set(4, 480)

imgBackground = cv2.imread('Resources/background.png')

folderModePath = 'Resources/Modes'
modePathList = os.listdir(folderModePath)
imgModeList = []

for path in modePathList:
    imgModeList.append(cv2.imread(os.path.join(folderModePath, path)))

print("Loading Encode File ...")
file = open('EncodeFile.p', 'rb')
encodeListKnownWithIds = pickle.load(file)
file.close()
encodeListKnown, studentIds = encodeListKnownWithIds
print("Encode File Loaded")

modeType = 0
counter = 0
id = -1
imgStudent = []

while True:
    success, img = cap.read()

    # Check if the image is empty
    if img is None:
        print("Error: Empty frame received.")
        continue

    imgS = cv2.resize(img, (0, 0), None, 0.25, 0.25)
    imgS = cv2.cvtColor(imgS, cv2.COLOR_RGB2BGR)

    faceCurFrame = face_recognition.face_locations(imgS)
    encodeCurFrame = face_recognition.face_encodings(imgS, faceCurFrame)

    imgBackground[162:162+480, 55:55+640] = img
    imgBackground[44:44 + 633, 808:808+414] = imgModeList[modeType]

    if faceCurFrame:
        for encodeFace, faceLoc in zip(encodeCurFrame, faceCurFrame):
            matches = face_recognition.compare_faces(encodeListKnown, encodeFace)
            faceDis = face_recognition.face_distance(encodeListKnown, encodeFace)
            matchIndex = np.argmin(faceDis)

            if matches[matchIndex]:
                y1, x2, y2, x1 = faceLoc
                y1, x2, y2, x1 = y1 * 4, x2 * 4, y2 * 4, x1 * 4
                bbox = 55 + x1, 162 + y1, x2 - x1, y2 - y1
                imgBackground = cvzone.cornerRect(imgBackground, bbox, rt=0)
                id = studentIds[matchIndex]
                if counter == 0:
                    counter = 1 
                    modeType = 1

        if counter != 0:
            if counter == 1:
                # Get the Data 
                sql = f"SELECT * FROM Students WHERE student_id = '{id}'"
                mycursor.execute(sql)
                studentInfo = mycursor.fetchone()

                # Update attendance 
                datetimeObject = studentInfo[8]  # Assuming 'last_attendance_time' is the 8th column
                secondElapsed = (datetime.now() - datetimeObject).total_seconds()
                print(secondElapsed)

                if secondElapsed > 30:
                    sql = f"UPDATE Students SET total_attendance = total_attendance + 1, last_attendance_time = '{datetime.now().strftime('%Y-%m-%d %H:%M:%S')}' WHERE student_id = '{id}'"
                    mycursor.execute(sql)
                    mydb.commit()
                else:
                    modeType = 3
                    counter = 0
                    imgBackground[44:44 + 633, 808:808+414] = imgModeList[modeType]

            if modeType != 3: 
                if 10 < counter < 20: 
                    modeType = 2
                imgBackground[44:44 + 633, 808:808+414] = imgModeList[modeType]

                if counter <= 10:
                    cv2.putText(imgBackground, str(studentInfo[5]), (861, 125),
                                cv2.FONT_HERSHEY_COMPLEX, 1, (255, 255, 255), 1)
                    cv2.putText(imgBackground, str(studentInfo[2]), (1006, 550),
                                cv2.FONT_HERSHEY_COMPLEX, 0.5, (255, 255, 255), 1)
                    cv2.putText(imgBackground, str(id), (1006, 493),
                                cv2.FONT_HERSHEY_COMPLEX, 0.5, (255, 255, 255), 1)
                    cv2.putText(imgBackground, str(studentInfo[4]), (910, 625),
                                cv2.FONT_HERSHEY_COMPLEX, 0.6, (100, 100, 100), 1)
                    cv2.putText(imgBackground, str(studentInfo[6]), (1025, 625),
                                cv2.FONT_HERSHEY_COMPLEX, 0.6, (100, 100, 100), 1)
                    cv2.putText(imgBackground, str(studentInfo[3]), (1125, 625),
                                cv2.FONT_HERSHEY_COMPLEX, 0.6, (100, 100, 100), 1)
                    (w, h), _ = cv2.getTextSize(studentInfo[1], cv2.FONT_HERSHEY_COMPLEX, 1, 1)
                    offset = (414-w)//2
                    cv2.putText(imgBackground, str(studentInfo[1]), (808+offset, 445),
                                cv2.FONT_HERSHEY_COMPLEX, 1, (50, 50, 50, 1))
                    
                    # Load student image from database
                    sql_image = f"SELECT * FROM StudentsImages WHERE student_id = '{id}'"
                    mycursor.execute(sql_image)
                    studentImageInfo = mycursor.fetchone()

                    if studentImageInfo:
                        imgStudent = pickle.loads(studentImageInfo[2])
                        # Resize imgStudent to match the region size
                        imgStudent = cv2.resize(imgStudent, (216, 216))
                        imgBackground[175:175+216, 909:909+216] = imgStudent


            counter += 1

            if counter >= 20:
                counter = 0
                modeType = 0 
                studentInfo = []
                imgStudent = []
                imgBackground[44:44 + 633, 808:808+414] = imgModeList[modeType]
    else:
        modeType = 0 
        counter = 0

    cv2.imshow("Student Attendance", imgBackground)
    cv2.waitKey(1)

# Close the connection
# mydb.close()

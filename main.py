import cv2
import os
import pickle
import face_recognition
import numpy as np 
import mysql.connector
import cvzone
from datetime import datetime
import base64

# Connexion à la base de données MySQL
mydb = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="managementofstudentabsences"
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

print("encodeListKnownWithIds : ",encodeListKnownWithIds)
print("encodeListKnown : ",encodeListKnown)
print("studentIds : ",studentIds)
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
                print("Student ID:", id)
                sql = f"SELECT * FROM students WHERE student_id = '{id}'"
                if mycursor.execute(sql):
                    print("Resultat obtenu !")
                studentInfo = mycursor.fetchone()
                print(studentInfo)

                # Update attendance 
                # datetimeObject = studentInfo[6]  # Assuming 'last_attendance_time' is the 8th column
                datetimeObject = studentInfo[5]
                

                secondElapsed = (datetime.now() - datetimeObject).total_seconds()
                print(secondElapsed)

                if secondElapsed > 30:
                    sql_Update = f"UPDATE students SET total_attendance = total_attendance + 1, last_attendance_time = '{datetime.now().strftime('%Y-%m-%d %H:%M:%S')}' WHERE student_id = '{id}'"
                    mycursor.execute(sql_Update)

                    # Calculer le numéro de semaine
                    week_number = datetime.now().strftime('%U')
                    # Jour de la semaine (lundi = 0, dimanche = 6)
                    day_of_week = datetime.now().weekday() + 1

                    sql_insert_attendance = "INSERT INTO attendancerecords (student_id, date_time, week_number, day_of_week, present) VALUES (%s, %s, %s, %s, %s)"
                    values = (id, datetime.now(), week_number, day_of_week, 1)

                    try:
                        mycursor.execute(sql_insert_attendance, values)
                        mydb.commit()
                    except mysql.connector.Error as err:
                        print(f"Error: {err}")

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
                    cv2.putText(imgBackground, str(studentInfo[1]), (1006, 550),
                                cv2.FONT_HERSHEY_COMPLEX, 0.5, (255, 255, 255), 1)
                    cv2.putText(imgBackground, str(id), (1006, 493),
                                cv2.FONT_HERSHEY_COMPLEX, 0.5, (255, 255, 255), 1)
                    cv2.putText(imgBackground, str(studentInfo[3]), (910, 625),
                                cv2.FONT_HERSHEY_COMPLEX, 0.6, (100, 100, 100), 1)
                    cv2.putText(imgBackground, str(studentInfo[5]), (1025, 625),
                                cv2.FONT_HERSHEY_COMPLEX, 0.6, (100, 100, 100), 1)
                    cv2.putText(imgBackground, str(studentInfo[4]), (1125, 625),
                                cv2.FONT_HERSHEY_COMPLEX, 0.6, (100, 100, 100), 1)
                    (w, h), _ = cv2.getTextSize(studentInfo[1], cv2.FONT_HERSHEY_COMPLEX, 1, 1)
                    offset = (414-w)//2
                    cv2.putText(imgBackground, str(studentInfo[2]), (808+offset, 445),
                                cv2.FONT_HERSHEY_COMPLEX, 1, (50, 50, 50, 1))

                    
                    # Load student image from database
                    sql_image = f"SELECT * FROM studentsimages WHERE student_id = '{id}'"
                    mycursor.execute(sql_image)
                    studentImageInfo = mycursor.fetchone()

                    # if studentImageInfo:
                    #     # Assuming studentImageInfo[2] contains the base64-encoded image data
                    #     # decoded_image = base64.b64decode(studentImageInfo[2])
                    #     # imgStudent = cv2.imdecode(np.frombuffer(decoded_image, np.uint8), -1)
                    #     imgStudent = studentImageInfo[2]
                    #     # imgStudent = pickle.loads(studentImageInfo[2])
                    #     # Resize imgStudent to match the region size
                    #     imgStudent = cv2.resize(imgStudent, (216, 216))
                    #     imgBackground[175:175+216, 909:909+216] = imgStudent
                    if studentImageInfo:
                        imgStudent = studentImageInfo[2]
                        # Convert to numpy array
                        imgStudent_np = np.frombuffer(imgStudent, dtype=np.uint8)
                        imgStudent = cv2.imdecode(imgStudent_np, -1)
                        # Resize imgStudent to match the region size
                        imgStudent_resized = cv2.resize(imgStudent, (216, 216))
                        imgBackground[175:175+216, 909:909+216] = imgStudent_resized


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
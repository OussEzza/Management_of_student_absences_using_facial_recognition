import face_recognition
import cv2

# Load the reference image for facial recognition
known_image = face_recognition.load_image_file("img.jpg")
known_encoding = face_recognition.face_encodings(known_image)[0]

# Video capture using OpenCV
# cap = cv2.VideoCapture(0)
cap = cv2.VideoCapture(0, cv2.CAP_DSHOW)

while True:
    ret, frame = cap.read()

    # Display the current frame
    cv2.imshow('Camera', frame)


    # Check for the spacebar key press
    key = cv2.waitKey(1)
    if key == ord(' '):
        # Find faces in the current frame
        face_locations = face_recognition.face_locations(frame)
        face_encodings = face_recognition.face_encodings(frame, face_locations)

        # Process each detected face
        for face_encoding in face_encodings:
            # Compare the current face with the known face
            match = face_recognition.compare_faces([known_encoding], face_encoding)

            if match[0]:
                print("Face recognized!")

    # Check for the 'q' key press to exit the loop
    elif key == ord('q'):
        break

# Release the video capture and close the window
cap.release()
cv2.destroyAllWindows()

# Management of Student Absences using Facial Recognition

## Overview
This project is a student attendance management system that utilizes facial recognition for user authentication. It is designed with two distinct interfaces: one for regular users (students) to mark their attendance through facial recognition, and another for the administrator (president) to manage attendance records and handle student applications.

## Features
- **User Interface for Students:**
  - Mark attendance through facial recognition.

- **Administrator Interface:**
  - Select and view attendance records for a specific date.
  - Manage student and store new student information.
  - View attendance statistics, including the total number of students present and average attendance rate.
  - Filter attendance records based on various criteria such as name, etc.
  - Export attendance records to a file (e.g., CSV).
  - Manage user accounts, including adding and removing accounts.
  - Implement security features such as authentication and authorization.

## Technologies Used
- **Python for development:**
  - Utilized for the overall application logic (version 3.7).
- **OpenCV and dlib for facial recognition:**
  - Implemented facial recognition features for user authentication.
- **MySQL for database management:**
  - Used for storing and managing student information and attendance records.
- **Web Development Technologies for Admin Interface:**
  - **Frontend:**
    - HTML, CSS
    - [Tailwind CSS](https://tailwindcss.com/) for styling
    - [JavaScript](https://js.org/) for dynamic user interfaces
  - **Backend:**
    - PHP for server-side logic
- **Other Dependencies:**
  - Dependencies specific to Python, OpenCV, and other libraries are listed in `requirements.txt`.

## Setup
1. Clone the repository: `git clone https://github.com/OussEzza/Management_of_student_absences_using_facial_recognition.git`
2. Install the face_recognition_models dependencies: `pip install .\Install-dlib-main\dlib-19.19.0-cp37-cp37m-win_amd64.whl`
2. Install the face_recognition_models dependencies: `pip install .\face_recognition_models-master\face_recognition_models`
2. Install the required dependencies: `pip install -r requirements.txt`
3. Configure the database connection.
4. Set up the admin interface (instructions for frontend and PHP backend setup).

## Contribution
If you would like to contribute to the project, please follow these steps:
1. Fork the repository.
2. Create a new branch: `git checkout -b feature/new-feature`
3. Make your changes and commit them: `git commit -am 'Add new feature'`
4. Push to the branch: `git push origin feature/new-feature`
5. Submit a pull request.

## License
This project is licensed under the [MIT License](LICENSE).

## Contact
For inquiries or support, please contact Oussama EZZAHRI at [ezzahrioussama01@gmail.com].

---

[GitHub Repository](https://github.com/OussEzza/Management_of_student_absences_using_facial_recognition)

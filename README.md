# Use Cases and Data Model for a School Management System

## Actors:
- Student
- Teacher
- Admin

## Use Cases:

### Student
- Access course material:
    - Read course content
    - Ask questions
    - View answers
    - Take notes
- Access assignments:
    - Submit homework
    - View grades
- View notifications
- View schedule:
    - View course schedule
    - View exam schedule
- Edit profile
- Authenticate
- Contact admin

### Teacher
- Create and send course material:
    - Create and send lectures, tutorials, and practical sessions
- Assign and grade assignments:
    - Assign homework and projects
    - Grade student work
- Answer student questions
- View notifications
- View schedule:
    - View course schedule
    - View exam schedule
- Edit profile
- Authenticate
- Send announcements
- View course feedback
- Contact admin

### Admin
- Manage schedules:
    - Manage course schedules
    - Manage exam schedules
- Send announcements
- View statistics
- Authenticate
- Add student/teacher to database (table of pre-registered users)
- Manage accounts
- Respond to issues

## Data Model:

### Pre-Registered Student
- id
- registration number
- email

### Pre-Registered Teacher
- id
- registration number
- email

### Student
- id
- first name
- last name
- email
- password
- photo
- phone number
- registration number
- birth date
- about me
- level id

### Teacher
- id
- first name
- last name
- email
- password
- photo
- phone number
- registration number
- birth date
- about me

### Level
- id
- name

### Subject
- id
- name
- level id

### Course
- id
- teacher id
- subject id
- title
- content
- files

### Summary
- id
- course id
- summary

### Course Question
- id
- course id
- student id
- message (string)
- response (string)
- answered (boolean)

### Course Grade
- id
- student id
- course id
- grade (out of 5)

### Notification
- id
- sender id
- receiver id
- type (homework, message)
- title
- content

### Homework
- id
- subject id
- teacher id
- title
- content
- file
- deadline

### Homework Response
- id
- homework id
- student id
- title
- files
- date
- grade (out of 20)

### Exam
- id
- subject id
- teacher id
- supervisor id
- date

### Message
- id
- sender id
- receiver id
- title
- content

😎😎😎😎😎

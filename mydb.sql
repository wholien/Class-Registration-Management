/* To start with a fresh new database named coursereg, we will delete one
 * if one already exists:
 */
DROP DATABASE IF EXISTS coursereg;

-- Now, create a new databse named bookstore (another form of a comment)
CREATE DATABASE coursereg;

/* Create a user named julien@localhost with the password 'chien'.
 * julien is to have full access on all the objects (tables) on the store
 * database.  * is a wildcared to mean all the tables.
 * Note: You must use a semicolon to end every command; you also need quotes
 *       around a password.
 * After you create a user named julien with the password chien, you will be
 * able to start a client like this:
 *    /Applications/XAMPP/bin/mysql -u julien -p
 * which will prompt for a password to which you can type in chien.
 */
GRANT ALL PRIVILEGES ON coursereg.* to julien@localhost IDENTIFIED BY 'chien';

USE coursereg;

CREATE TABLE IF NOT EXISTS students (
 id int NOT NULL AUTO_INCREMENT,
 name text NOT NULL,
 username text NOT NULL,
 password text NOT NULL, 
 credit_limit int NOT NULL,
 credit_registered int NOT NULL,
 PRIMARY KEY (id)
) ENGINE=InnoDB; 

CREATE TABLE IF NOT EXISTS courses (
 id int NOT NULL AUTO_INCREMENT,
 title text NOT NULL,
 category text NOT NULL,
 course_number text NOT NULL,
 course_time int NOT NULL,
 location text NOT NULL,
 slots int(11) NOT NULL,
 slotstaken int(11) NOT NULL,
 /*classtimes int NOT NULL, */
 description text NOT NULL,
 PRIMARY KEY (id)
) ENGINE=InnoDB; 

CREATE TABLE IF NOT EXISTS studentcourses (
 student_id int NOT NULL,
 course_id int NOT NULL,
 PRIMARY KEY (student_id, course_id)
) ENGINE=InnoDB; 

CREATE TABLE IF NOT EXISTS course_times (
 id int NOT NULL AUTO_INCREMENT,
 time text NOT NULL,
 PRIMARY KEY (id)
) ENGINE=InnoDB; 


/* prepopulation for users
Created two users,
1:
username: Julien
password: Chien
role: admin

2:
username: Andrew
password: Sheets
role: user

INSERT INTO users (id, username, password, role) VALUES (NULL, 'Julien', 'Chien', 'admin');
INSERT INTO users (id, username, password, role) VALUES (NULL, 'Andrew', 'Sheets', 'user');
*/
INSERT INTO students (id, name, username, password, credit_limit, credit_registered)
 VALUES (NULL, 'Julien Chien', 'jchien17', 'admin', 4, 4);

INSERT INTO studentcourses (student_id, course_id) VALUES (1, 1);
INSERT INTO studentcourses (student_id, course_id) VALUES (1, 2);
INSERT INTO studentcourses (student_id, course_id) VALUES (1, 3);
INSERT INTO studentcourses (student_id, course_id) VALUES (1, 4);

INSERT INTO courses (id, title, category, course_number, course_time, location, slots, slotstaken, description) 
VALUES (NULL, 'Fundamentals of CS', 'Computer Science', 'CSCI052-CM', 1 , 'Kravis 102', 5, 1, 'this is intro to cs');

INSERT INTO courses (id, title, category, course_number, course_time, location, slots, slotstaken, description) 
VALUES (NULL, 'Data Structures', 'Computer Science', 'CSCI062-CM', 2 , 'Kravis 115', 5, 1, 'This is 2nd class in cs');

INSERT INTO courses (id, title, category, course_number, course_time, location, slots, slotstaken, description) 
VALUES (NULL, 'Distributed Systems', 'Computer Science', 'CSCI135-CM', 3 , 'Roberts North 12', 5, 1, 'Art Lee class');

INSERT INTO courses (id, title, category, course_number, course_time, location, slots, slotstaken, description) 
VALUES (NULL, 'Dicrete Mathematics', 'Computer Science', 'CSCI055-CM', 4 , 'Roberts South 103', 5, 1, 'You need discrete math');


INSERT INTO course_times (id, time) VALUES (NULL, 'MW 8:10 - 9:25AM');
INSERT INTO course_times (id, time) VALUES (NULL, 'MW 9:35 - 10:50AM');
INSERT INTO course_times (id, time) VALUES (NULL, 'MW 11:00 - 12:15PM');
INSERT INTO course_times (id, time) VALUES (NULL, 'MW 1:15 - 2:30PM');



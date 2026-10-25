-- StudentHub database  |  ITUE203 Web Development Frameworks  |  25DCE070
-- Practicals 8-15  (generated dump: schema + seed data)
-- SQLite equivalent used ONLY for the local demo/screenshots (no MySQL server in the sandbox).
PRAGMA foreign_keys = ON;
DROP TABLE IF EXISTS audit_logs; DROP TABLE IF EXISTS registrations; DROP TABLE IF EXISTS events; DROP TABLE IF EXISTS students; DROP TABLE IF EXISTS users;
CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT, full_name TEXT NOT NULL, email TEXT NOT NULL UNIQUE, password_hash TEXT NOT NULL, role TEXT NOT NULL DEFAULT 'student' CHECK(role IN ('student','admin')), status TEXT NOT NULL DEFAULT 'active' CHECK(status IN ('active','inactive')), remember_token_hash TEXT, remember_expires TEXT, last_login TEXT, created_at TEXT DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE students (id INTEGER PRIMARY KEY AUTOINCREMENT, enrollment_no TEXT NOT NULL UNIQUE, full_name TEXT NOT NULL, email TEXT NOT NULL UNIQUE, mobile TEXT NOT NULL, course TEXT NOT NULL, year INTEGER NOT NULL DEFAULT 1, gender TEXT NOT NULL, cgpa REAL NOT NULL DEFAULT 0, status TEXT NOT NULL DEFAULT 'active', created_at TEXT DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE events (id INTEGER PRIMARY KEY AUTOINCREMENT, title TEXT NOT NULL, category TEXT NOT NULL, event_date TEXT NOT NULL, venue TEXT NOT NULL, seats INTEGER NOT NULL DEFAULT 0, description TEXT, poster TEXT, status TEXT NOT NULL DEFAULT 'open', created_by INTEGER REFERENCES users(id) ON DELETE SET NULL, created_at TEXT DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE registrations (id INTEGER PRIMARY KEY AUTOINCREMENT, student_id INTEGER NOT NULL REFERENCES students(id) ON DELETE CASCADE, event_id INTEGER NOT NULL REFERENCES events(id) ON DELETE CASCADE, registered_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP, UNIQUE(student_id,event_id));
CREATE TABLE audit_logs (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER REFERENCES users(id) ON DELETE SET NULL, action TEXT NOT NULL, details TEXT, ip_address TEXT, created_at TEXT DEFAULT CURRENT_TIMESTAMP);

-- Seed data: users (passwords hashed with password_hash(); demo password: Admin@2026 / Student@2026)
INSERT INTO users (full_name,email,password_hash,role,status) VALUES ('Admin User','admin@charusat.edu.in','$2y$10$ppzzT8hAqqes0i2fMwAyH.THo.pKgaxlTVviwcAGSzXzoemHhjBna','admin','active');
INSERT INTO users (full_name,email,password_hash,role,status) VALUES ('Yuvraj Parmar','25dce070@charusat.edu.in','$2y$10$iss8Ub677fUN0yObo2nGr.q0AjgmczSqKFsPwbQoQajLGIyrljTMq','student','active');
INSERT INTO users (full_name,email,password_hash,role,status) VALUES ('Aarav Sharma','25dce071@charusat.edu.in','$2y$10$k..04c7GuYvj020zgqboBeSd0ODuVivvv4/wCQSEQXZjJHH6VQAty','student','active');
INSERT INTO users (full_name,email,password_hash,role,status) VALUES ('Diya Patel','25dce072@charusat.edu.in','$2y$10$C0PfV.yHyrSoihA496Gzse8oGCsjdZEIKE/FIaPYpAOb6/WpyctlW','student','inactive');

-- Seed data: students (22 rows, 1 soft-deleted)
INSERT INTO students (enrollment_no,full_name,email,mobile,course,year,gender,cgpa,status) VALUES ('25DCE070','Yuvraj Parmar','25dce070@charusat.edu.in','9853464097','B.Tech Computer Engineering',2,'Male',9.42,'active');
INSERT INTO students (enrollment_no,full_name,email,mobile,course,year,gender,cgpa,status) VALUES ('25DCE012','Aarav Sharma','aarav.sharma@charusat.edu.in','9830246633','B.Tech Computer Engineering',2,'Male',8.85,'active');
INSERT INTO students (enrollment_no,full_name,email,mobile,course,year,gender,cgpa,status) VALUES ('25DIT045','Diya Patel','diya.patel@charusat.edu.in','9862992312','B.Tech Information Technology',2,'Female',9.18,'active');
INSERT INTO students (enrollment_no,full_name,email,mobile,course,year,gender,cgpa,status) VALUES ('25DCE089','Rohan Desai','rohan.desai@charusat.edu.in','9897366946','B.Tech Computer Engineering',2,'Male',8.6,'active');
INSERT INTO students (enrollment_no,full_name,email,mobile,course,year,gender,cgpa,status) VALUES ('25DAI018','Ananya Joshi','ananya.joshi@charusat.edu.in','9816480894','B.Tech Computer Engineering',2,'Female',9.5,'active');
INSERT INTO students (enrollment_no,full_name,email,mobile,course,year,gender,cgpa,status) VALUES ('25DEC034','Krishnakumar Dave','krishna.dave@charusat.edu.in','9819722233','B.Tech Computer Engineering',2,'Male',8.4,'active');
INSERT INTO students (enrollment_no,full_name,email,mobile,course,year,gender,cgpa,status) VALUES ('25DIT092','Pooja Vaghela','pooja.vaghela@charusat.edu.in','9881924865','B.Tech Information Technology',2,'Female',8.95,'active');
INSERT INTO students (enrollment_no,full_name,email,mobile,course,year,gender,cgpa,status) VALUES ('25DME055','Aditya Singh','aditya.singh@charusat.edu.in','9822633920','B.Tech Computer Engineering',2,'Male',8.75,'active');
INSERT INTO students (enrollment_no,full_name,email,mobile,course,year,gender,cgpa,status) VALUES ('25DCE103','Meera Trivedi','meera.trivedi@charusat.edu.in','9859081935','B.Tech Computer Engineering',2,'Female',9.1,'active');
INSERT INTO students (enrollment_no,full_name,email,mobile,course,year,gender,cgpa,status) VALUES ('25DAI042','Devansh Shah','devansh.shah@charusat.edu.in','9888220482','B.Tech Computer Engineering',2,'Male',9.3,'active');
INSERT INTO students (enrollment_no,full_name,email,mobile,course,year,gender,cgpa,status) VALUES ('25DCL021','Sneha Prajapati','sneha.prajapati@charusat.edu.in','9817784483','B.Tech Computer Engineering',2,'Female',8.65,'active');
INSERT INTO students (enrollment_no,full_name,email,mobile,course,year,gender,cgpa,status) VALUES ('25DIT064','Varun Bhatt','varun.bhatt@charusat.edu.in','9878106871','B.Tech Information Technology',2,'Male',9.05,'active');
INSERT INTO students (enrollment_no,full_name,email,mobile,course,year,gender,cgpa,status) VALUES ('25DCE038','Isha Mehta','isha.mehta@charusat.edu.in','9838816302','B.Tech Computer Engineering',2,'Female',9.22,'active');
INSERT INTO students (enrollment_no,full_name,email,mobile,course,year,gender,cgpa,status) VALUES ('25DEC077','Harsh Choudhary','harsh.choudhary@charusat.edu.in','9815032582','B.Tech Computer Engineering',2,'Male',8.5,'active');
INSERT INTO students (enrollment_no,full_name,email,mobile,course,year,gender,cgpa,status) VALUES ('25DAI009','Kavya Rawal','kavya.rawal@charusat.edu.in','9821535642','B.Tech Computer Engineering',2,'Female',9.6,'active');
INSERT INTO students (enrollment_no,full_name,email,mobile,course,year,gender,cgpa,status) VALUES ('25DCE118','Manan Soni','manan.soni@charusat.edu.in','9868202938','B.Tech Computer Engineering',2,'Male',8.8,'active');
INSERT INTO students (enrollment_no,full_name,email,mobile,course,year,gender,cgpa,status) VALUES ('25DCE081','Nidhi Parikh','25dce081@charusat.edu.in','9766126116','B.Tech Computer Engineering',2,'Female',7.37,'active');
INSERT INTO students (enrollment_no,full_name,email,mobile,course,year,gender,cgpa,status) VALUES ('25DCE082','Jay Panchal','25dce082@charusat.edu.in','9722175294','B.Tech Computer Engineering',2,'Male',8.52,'active');
INSERT INTO students (enrollment_no,full_name,email,mobile,course,year,gender,cgpa,status) VALUES ('25DCE083','Riya Gandhi','25dce083@charusat.edu.in','9717933677','B.Tech Computer Engineering',2,'Female',9.18,'active');
INSERT INTO students (enrollment_no,full_name,email,mobile,course,year,gender,cgpa,status) VALUES ('25DCE084','Smit Thakkar','25dce084@charusat.edu.in','9726616417','B.Tech Computer Engineering',2,'Male',9.47,'active');
INSERT INTO students (enrollment_no,full_name,email,mobile,course,year,gender,cgpa,status) VALUES ('25DCE085','Tanvi Kapadia','25dce085@charusat.edu.in','9794641177','B.Tech Computer Engineering',2,'Female',8.71,'active');
INSERT INTO students (enrollment_no,full_name,email,mobile,course,year,gender,cgpa,status) VALUES ('25DCE086','Om Chauhan','25dce086@charusat.edu.in','9718302983','B.Tech Computer Engineering',2,'Male',8.59,'deleted');

-- Seed data: events (16 rows)
INSERT INTO events (title,category,event_date,venue,seats,description,poster,status,created_by) VALUES ('TechGenesis 2026 - Annual National Hackathon','Hackathon','2026-10-15','Main Auditorium & Computing Center',200,'A 24-hour intense coding hackathon focusing on AI, Web3, and Sustainable Tech solutions. Win cash prizes worth ₹1,50,000.',NULL,'closed',1);
INSERT INTO events (title,category,event_date,venue,seats,description,poster,status,created_by) VALUES ('Full-Stack Web Development Masterclass','Workshop','2026-10-18','Lab 302, Department of Computer Engineering',60,'Deep dive into modern web frameworks, RESTful APIs, asynchronous JavaScript, and secure PHP/MySQL backend architecture.',NULL,'closed',1);
INSERT INTO events (title,category,event_date,venue,seats,description,poster,status,created_by) VALUES ('National Cyber Security & Ethical Hacking Seminar','Seminar','2026-10-22','Seminar Hall A',150,'Explore the latest trends in network vulnerabilities, penetration testing, Web Application Firewalls, and secure coding practices.',NULL,'closed',1);
INSERT INTO events (title,category,event_date,venue,seats,description,poster,status,created_by) VALUES ('Spandan 2026 - Cultural Extravaganza','Cultural','2026-10-28','Open Air Amphitheatre',600,'Experience music performances, drama, traditional dance competitions, fashion shows, and delicious food stalls across campus.',NULL,'open',1);
INSERT INTO events (title,category,event_date,venue,seats,description,poster,status,created_by) VALUES ('RoboWars & Autonomous Drone Racing','Competition','2026-11-02','Mechanical Workshop Arena',100,'Showcase your combat robots and autonomous drones in speed and obstacle navigation challenges. Exciting trophies and certificates.',NULL,'open',1);
INSERT INTO events (title,category,event_date,venue,seats,description,poster,status,created_by) VALUES ('Industry 4.0 & AI in Cloud Computing','Seminar','2026-11-06','Conference Hall 1',120,'Learn how cloud hyperscalers integrate machine learning models, serverless functions, and microservices for enterprise workloads.',NULL,'open',1);
INSERT INTO events (title,category,event_date,venue,seats,description,poster,status,created_by) VALUES ('Inter-College Cricket & Football Tournament','Sports','2026-11-10','CHARUSAT Sports Complex',300,'Annual inter-department championship for cricket, football, basketball, and volleyball. Cheer for your branch team!',NULL,'open',1);
INSERT INTO events (title,category,event_date,venue,seats,description,poster,status,created_by) VALUES ('UI/UX Design Sprint with Figma','Workshop','2026-11-14','Design Studio Lab',50,'Hands-on session creating design systems, auto-layout wireframes, micro-interactions, and high-fidelity clickable prototypes.',NULL,'open',1);
INSERT INTO events (title,category,event_date,venue,seats,description,poster,status,created_by) VALUES ('CodeSprint: Speed Algorithm Challenge','Competition','2026-11-18','Online & Lab 201',200,'Test your problem solving skills across data structures, graph theory, dynamic programming, and algorithm optimization.',NULL,'open',1);
INSERT INTO events (title,category,event_date,venue,seats,description,poster,status,created_by) VALUES ('Campus Placement Drive & Resume Clinic','Career','2026-11-22','Training & Placement Cell',250,'Mock technical interviews, ATS-friendly resume review, HR interview tips, and direct interactions with hiring managers.',NULL,'open',1);
INSERT INTO events (title,category,event_date,venue,seats,description,poster,status,created_by) VALUES ('Data Science & Machine Learning with Python','Workshop','2026-11-26','Data Lab 4',50,'Understand NumPy, Pandas, Scikit-Learn, data visualization techniques, regression models, and neural network basics.',NULL,'open',1);
INSERT INTO events (title,category,event_date,venue,seats,description,poster,status,created_by) VALUES ('Annual Alumni Meet & Networking Gala','Networking','2026-12-01','University Banquet Hall',300,'Connect with graduates working in global tech giants and startups. Guidance on higher studies, research, and career growth.',NULL,'open',1);
INSERT INTO events (title,category,event_date,venue,seats,description,poster,status,created_by) VALUES ('Entrepreneurship & Startup Pitch Day','Business','2026-12-05','Incubation & Innovation Cell',100,'Pitch your business ideas to angel investors and industry leaders for seed funding, prototyping grants, and mentorship.',NULL,'open',1);
INSERT INTO events (title,category,event_date,venue,seats,description,poster,status,created_by) VALUES ('Open Source Contributions & Git/GitHub Workshop','Workshop','2026-12-09','Software Lab 2',70,'Master Git branch management, pull requests, resolving merge conflicts, and contributing to open-source software.',NULL,'open',1);
INSERT INTO events (title,category,event_date,venue,seats,description,poster,status,created_by) VALUES ('Debate Championship: Ethics in Artificial Intelligence','Competition','2026-12-14','Senate Hall',150,'Engage in debates on AI alignment, intellectual property, deepfakes, autonomous warfare, and societal impact.',NULL,'open',1);
INSERT INTO events (title,category,event_date,venue,seats,description,poster,status,created_by) VALUES ('IoT & Smart Hardware Hackathon','Hackathon','2026-12-19','Embedded Systems Lab',80,'Build sensor-driven smart systems using ESP32, Arduino, Raspberry Pi, MQTT brokers, and cloud telemetry dashboards.',NULL,'open',1);

-- Seed data: registrations
INSERT INTO registrations (student_id,event_id,registered_at) VALUES (3,2,'2026-06-15 18:07:00');
INSERT INTO registrations (student_id,event_id,registered_at) VALUES (3,3,'2026-09-06 09:13:00');
INSERT INTO registrations (student_id,event_id,registered_at) VALUES (3,11,'2026-07-15 11:47:00');
INSERT INTO registrations (student_id,event_id,registered_at) VALUES (4,7,'2026-07-05 15:25:00');
INSERT INTO registrations (student_id,event_id,registered_at) VALUES (4,14,'2026-09-07 10:10:00');
INSERT INTO registrations (student_id,event_id,registered_at) VALUES (5,4,'2026-08-25 15:35:00');
INSERT INTO registrations (student_id,event_id,registered_at) VALUES (5,16,'2026-07-13 11:52:00');
INSERT INTO registrations (student_id,event_id,registered_at) VALUES (6,11,'2026-08-21 17:17:00');
INSERT INTO registrations (student_id,event_id,registered_at) VALUES (8,2,'2026-08-17 14:43:00');
INSERT INTO registrations (student_id,event_id,registered_at) VALUES (8,3,'2026-08-08 12:09:00');
INSERT INTO registrations (student_id,event_id,registered_at) VALUES (8,6,'2026-05-24 11:09:00');
INSERT INTO registrations (student_id,event_id,registered_at) VALUES (9,16,'2026-07-01 12:00:00');
INSERT INTO registrations (student_id,event_id,registered_at) VALUES (10,3,'2026-09-04 18:11:00');
INSERT INTO registrations (student_id,event_id,registered_at) VALUES (10,13,'2026-07-09 13:00:00');
INSERT INTO registrations (student_id,event_id,registered_at) VALUES (10,14,'2026-06-09 15:34:00');
INSERT INTO registrations (student_id,event_id,registered_at) VALUES (10,15,'2026-08-05 18:36:00');
INSERT INTO registrations (student_id,event_id,registered_at) VALUES (11,12,'2026-07-23 11:44:00');
INSERT INTO registrations (student_id,event_id,registered_at) VALUES (11,15,'2026-09-11 18:41:00');
INSERT INTO registrations (student_id,event_id,registered_at) VALUES (12,1,'2026-05-16 16:57:00');
INSERT INTO registrations (student_id,event_id,registered_at) VALUES (12,4,'2026-09-23 15:25:00');
INSERT INTO registrations (student_id,event_id,registered_at) VALUES (12,10,'2026-08-13 15:06:00');
INSERT INTO registrations (student_id,event_id,registered_at) VALUES (13,2,'2026-09-03 15:03:00');
INSERT INTO registrations (student_id,event_id,registered_at) VALUES (14,2,'2026-06-20 10:13:00');
INSERT INTO registrations (student_id,event_id,registered_at) VALUES (15,12,'2026-08-23 11:07:00');
INSERT INTO registrations (student_id,event_id,registered_at) VALUES (16,14,'2026-07-29 18:03:00');
INSERT INTO registrations (student_id,event_id,registered_at) VALUES (17,16,'2026-05-29 09:36:00');
INSERT INTO registrations (student_id,event_id,registered_at) VALUES (18,3,'2026-06-10 17:06:00');
INSERT INTO registrations (student_id,event_id,registered_at) VALUES (18,5,'2026-08-04 18:01:00');
INSERT INTO registrations (student_id,event_id,registered_at) VALUES (18,6,'2026-05-21 12:39:00');
INSERT INTO registrations (student_id,event_id,registered_at) VALUES (19,2,'2026-08-07 11:40:00');
INSERT INTO registrations (student_id,event_id,registered_at) VALUES (19,10,'2026-07-06 14:38:00');
INSERT INTO registrations (student_id,event_id,registered_at) VALUES (19,15,'2026-08-04 16:07:00');
INSERT INTO registrations (student_id,event_id,registered_at) VALUES (20,7,'2026-06-01 16:29:00');
INSERT INTO registrations (student_id,event_id,registered_at) VALUES (20,16,'2026-09-02 16:19:00');

-- Seed data: audit_logs
INSERT INTO audit_logs (user_id,action,details,ip_address,created_at) VALUES (1,'LOGIN','Admin logged in','127.0.0.1','2026-10-20 09:10:00');
INSERT INTO audit_logs (user_id,action,details,ip_address,created_at) VALUES (1,'EVENT_CREATE','Created event #1','127.0.0.1','2026-10-20 09:15:00');
INSERT INTO audit_logs (user_id,action,details,ip_address,created_at) VALUES (1,'STUDENT_UPDATE','Updated student #4','127.0.0.1','2026-10-21 11:02:00');
INSERT INTO audit_logs (user_id,action,details,ip_address,created_at) VALUES (2,'LOGIN','Student logged in','127.0.0.1','2026-10-22 10:00:00');

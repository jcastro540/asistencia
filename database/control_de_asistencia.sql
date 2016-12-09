CREATE DATABASE IF NOT EXISTS control_de_asistencia;
USE control_de_asistencia;

CREATE TABLE users(
    id          int(255)  auto_increment not null,
    name        varchar(255),
    surname     varchar(255),
    username    varchar(255),
    email       varchar(255),
    password    varchar(255),
    imame       varchar(255),
    role        varchar(20),
    CONSTRAINT users_uniques_fields UNIQUE (email, username),
    CONSTRAINT pk_users PRIMARY KEY(id)
)ENGINE = InnoDb;

CREATE TABLE courses(
    id          int(255)  auto_increment not null,
    name        varchar(255),
    startdate   datetime,
    enddate     datetime,
    type        varchar(255),
    num_class   int(255),
    user_id     int(255),
    CONSTRAINT pk_courses PRIMARY KEY(id),
    CONSTRAINT fk_courses_users FOREIGN KEY(user_id) references users(id)
)ENGINE = InnoDb;

CREATE TABLE students(
    id          int(255)  auto_increment not null,
    name        varchar(255),
    email       varchar(255),
    user_id     int(255),
    courses_id  int(255),
    CONSTRAINT pk_students PRIMARY KEY(id),
    CONSTRAINT fk_students_users FOREIGN KEY(user_id) references users(id),
    CONSTRAINT fk_students_courses FOREIGN KEY(courses_id) references courses(id)
)ENGINE = InnoDb;


CREATE TABLE assists(
    id          int(255)  auto_increment not null,
    assists     varchar(255),
    class	varchar(255),
    student_id  int(255),
    course_id   int(255),
    CONSTRAINT pk_assists PRIMARY KEY(id),
    CONSTRAINT fk_assists_students FOREIGN KEY(student_id) references students(id),
    CONSTRAINT fk_assists_courses FOREIGN KEY(course_id) references courses(id)
)ENGINE = InnoDb;


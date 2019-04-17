create table clients
(
  client_id int auto_increment
    primary key,
  surname varchar(255) null,
  name varchar(255) null,
  patronymic varchar(255) null,
  phone varchar(255) null,
  date_of_birth date null,
  is_deleted tinyint(1) default 0 null
);

create table measurement_units
(
  measurement_unit_id int not null
    primary key,
  measurement_unit_name varchar(60) not null,
  measurement_unit_abbr varchar(60) null
);

INSERT INTO measurement_units (measurement_unit_id, measurement_unit_name, measurement_unit_abbr) VALUES (0, 'Per procedure', 'proc');
INSERT INTO measurement_units (measurement_unit_id, measurement_unit_name, measurement_unit_abbr) VALUES (1, 'Per time', 'minute');

create table ordered_services
(
  ordered_service_id int auto_increment
    primary key,
  service_id int null,
  start_time time null,
  end_time time null,
  quantity int null,
  cost decimal(10,2) null,
  discount int null,
  total decimal(10,2) null,
  employee_id int null,
  visit_id int null,
  duration time null,
  is_next_consecutive tinyint(1) not null
);

create table positions
(
  position_id int auto_increment
    primary key,
  position_name varchar(60) not null,
  is_deleted tinyint(1) default 0 not null
);

create table service_categories
(
  category_id int auto_increment
    primary key,
  category_name varchar(60) not null,
  is_deleted tinyint(1) default 0 not null
);

create table services
(
  service_id int auto_increment
    primary key,
  service_name varchar(60) not null,
  category_id int not null,
  service_cost decimal(10,2) not null,
  measurement_unit_id int not null,
  service_duration time not null,
  is_deleted tinyint(1) default 0 not null
);

create table staff
(
  employee_id int auto_increment
    primary key,
  surname varchar(60) null,
  name varchar(60) not null,
  patronymic varchar(60) null,
  position_id varchar(60) not null,
  date_of_birth date null,
  is_deleted tinyint(1) default 0 not null,
  email varchar(60) null,
  phone varchar(60) null
);

create table visits
(
  visit_id int auto_increment
    primary key,
  client_id int not null,
  visit_date date not null,
  isFinished tinyint(1) default 0 null
);


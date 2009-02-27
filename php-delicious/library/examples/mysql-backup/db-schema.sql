create database if not exists delicious
   default character set utf8
   default collate utf8_general_ci;

use delicious;

create table if not exists posts (
   url varchar(250),
   description varchar(250),
   notes text,
   hash varchar(50),
   updated datetime,
   primary key(hash)
);

create table if not exists tags (
   hash varchar(50),
   tag varchar(50)
);
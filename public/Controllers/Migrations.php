<?php

namespace Controllers;

class Migrations
{
    public $migrations = [
        [
            'Description' => 'Add migrations table',
            'SQL_up' => '
                create table migrations(
                    migrationID int primary key auto_increment,
                    description text,
                    status int
                );
            ',
            'SQL_down' => '
                drop table migrations;
            '
        ],
        [
            'Description' => 'Add users table',
            'SQL_up' => '
                create table user(
                    userID int primary key auto_increment,
                    name text,
                    username text,
                    password text,
                    date_created datetime,
                    date_deleted datetime
                );
            ',
            'SQL_down' => '
                drop table user;
            '
        ]
    ];
}
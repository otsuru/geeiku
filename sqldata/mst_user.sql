create table users(
	`id` int(8) unsigned NOT NULL auto_increment,
	`name` varchar(255),
	`chara` varchar(20),
	`icon_name` varchar(255) DEFAULT NULL,
	`icon_ext` varchar(30) DEFAULT NULL,
	`tw_id` varchar(100),
	`tw_token` varchar(255),
	`tw_token_secret` varchar(255),
	`fav_tenpo` varchar(30),
	`fav_tenpo_game` varchar(30),
	`fav_tenpo_2` varchar(30),
	`fav_tenpo_game_2` varchar(30),
	`fav_tenpo_3` varchar(30),
	`fav_tenpo_game_3` varchar(30),
	`remote_addr` varchar(100),
	`user_agent` text ,
	`input_date` int(8) NOT NULL DEFAULT '0',
	`input_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`update_date` int(8) ,
	`update_datetime` datetime ,
	PRIMARY KEY(`id`)
);

create index users_id on users(id);
create index users_fav_tenpo on users(fav_tenpo);
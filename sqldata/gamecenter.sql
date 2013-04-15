create table gamecenter(
	`id` int(8) unsigned NOT NULL auto_increment,
	`name` varchar(100),
	`pref` varchar(20),
	`address` varchar(255),
	`tel` varchar(20),
	`zip` varchar(20),
	`open_time` varchar(20),
	`game` varchar(255),
	`info` text,
	`input_date` int(8) NOT NULL DEFAULT '0',
	`input_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`update_date` int(8) ,
	`update_datetime` datetime ,
	PRIMARY KEY(`id`)
);


create index gamecenter_id on gamecenter(id);
create index gamecenter_name on gamecenter(name);
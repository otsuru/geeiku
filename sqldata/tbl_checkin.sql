create table checkin(
	`id` int(8) unsigned NOT NULL auto_increment,
	`tenpo_id` int8,
	`user_id` int8,
	`user_name` varchar(100),
	`chara` varchar(20),
	`game` varchar(20),
	`checkin_datetime` datetime,
	`comment` text,
	`iine_name` text,
	`iine_id` text,
	PRIMARY KEY(`id`)
);


create index checkin_tenpo_id on checkin(tenpo_id);
create index checkin_user_id on checkin(user_id);
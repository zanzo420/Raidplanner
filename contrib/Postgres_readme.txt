The SQL commands in the install.xml for this mod assume you're using MySQL41.  
If you're not using MySQL41, you'll need to figure out the required changes to port your SQL commands to the right database.  

For more information on this topic please refer to: http://www.phpbb.com/community/viewtopic.php?f=70&t=666195&st=0&sk=t&sd=a&start=225#p3824905

------------------------------------------------------------------------
Fortunately for the Postgres users, kim.toms was kind enough to share the solution:

If you have a solution for a different database system please post it so we can include it in the next release.
------------------------------------------------------------------------

/* WARNING: do NOT execute the SQL commands on the phpbb_acl_options table more 
than once if you're upgrading or trying to repair a previous installation of the calendar mod.
Executing this command more than once will add duplicate entries to the table 
breaking your current permissions and causing the calendar to malfunction */


INSERT INTO phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('a_calendar', 1, 0, 0),
('m_calendar_edit_other_users_events', 1, 0, 0),
('m_calendar_delete_other_users_events', 1, 0, 0),
('u_calendar_view_events', 1, 0, 0),
('u_calendar_create_group_events', 1, 0, 0),
('u_calendar_edit_events', 1, 0, 0),
('u_calendar_delete_events', 1, 0, 0),
('u_calendar_create_public_events', 1, 0, 0),
('u_calendar_create_private_events', 1, 0, 0),
('u_calendar_nonmember_groups', 1, 0, 0),
('u_calendar_track_rsvps', 1, 0, 0),
('u_calendar_allow_guests', 1, 0, 0),
('u_calendar_view_headcount', 1, 0, 0),
('u_calendar_view_detailed_rsvps', 1, 0, 0),
('u_calendar_create_recurring_events', 1, 0, 0),
('m_calendar_edit_other_users_rsvps', 1, 0, 0);


DROP TABLE IF EXISTS phpbb_calendar_config;
CREATE TABLE phpbb_calendar_config (
  config_name varchar(255) NOT NULL,
  config_value varchar(255) NOT NULL
);

INSERT INTO phpbb_calendar_config (config_name, config_value) VALUES 
('first_day_of_week', '0'),
('index_display_week', '0'),
('index_display_next_events', '5'),
('hour_mode', '12'),
('display_truncated_name', '0'),
('prune_frequency', '0'),
('last_prune', '0'),
('prune_limit', '2592000'),
('display_hidden_groups', '0'),
('time_format','h:i a'),
('date_format','M d, Y'),
('date_time_format','M d, Y h:i a'),
('disp_events_only_on_start','0'),
('populate_frequency','86400'),
('last_populate','0'),
('populate_limit','2592000');


DROP TABLE IF EXISTS phpbb_calendar_event_types;
CREATE TABLE phpbb_calendar_event_types (
  etype_id serial ,
  etype_index smallint  NOT NULL default '0',
  etype_full_name varchar(255)  NOT NULL default '',
  etype_display_name varchar(255)  NOT NULL default '',
  etype_color varchar(6)  NOT NULL default '',
  etype_image varchar(255) NOT NULL,
  PRIMARY KEY  (etype_id)
);

INSERT INTO phpbb_calendar_event_types (etype_id,etype_index,etype_full_name,etype_display_name,etype_color,etype_image) VALUES 
(1,1,'Generic Event','','','');

DROP TABLE IF EXISTS phpbb_calendar_events;
CREATE TABLE phpbb_calendar_events (
  event_id serial ,
  etype_id smallint NOT NULL,
  sort_timestamp bigint  NOT NULL,
  event_start_time bigint  NOT NULL,
  event_end_time bigint  NOT NULL,
  event_all_day smallint NOT NULL default '0',
  event_day varchar(10)  NOT NULL default '',
  event_subject varchar(255)  NOT NULL default '',
  event_body bytea NOT NULL,
  poster_id int  DEFAULT '0' NOT NULL,
  event_access_level smallint NOT NULL default '0',
  group_id int  DEFAULT '0' NOT NULL,
  group_id_list varchar(255)  NOT NULL default ',',
  enable_bbcode smallint  NOT NULL default '1',
  enable_smilies smallint  NOT NULL default '1',
  enable_magic_url smallint  NOT NULL default '1',
  bbcode_bitfield varchar(255)  NOT NULL default '',
  bbcode_uid varchar(8)  NOT NULL,
  track_rsvps smallint  NOT NULL default '0',
  allow_guests smallint  NOT NULL default '0',
  rsvp_yes int  DEFAULT '0' NOT NULL,
  rsvp_no int  DEFAULT '0' NOT NULL,
  rsvp_maybe int  DEFAULT '0' NOT NULL,
  recurr_id int  DEFAULT '0' NOT NULL,
  PRIMARY KEY  (event_id)
);


/**********************************************************************
***********************************************************************
*****************************  WARNING ********************************
***********************************************************************
***********************************************************************


NOTE I (alightner) added the necessary edits to the tables above for the 0.1.0 release, 
but have not tested the edits as I don't have an Postgres server on which to test.  
Also the new tables for 0.1.0 (shown below) have yet to be translated for Postgres.
If you translate them, please post your changes so we can include them in the next release.
THANKS!


*/


CREATE TABLE IF NOT EXISTS `phpbb_calendar_recurring_events` (
  `recurr_id` mediumint(8) unsigned NOT NULL auto_increment,
  `etype_id` tinyint(4) NOT NULL,
  `frequency` tinyint(4) NOT NULL default '1',
  `frequency_type` tinyint(4) NOT NULL default '0',
  `first_occ_time` bigint(20) unsigned NOT NULL,
  `final_occ_time` bigint(20) unsigned NOT NULL,
  `event_all_day` tinyint(2) NOT NULL default '0',
  `event_duration` bigint(20) unsigned NOT NULL,
  `week_index` tinyint(2) NOT NULL default '0',
  `first_day_of_week` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
  `last_calc_time` bigint(20) unsigned NOT NULL,
  `next_calc_time` bigint(20) unsigned NOT NULL,
  `event_subject` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  `event_body` longblob NOT NULL,
  `poster_id` mediumint(8) unsigned NOT NULL default '0',
  `poster_timezone` decimal(5,2) NOT NULL default '0.00',
  `poster_dst` tinyint(1) unsigned NOT NULL default '0',
  `event_access_level` tinyint(1) NOT NULL default '0',
  `group_id` mediumint(8) unsigned NOT NULL default '0',
  `group_id_list` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL default ',',
  `enable_bbcode` tinyint(1) unsigned NOT NULL default '1',
  `enable_smilies` tinyint(1) unsigned NOT NULL default '1',
  `enable_magic_url` tinyint(1) unsigned NOT NULL default '1',
  `bbcode_bitfield` varchar(255) character set utf8 collate utf8_bin NOT NULL default '',
  `bbcode_uid` varchar(8) character set utf8 collate utf8_bin NOT NULL,
  `track_rsvps` tinyint(1) unsigned NOT NULL default '0',
  `allow_guests` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`recurr_id`)
);

CREATE TABLE IF NOT EXISTS `phpbb_calendar_rsvps` (
  `rsvp_id` mediumint(8) unsigned NOT NULL auto_increment,
  `event_id` mediumint(8) unsigned NOT NULL default '0',
  `poster_id` mediumint(8) unsigned NOT NULL default '0',
  `poster_name` varchar(255) collate utf8_bin NOT NULL default '',
  `poster_colour` varchar(6) collate utf8_bin NOT NULL default '',
  `poster_ip` varchar(40) collate utf8_bin NOT NULL default '',
  `post_time` int(11) unsigned NOT NULL default '0',
  `rsvp_val` tinyint(1) unsigned NOT NULL default '0',
  `rsvp_count` smallint(4) unsigned NOT NULL default '1',
  `rsvp_detail` mediumtext collate utf8_bin NOT NULL,
  `bbcode_bitfield` varchar(255) collate utf8_bin NOT NULL default '',
  `bbcode_uid` varchar(8) collate utf8_bin NOT NULL,
  `bbcode_options` tinyint(1) unsigned NOT NULL default '7',
  PRIMARY KEY (`rsvp_id`),
  KEY `event_id` (`event_id`),
  KEY `poster_id` (`poster_id`),
  KEY `eid_post_time` (`event_id`,`post_time`)
);


CREATE TABLE IF NOT EXISTS `phpbb_calendar_events_watch` (
  `event_id` mediumint(8) unsigned NOT NULL default '0',
  `user_id` mediumint(8) unsigned NOT NULL default '0',
  `notify_status` tinyint(1) unsigned NOT NULL default '0',
  `track_replies` tinyint(1) unsigned NOT NULL default '0',
  KEY `event_id` (`event_id`),
  KEY `user_id` (`user_id`),
  KEY `notify_stat` (`notify_status`)
);

CREATE TABLE IF NOT EXISTS `phpbb_calendar_watch` (
  `user_id` mediumint(8) unsigned NOT NULL default '0',
  `notify_status` tinyint(1) unsigned NOT NULL default '0',
  KEY `user_id` (`user_id`),
  KEY `notify_stat` (`notify_status`)
);


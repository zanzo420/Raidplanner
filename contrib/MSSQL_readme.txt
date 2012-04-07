The SQL commands in the install.xml for this mod assume you're using MySQL41.  
If you're not using MySQL41, you'll need to figure out the required changes to port your SQL commands to the right database.  

For more information on this topic please refer to: http://www.phpbb.com/community/viewtopic.php?f=70&t=666195&st=0&sk=t&sd=a&start=225#p3824905

------------------------------------------------------------------------
Fortunately for the MSSQL 9 (2005) users, gytr_r1 was kind enough to share the solution:

If you have a solution for a different database system please post it so we can include it in the next release.
------------------------------------------------------------------------


INSERT INTO dbo.phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('a_calendar', 1, 0, 0);
INSERT INTO dbo.phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('m_calendar_edit_other_users_events', 1, 0, 0);
INSERT INTO dbo.phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('m_calendar_delete_other_users_events', 1, 0, 0);
INSERT INTO dbo.phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('u_calendar_view_events', 1, 0, 0);
INSERT INTO dbo.phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('u_calendar_edit_events', 1, 0, 0);
INSERT INTO dbo.phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('u_calendar_delete_events', 1, 0, 0);
INSERT INTO dbo.phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('u_calendar_create_public_events', 1, 0, 0);
INSERT INTO dbo.phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('u_calendar_create_group_events', 1, 0, 0);
INSERT INTO dbo.phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('u_calendar_create_private_events', 1, 0, 0);
INSERT INTO dbo.phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('u_calendar_nonmember_groups', 1, 0, 0);
INSERT INTO dbo.phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('u_calendar_track_rsvps', 1, 0, 0);
INSERT INTO dbo.phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('u_calendar_allow_guests', 1, 0, 0);
INSERT INTO dbo.phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('u_calendar_view_headcount', 1, 0, 0);
INSERT INTO dbo.phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('u_calendar_view_detailed_rsvps', 1, 0, 0);
INSERT INTO dbo.phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('u_calendar_create_recurring_events', 1, 0, 0);
INSERT INTO dbo.phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('m_calendar_edit_other_users_rsvps', 1, 0, 0);



IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[phpbb_calendar_config]') AND type in (N'U'))
DROP TABLE [dbo].[phpbb_calendar_config]

CREATE TABLE dbo.phpbb_calendar_config (config_name varchar(255) NOT NULL, config_value varchar(255) NOT NULL);

INSERT INTO dbo.phpbb_calendar_config (config_name, config_value) VALUES ('first_day_of_week', 0);
INSERT INTO dbo.phpbb_calendar_config (config_name, config_value) VALUES ('index_display_week', 0);
INSERT INTO dbo.phpbb_calendar_config (config_name, config_value) VALUES ('index_display_next_events', 5);
INSERT INTO dbo.phpbb_calendar_config (config_name, config_value) VALUES ('hour_mode', 12);
INSERT INTO dbo.phpbb_calendar_config (config_name, config_value) VALUES ('display_truncated_name', 0);
INSERT INTO dbo.phpbb_calendar_config (config_name, config_value) VALUES ('prune_frequency', 0);
INSERT INTO dbo.phpbb_calendar_config (config_name, config_value) VALUES ('last_prune', 0);
INSERT INTO dbo.phpbb_calendar_config (config_name, config_value) VALUES ('prune_limit', 2592000);
INSERT INTO dbo.phpbb_calendar_config (config_name, config_value) VALUES ('display_hidden_groups', 0);
INSERT INTO dbo.phpbb_calendar_config (config_name, config_value) VALUES ('time_format','h:i a');
INSERT INTO dbo.phpbb_calendar_config (config_name, config_value) VALUES ('date_format','M d, Y');
INSERT INTO dbo.phpbb_calendar_config (config_name, config_value) VALUES ('date_time_format','M d, Y h:i a');
INSERT INTO dbo.phpbb_calendar_config (config_name, config_value) VALUES ('disp_events_only_on_start','0');
INSERT INTO dbo.phpbb_calendar_config (config_name, config_value) VALUES ('populate_frequency','86400');
INSERT INTO dbo.phpbb_calendar_config (config_name, config_value) VALUES ('last_populate','0');
INSERT INTO dbo.phpbb_calendar_config (config_name, config_value) VALUES ('populate_limit','2592000');


IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[phpbb_calendar_event_types]') AND type in (N'U'))
DROP TABLE [dbo].[phpbb_calendar_event_types]

CREATE TABLE dbo.phpbb_calendar_event_types (
  etype_id tinyint identity NOT NULL PRIMARY KEY,
  etype_index tinyint NOT NULL default '0',
  etype_full_name varchar(255) DEFAULT ('') NOT NULL,
  etype_display_name varchar(255) DEFAULT ('') NOT NULL,
  etype_color varchar(6) DEFAULT ('') NOT NULL,
  etype_image varchar(255) NOT NULL,
);

SET IDENTITY_INSERT dbo.phpbb_calendar_event_types ON

INSERT INTO dbo.phpbb_calendar_event_types (etype_id,etype_index,etype_full_name,etype_display_name,etype_color,etype_image) VALUES 
(1,1,'Generic Event','','','');

IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[phpbb_calendar_events]') AND type in (N'U'))
DROP TABLE [dbo].[phpbb_calendar_events]

CREATE TABLE dbo.phpbb_calendar_events (
  event_id int identity NOT NULL PRIMARY KEY,
  etype_id tinyint NOT NULL,
  sort_timestamp bigint NOT NULL,
  event_start_time bigint NOT NULL,
  event_end_time bigint NOT NULL,
  event_all_day tinyint NOT NULL default '0',
  event_day varchar(10) DEFAULT ('') NOT NULL,
  event_subject varchar(255) DEFAULT ('') NOT NULL,
  event_body Text  NOT NULL,
  poster_id int DEFAULT '0' NOT NULL,
  event_access_level tinyint NOT NULL default '0',
  group_id int DEFAULT '0' NOT NULL,
  group_id_list varchar(255) DEFAULT ('') NOT NULL,
  enable_bbcode tinyint NOT NULL default '1',
  enable_smilies tinyint NOT NULL default '1',
  enable_magic_url tinyint NOT NULL default '1',
  bbcode_bitfield varchar(255) DEFAULT ('') NOT NULL,
  bbcode_uid varchar(8) DEFAULT ('') NOT NULL,
  track_rsvps tinyint NOT NULL default '0',
  allow_guests tinyint NOT NULL default '0',
  rsvp_yes int DEFAULT '0' NOT NULL,
  rsvp_no int DEFAULT '0' NOT NULL,
  rsvp_maybe int DEFAULT '0' NOT NULL,
  recurr_id int DEFAULT '0' NOT NULL,

);


/**********************************************************************
***********************************************************************
*****************************  WARNING ********************************
***********************************************************************
***********************************************************************


NOTE I (alightner) added the necessary edits to the tables above for the 0.1.0 release, 
but have not tested the edits as I don't have an MSSQL server on which to test.  
Also the new tables for 0.1.0 (shown below) have yet to be translated for MSSQL.
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


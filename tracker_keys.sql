DROP TABLE IF EXISTS tracker_keys;
CREATE TABLE tracker_keys (
  created bigint(20)   NOT NULL default '0',
  date_created varchar(10) NOT NULL default '',
  keydata varchar(128) NOT NULL default '',
  UNIQUE KEY (date_created,keydata)
) TYPE=MyISAM;

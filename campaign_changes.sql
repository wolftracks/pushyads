
DROP TABLE IF EXISTS campaign_changes;
CREATE TABLE widget (
  member_id varchar(20) NOT NULL default '',
  firstname varchar(30) NOT NULL default '',
  lastname  varchar(30) NOT NULL default '',
  email     varchar(65) NOT NULL default '',
  old_level int(11) not null default '0',
  new_level int(11) not null default '0'
) TYPE=MyISAM;


CREATE TABLE whats_new (
  event_id    int(11) NOT NULL default '0',
  yymmdd      varchar(10) NOT NULL default '',
  member_id   varchar(20) NOT NULL default '',
  sales_pro   int(11) NOT NULL default '0',
  ad_id       bigint(20) NOT NULL auto_increment,
  product_id  bigint(20) NOT NULL default '0',
  widget_key  varchar(64) NOT NULL default '',
) TYPE=MyISAM;



new signups
   -  member_id, name, user_level, email confirmed, registered, lastaccess

new widgets created
   -  member_id, name, user_level,  total_access_count, uri

new products uploaded
   -  product_owner, name, user_level,  product_id, product_name, product_title

new ads created
   -  member_id, name, user_level,  ad_id, product_name, marketing_selection

ad designations
   -  member_id, name, user_level,  ad_id, product_name, marketing_selection


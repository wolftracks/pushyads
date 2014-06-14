SELECT w5_h5 from tracker_widget_category;
SELECT w5_h5, userkey,widget_key from tracker_widget_category;
SELECT sum(w5_h5), userkey,widget_key from tracker_widget_category group by userkey, widget_key;
SELECT sum(w5_h5), userkey from tracker_widget_category group by userkey;
SELECT sum(w5_h5), widget_key from tracker_widget_category group by widget_key;
SELECT sum(w5_h5), userkey from tracker_widget_category group by userkey;

SELECT w5_h6 from tracker_widget_category;
SELECT w5_h6, userkey,widget_key from tracker_widget_category;
SELECT sum(w5_h6), userkey,widget_key from tracker_widget_category group by userkey, widget_key;
SELECT sum(w5_h6), userkey from tracker_widget_category group by userkey;
SELECT sum(w5_h6), widget_key from tracker_widget_category group by widget_key;
SELECT sum(w5_h6), userkey from tracker_widget_category group by userkey;



SELECT w5_h5, w5_h6 from tracker_widget_category;
SELECT w5_h5, w5_h6, userkey,widget_key from tracker_widget_category;
SELECT sum(w5_h5), sum(w5_h6), userkey,widget_key from tracker_widget_category group by userkey, widget_key;
SELECT sum(w5_h5), sum(w5_h6), userkey from tracker_widget_category group by userkey;
SELECT sum(w5_h5), sum(w5_h6), widget_key from tracker_widget_category group by widget_key;
SELECT sum(w5_h5), sum(w5_h6), userkey from tracker_widget_category group by userkey;

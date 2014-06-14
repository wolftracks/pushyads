call mysqldump  -hpushyads.com -utjwolf -pdragon --add-drop-table pushyads      >remote-pushyads.backup
call mysqldump  -hpushyads.com -utjwolf -pdragon --add-drop-table pushyimage    >remote-pushyimage.backup
call mysqldump                 -utjwolf -pdragon --add-drop-table pushyads      >pushyads.backup
call mysqldump                 -utjwolf -pdragon --add-drop-table pushyimage    >pushyimage.backup

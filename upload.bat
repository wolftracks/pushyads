del c:\pushyads.zip

cd c:\xampp\pushyads
call pkzip32 -arp c:\pushyads.zip *
cd c:\
call wput pushyadsupload pushyads.zip
cd c:\xampp\pushyads

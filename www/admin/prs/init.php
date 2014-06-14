<?php
// header("Status: 200 OK");
header("HTTP/1.1 200 OK");
include_once("users.php");

$author="";
$err=TRUE;
$pathInfo=FALSE;
$qs=explode("&",$QUERY_STRING);
for ($i=0; $i<count($qs); $i++)
  {
    list($key,$value)=split("=",$qs[$i],2);
    if ($key=="404")
      {
        $pathInfo=TRUE;
        break;
      }
  }
if ($pathInfo)
  {
    $j=0;
    $author="";
    $pinfo=explode("/",$REQUEST_URI);
    for ($i=0; $i<count($pinfo); $i++)
      {
        if (strlen($pinfo[$i])>0)
          {
            $j++;
            if ($j==2)
              {
                $author=strtolower($pinfo[$i]);
                break;
              }
          }
      }
  }

$author = trim($author);
if (strlen($author) == 0 && (strlen($PRSAUTHOR) > 0))
  {
    $author=$PRSAUTHOR;
  }

if (strlen($author) > 0)
  {
    if (isset($users[$author]))
      {
        $PRSAUTHOR=$author;
      }
    else
      {
        $author="";
        $PRSAUTHOR="";
      }
    setcookie("PRSAUTHOR",$PRSAUTHOR,time()+94608000,"/","",0);
    include ("index.php");
    exit;
  }
include ("signin.php");
exit;
?>

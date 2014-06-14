<?php
header("Status: 200 OK");
require("../users.php");

// if (is_array($_COOKIE))
//    {
//      while (list($key, $value) = each($_COOKIE))
//        {
//          printf("X) %s=%s<br>\n",$key,$value);
//        }
//    }

//  if (is_array($GLOBALS))
//    {
//      while (list($key, $value) = each($GLOBALS))
//        {
//          printf("Y) %s=%s<br>\n",$key,$value);
//        }
//    }
//
//
//  if (is_array($_SERVER))
//    {
//      while (list($key, $value) = each($_SERVER))
//        {
//          printf("Z) %s=%s<br>\n",$key,$value);
//        }
//    }

//
//if (!isset($SVCAUTHOR) || strlen($SVCAUTHOR)==0)
//   $SVCAUTHOR=$_COOKIE["SVCAUTHOR"];
//
//printf("SVCAUTHOR: %s<br>\n",$SVCAUTHOR);
//printf("SVCAUTHOR[]: %s<br>\n",$_COOKIE["SVCAUTHOR"]);

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
if (strlen($author) == 0 && (strlen($SVCAUTHOR) > 0))
  {
    $author=$SVCAUTHOR;
  }

if (strlen($author) > 0)
  {
    if (isset($users[$author]))
      {
        $SVCAUTHOR=$author;
      }
    else
      {
        $author="";
        $SVCAUTHOR="";
      }
    setcookie("SVCAUTHOR",$SVCAUTHOR,time()+94608000,"/","",0);
    include ("index.php");
    exit;
  }
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("signin.php");
exit;
?>

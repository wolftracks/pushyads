<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");

$db = getPushyDatabaseConnection();

function listTables($db)
  {
    $tables=array();
    $tcount=0;
    $sql  = "SHOW TABLES";
    $result = mysql_query($sql,$db);
    if ($result)
      {
        while ($myrow=mysql_fetch_array($result))
          {
            $tables[$tcount]=$myrow[0];
            $tcount++;
          }
      }
    return $tables;
  }
?>

<html>
<head>

<link rel="stylesheet" type="text/css" href="/local-css/styles.css">

<script type="text/javascript">
<!---
var win=null;

function validate(theForm)
  {
    if (theForm.sql_table.selectedIndex==0)
      {
        alert("Select Table");
        theForm.sql_table.focus();
        return false;
      }

    return true;
  }

//-->
</script>

<title>DB Edit</title>
</head>

<body>

<form name="QUERY" method="POST" action="select.php" onSubmit=return validate(this)>
  <input type="hidden" name="op" value="SQL-SELECT">

  <table width=600 cellpadding=4 cellspacing=2>
    <tr>
      <td width="200" class="bigdarkbluebold">SELECT</td>
      <td width="400" class="normaltext">&nbsp;</td>
    </tr>
    <tr>
      <td width="200" class="normaldarkbluebold">Table:</td>
      <td width="400" class="normaltext">
        <select name="sql_table" class="smalltext"><option value="" selected>---- SELECT TABLE -----</option>
          <?php
            $tables = listTables($db);
            for ($i=0; $i<count($tables); $i++)
              {
                echo "   <option value=\"".$tables[$i]."\">".$tables[$i]."</option>\n";
              }
          ?>
        </select>
      </td>
    </tr>
    <tr height="30">
      <td width="200" class="normaldarkbluebold">&nbsp;</td>
      <td width="400" class="normaltext">
        <input type="submit" class="button" value="  Submit  ">
      </td>
    </tr>

  </table>
</form>

</body>
</html>

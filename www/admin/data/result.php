<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");

$op=$_REQUEST["op"];
$sql_table=$_REQUEST["sql_table"];
$sql_field=$_REQUEST["sql_field"];
$sql_compare=$_REQUEST["sql_compare"];
$sql_value=$_REQUEST["sql_value"];

$db = getPushyDatabaseConnection();


function describeTable($db,$tablename)
  {
    printf("\n\n------ TABLE=%s -------------------------------\n",$tablename);
    printf("%-22s | ","Field Name");
    printf("%-12s | ","Type");
    printf("%-6s | ","Null");
    printf("%-6s | ","Key");
    printf("%-8s | ","Default");
    printf("%-10s\n","Extra");

    $sql  = "DESCRIBE $tablename";
    $result = mysql_query($sql,$db);
    if ($result)
      {
        while ($myrow=mysql_fetch_array($result))
          {
            $field   = $myrow[0];
            $type    = $myrow[1];
            $null    = $myrow[2];
            $key     = $myrow[3];
            $default = $myrow[4];
            $extra   = $myrow[5];

            printf("%-22s | ",$field);
            printf("%-12s | ",$type);
            printf("%-6s | ",$null);
            printf("%-6s | ",$key);
            printf("%-8s | ",$default);
            printf("%-10s\n",$extra);
          }
      }
  }


function listFields($db, $tablename)
  {
    $fields=array();
    $fcount=0;
    $sql  = "DESCRIBE $tablename";
    $result = mysql_query($sql,$db);
    if ($result)
      {
        while ($myrow=mysql_fetch_array($result))
          {
            $fields[$fcount] = array(
               "fieldname"    => $myrow[0],
               "fieldtype"    => $myrow[1],
               "fieldnull"    => $myrow[2],
               "fieldkey"     => $myrow[3],
               "fielddefault" => $myrow[4],
               "fieldextra"   => $myrow[5]);
            $fcount++;
          }
      }
    return $fields;
  }


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


function _select($db,$tablename,$where="",$order="",$group="",$limit="")
  {
    $fields=array();
    $fcount=0;
    $sql  = "DESCRIBE $tablename";
    $result = mysql_query($sql,$db);
    if ($result)
      {
        while ($myrow=mysql_fetch_array($result))
          {
            $fields[$fcount] = array(
               "fieldname"    => $myrow[0],
               "fieldtype"    => $myrow[1],
               "fieldnull"    => $myrow[2],
               "fieldkey"     => $myrow[3],
               "fielddefault" => $myrow[4],
               "fieldextra"   => $myrow[5]);
            $fcount++;
          }

        $sql = "SELECT * from $tablename";
        if (strlen($where) > 0)
          $sql .= " WHERE $where";
        if (strlen($order) > 0)
          $sql .= " ORDER BY $order";
        if (strlen($group) > 0)
          $sql .= " GROUP BY $order";
        if (strlen($limit) > 0)
          $sql .= " LIMIT $limit";

        $result = mysql_query($sql,$db);

        printf("SQL: %s<br>\n",$sql);
        printf("ERR: %s<br>\n",mysql_error());
        printf("ROWS: %s<br>\n",mysql_num_rows($result));


        $rows=array();
        $rcount=0;
        if ($result)
          {
            while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
              {
                $rows[$rcount]=$row;
                $rcount++;
              }
          }
      }

    return array($fields,$rows);
  }

//describeTable($db,"account");
//describeTable($db,"member");
//describeTable($db,"profile");
//describeTable($db,"campaign");
//describeTable($db,"participant");
//describeTable($db,"leadtable");


// list($fields, $rows) = _select($db,"participant");
//
// for ($i=0; $i<count($rows); $i++)
// for ($i=0; $i<1; $i++)
//  {
//    $row=$rows[$i];
//    for ($j=0; $j<count($fields); $j++)
//      {
//        $field=$fields[$j];
//        printf("%s => %s\n", $field["fieldname"],$row[$field["fieldname"]]);
//      }
//  }
?>


<html>
<head>

<link rel="stylesheet" type="text/css" href="../autoprospector/styles.css">

<title>DB Edit</title>
</head>

<body>


<?php
 $where="$sql_field $sql_compare '$sql_value'";
 // $sql="SELECT * FROM $sql_table WHERE $where";
 list($fields, $rows) = _select($db,$sql_table,$where, "", "", "5");
?>


<form name="RESULT" method="POST" action="result.php">
  <input type="hidden" name="op" value="<?php echo $op?>">
  <input type="hidden" name="sql_table" value="<?php echo $sql_table?>">

  <table width=600 cellpadding=2 cellspacing=0>

<?php
    for ($i=0; $i<count($rows); $i++)
      {
        $bg="#FFFFFF";
        if ($i%2==1)
          $bg="#E0E0E0";
        echo "<tr><td colspan=\"3\">&nbsp;</td></tr>\n";

        $row=$rows[$i];
        for ($j=0; $j<count($fields); $j++)
          {
            $field=$fields[$j];
            $fieldname  = $field["fieldname"];
            $fieldtype  = $field["fieldtype"];
            $key        = $field["fieldkey"];
            $extra      = $field["fieldextra"];
            $fieldvalue = $row[$fieldname];
?>

          <tr bgcolor="<?php echo $bg?>">
            <td width="200" class="normaldarkbluebold"><?php echo $fieldname?></td>
            <td width="200" class="smalldarkredbold"><?php echo $fieldtype?></td>
            <td width="400" class="smalltext">
              <input type="text" size="40" name="<?php echo $fieldname?>" value="<?php echo $fieldvalue?>">&nbsp;&nbsp;&nbsp;<?php echo $key?>&nbsp;&nbsp;&nbsp;<?php echo $extra?>
            </td>
          </tr>
<?php
          }
      }
?>

  </table>
</form>

</body>
</html>

<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");

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
if ($op == "SQL-SELECT")
  {
    $where="$sql_field $sql_compare '$sql_value'";
    $sql="SELECT * FROM $sql_table WHERE $where";
    printf("SQL: %s<br><br>\n",$sql);

    list($fields, $rows) = _select($db,$sql_table,$where);
    for ($i=0; $i<count($rows); $i++)
      {
        $row=$rows[$i];
        for ($j=0; $j<count($fields); $j++)
          {
            $field=$fields[$j];
            printf("%s => %s\n", $field["fieldname"],$row[$field["fieldname"]]);
          }
      }
  }
?>


<form method="POST" action="index.php">
  <input type="hidden" name="op" value="SQL-SELECT">

  <table width=600 cellpadding=4 cellspacing=2>
    <tr>
      <td width="200" class="bigdarkbluebold">SELECT</td>
      <td width="400" class="normaltext">&nbsp;</td>
    </tr>
    <tr>
      <td width="200" class="normaldarkbluebold">Table:</td>
      <td width="400" class="normaltext">
        <select name="sql_table" class="smalltext">
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
    <!---
    <tr>
      <td width="200" class="normaldarkbluebold">Operation:</td>
      <td width="400" class="normaltext">
        <select name="sql_verb" class="smalltext"><option value="SELECT" selected>SELECT</option>
          <option value="UPDATE" selected>UPDATE</option>
          <option value="DELETE" selected>DELETE</option>
        </select>
      </td>
    </tr>
    --->
    <tr>
      <td width="200" class="normaldarkbluebold">Where:</td>
      <td width="400" class="normaltext">
        <select name="sql_field" class="smallmono">
          <?php
            $fields = listFields($db,"participant");
            $longest=0;
            for ($i=0; $i<count($fields); $i++)
              {
                $field = $fields[$i];
                $fieldname = $field["fieldname"];
                if (strlen($fieldname) > $longest)
                  $longest=strlen($fieldname);
              }
            $longest+=3;

            for ($i=0; $i<count($fields); $i++)
              {
                $field = $fields[$i];
                $fieldname = $field["fieldname"];
                $fieldtype = $field["fieldtype"];
                $pad = $longest - strlen($fieldname);
                $vpad="";
                for ($j=0; $j<$pad; $j++) $vpad.="&nbsp;";

                echo "   <option value=\"$fieldname\">$fieldname $vpad $fieldtype</option>\n";
              }
          ?>
        </select>
      </td>
    </tr>
    <tr>
      <td width="200" class="normaldarkbluebold">&nbsp;</td>
      <td width="400" class="normaltext">
        <select name="sql_compare" class="smallmono"><option value="=" selected> = </option>
           <option value="!="  selected> != </option>
           <option value="<"  selected> < </option>
           <option value=">"  selected> > </option>
           <option value="<=" selected> <= </option>
           <option value=">=" selected> >= </option>
           <option value="like" selected> like </option>
        </select>
      </td>
    </tr>
    <tr>
      <td width="200" class="normaldarkbluebold">&nbsp;</td>
      <td width="400" class="normaltext"><b>Value:</b>&nbsp;&nbsp;
        <input type="text" name="sql_value" value="">
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

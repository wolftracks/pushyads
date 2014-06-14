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
showTables($db);
describeTable($db,"participant");
//describeTable($db,"leadtable");


$tables = listTables($db);
list($fields, $rows) = _select($db,"participant");

// for ($i=0; $i<count($rows); $i++)
for ($i=0; $i<1; $i++)
  {
    $row=$rows[$i];
    for ($j=0; $j<count($fields); $j++)
      {
        $field=$fields[$j];
        printf("%s => %s\n", $field["fieldname"],$row[$field["fieldname"]]);
      }
  }
?>

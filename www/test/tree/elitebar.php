<?php
$DEBUG=FALSE;

include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");

include_once("pushy_tree.inc");
include_once("pushy_imagestore.inc");


function selectEliteProducts($db,$num)
  {
    $products=array();
    $mIndex=array();

    $sql  = "SELECT * from ads LEFT JOIN member USING(member_id) LEFT JOIN product USING(product_id) ";
    $sql .= " WHERE member.user_level=2 ";
    $sql .= " AND   member.member_disabled=0 ";
//- $sql .= " AND   product.product_approved>0 ";
    $sql .= " AND   ads.reseller_listing=0";
    $sql .= " AND   ads.pushy_network>0";

    //---- NEXT Line is for ROTATION over RANDOM
    $sql .= " ORDER BY ads.lastview_elitebar, ads.ad_id LIMIT $num";

    $result = mysql_query($sql,$db);
    if ($result && (($count=mysql_num_rows($result))>0))
      {
        if ($count<$num)
           $num=$count;

           // ---------- RANDOM instead of ROTATION -----------------
        if (FALSE)
          {  //---- The Following is for Randomizing ... We are doing ROTATION instead
              for ($i=0; $i<$num; $i++)
                {
                  $m=rand(0,$count-1);
                  // printf(" ...m=%d\n",$m);
                  while (isset($mIndex[$m]))
                    {
                      $m=rand(0,$count-1);
                      // printf(" .....m=%d\n",$m);
                    }
                  $mIndex[$m]=TRUE;
                }
              ksort($mIndex);

              // print_r($mIndex);
              // exit;

              foreach($mIndex as $indexValue=>$bool)
                {
                  // printf("indexValue=%d\n",$indexValue);
                  if ($indexValue > 0)
                    mysql_data_seek($result, $indexValue);
                  if ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
                    {
                      // printf("MID = \n",$myrow[0]);
                      $products[]=$myrow;
                    }
                }
          }

           // ---------- ROTATION instead of RANDOM  -----------------
        if (TRUE)
          {
            while ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
              {
                $products[]=$myrow;
              }
          }



        if (count($products) > 0)
          {
            $count=count($products);
            $viewed = getmicroseconds();
            $sql    = "UPDATE ads set lastview_elitebar='$viewed' WHERE (";
            for ($i=0; $i<$count; $i++)
              {
                $myrow=$products[$i];
                if ($i>0)
                  $sql .= " OR ";
                $sql .= " ad_id='".$myrow["ad_id"]."' ";
              }
            $sql .= " )";
            $result = mysql_query($sql,$db);
          }
      }

    // ---- DO WE NEED TO Scramble the Order ???
    if (FALSE)
      {
        $productList=array();
        if (count($products) > 0)  // Scramble the order
          {
            $count=count($products);
            for ($j=0; $j<$count; $j++)
              {
                $m=rand(0,$count-1);
                while (isset($productList[$m]))
                  {
                    $m=rand(0,$count-1);
                  }
                $productList[$m]=$products[$j];
              }
          }
        $products = $productList;
      }

    // print_r($products);
    return $products;
  }


set_time_limit(0);
$db = getPushyDatabaseConnection();

$products = selectEliteProducts($db,10);
print_r($products);

?>

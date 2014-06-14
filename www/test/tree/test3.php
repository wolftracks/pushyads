<?php
 include_once("pushy_common.inc");
 include_once("pushy_commonsql.inc");
 include_once("pushy.inc");
 include_once("pushy_tree.inc");
 include_once("pushy_imagestore.inc");
 include_once("tree_gen_options.php");







 function genCategories()
   {
     global $ProductCategories;
     $keys = array_keys($ProductCategories);
     sort($keys);

     $temp=array();
     $num=rand(2,14);
     for ($j=0; $j<$num; $j++)
       {
         $z=rand(0,count($keys)-1);
         $k=$keys[$z];
         while (isset($temp[$k]))
           {
             $z=rand(0,count($keys)-1);
             $k=$keys[$z];
           }
         $temp[$k]=TRUE;
       }

     $keys = array_keys($temp);
     sort($keys);
     $s="~";
     for ($j=0; $j<count($keys); $j++)
       $s .= $keys[$j]."~";
     return $s;
   }


 for ($i=0; $i<20; $i++)
   {
     $s=genCategories();
     printf("S=%s\n",$s);
   }



 $temp = array(
   "Zach"  =>   "Baker",
   "Andy"  =>   "McCarville",
   "Tony"  =>   "Anderson",
   "Mike"  =>   "Vance",
   "Jennifer" => "Morris",
 );

 ksort($temp);
 print_r($temp);
 asort($temp);
 print_r($temp);
 krsort($temp);
 print_r($temp);
 arsort($temp);
 print_r($temp);

?>

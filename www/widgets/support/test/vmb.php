<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
</head>
<body bgcolor="ffffff">
<!-- body bgcolor="ffffff" onload=init() -->

<?php
   $rows=5;
   $cols=10;
   $rowHeight = 100;
   $colWidth  = 80;
   $home_row=rand(1,$rows);
   $home_col=rand(1,$cols);
   $text="Now is the Time for all good Men to Come to the Aid of their Country";

   $inline=TRUE;
?>

<table width="<?php echo (($cols*$colWidth)+(360-$colWidth))?>" height="<?php echo (($rows*$rowHeight)+(445-$rowHeight))?>" cellpadding=0 cellspacing=0 border=1>
<?php
  if ($inline)
   {
?>
     <tr id="PUSHY_DATA_ROW">
       <td id="PUSHY_DATA" colspan=3>&nbsp;</td>
       <?php
          for ($c=4; $c<=$cols; $c++) // Cols
            {
       ?>
              <td width="<?php echo $colWidth?>">&nbsp;</td>
       <?php
            }
       //====================================================================
       ?>
     </tr>
<?php
   }
?>
<?php
   for ($r=1; $r<=$rows; $r++) // Rows
     {
?>
       <tr>
<?php
       $ct = rand(1,$cols);
       for ($c=1; $c<=$cols; $c++) // Cols
         {
           $home=FALSE;
           $bg="#FFFFFF";
           if ($r==$home_row && $c==$home_col)
             {
               $home=TRUE;
               $bg="#E0E0FF";
               $t="<div id=\"PUSHY_HOME\" style=\"position:absolute;\"></div>";
             }
           else
             {
               $t="&nbsp;";
               if ($c==$ct) $t=$text;
             }
?>
             <td width="<?php echo $colWidth?>" bgcolor="<?php echo $bg?>"><?php echo $t?></td>
<?php
         }
?>
       </tr>
<?php
     }
?>
</table>


<!-- Local  script type="text/javascript" src="<?php echo PUSHYWIDGETS?>/control/f95c5aa2ed12439ddf7266eadaa487b1.js"></script -->
<!-- Remote script type="text/javascript" src="<?php echo PUSHYWIDGETS?>/control/bfcf9c8318ba1b5d3412f6f1710eceb2.js"></script -->


<?php
if (is_integer(strpos(DOMAIN,"pushyads.com")))
  {  // Runs when invoked on PUSHYADS.COM
?>

    <script type="text/javascript" src="<?php echo PUSHYWIDGETS?>/control/f95c5aa2ed12439ddf7266eadaa487b1.js"></script>

<?php
  }
else
  {  // Runs when invoked on PUSHYADS.NET (LOCAL)
?>

    <script type="text/javascript" src="<?php echo PUSHYWIDGETS?>/control/f7604011ed3cd0188d8e457aec6615d8.js"></script>

<?php
  }
?>

</body>
</html>

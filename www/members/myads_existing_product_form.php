  <?php
    $selected_tag="PRODUCT_SELECTED_".getmicroseconds();
  ?>
  <tr>
    <td align="right" valign="middle" width=180 class=size16><b>Product Name:</b>
      <a href="#" onClick="return false" style="cursor:help">
      <img src="http://pds1106.s3.amazonaws.com/images/question1.png" border=0 style="vertical-align: middle;"  onmouseover="TagToTip('HELP-MYADS-107')"></a>
    </td>
    <td width=420>

        <?php
          //  echo "<PRE>\n";
          //  print_r($myproducts);
          //  echo "</PRE>\n";
        ?>

        <?php
          $product_list=array();
          $sql  = "SELECT * from ads LEFT JOIN product USING(product_id) ";
          $sql .= " WHERE ads.product_list!=0";   // On an Elite Member's Product List - i.e. is being Offered by An Elite Member to Resellers
          $sql .= " AND   ads.existing_products_requested!=0";
          $result = mysql_query($sql,$db);
          if ($result)
            {
              while ($myrow = mysql_fetch_array($result, MYSQL_ASSOC))
                {
                  $pid    = $myrow["product_id"];
                  $pname  = stripslashes($myrow["product_name"]);

                  // printf("%s ==> %s<br>\n",$pid,$pname);

                  //--------------------------------------------------------------------------------
                  // Bypass the Product If it Has Not Yet Been Approved For the Affiliate Offers List
                  //--------------------------------------------------------------------------------
                  $sql  = "SELECT product_owner from product_pending ";
                  $sql .= " WHERE replaces_product_id='$pid'";
                  $sql .= " AND   existing_products_requested!=0";
                  $res  = mysql_query($sql,$db);
                  if (($res) &&  ($pendingRow = mysql_fetch_array($res)))
                    {
                                   // Pending Approval as First Submission for Affiliate Offers List
                      // printf("%s *** %s *** PO=%s *** IAM=%s ***<br>\n",$pid,$pname,$pendingRow["product_owner"],$mid);
                      // if ($pendingRow["product_owner"] == $mid)
                         continue;
                    }
                  //--------------------------------------------------------------------------------

                  $product_list[$pid]=$pname;
                }
            }
          if ($op=="Update" && strlen($product_id)>0 && !isset($product_list[$product_id]))
            {
              $sql  = "SELECT product_name from product";
              $sql .= " WHERE product_id='$product_id'";
              $res = mysql_query($sql,$db);
              if ($res && (mysql_num_rows($res)>0) && ($myrow = mysql_fetch_array($res, MYSQL_ASSOC)))
                {
                  $pname  = stripslashes($myrow["product_name"]);
                  $product_list[$product_id]=$pname;
                }
            }
          asort($product_list);
          // var_dump($myproducts);
          // var_dump($product_list);
        ?>

        <SELECT NAME="existing_product_id" class=input style="width:260px; background-color: #FFFDF7" onChange=javascript:existing_product_selected(this.form,'<?php echo $selected_tag?>')>
          <OPTION VALUE="" selected>&nbsp; - Select Affiliate Offer Here - </option>
          <?php
             $product_ids_listed=array();
             $product_names_listed=array();
             foreach($product_list AS $pid=>$pname)
               {
                 $product_name = strtolower($pname);
                 if ( isset($product_ids_listed[$pid])      ||
                      isset($product_names_listed[$product_name]) )
                   { }
                 else
                 if ($op=="Update" && $pid==$product_id)
                   {
                     echo "<option value=\"$pid\" selected> $pname </option>\n";
                     $product_ids_listed[$pid]=TRUE;
                     $product_names_listed[$product_name]=TRUE;
                   }
                 else
                   {
                     if (!isset($myproducts[$pid]))
                       {
                          echo "<option value=\"$pid\"> $pname </option>\n";
                          $product_ids_listed[$pid]=TRUE;
                          $product_names_listed[$product_name]=TRUE;
                       }
                     else
                     if ($myproducts[$pid])  // put it on the drop down If I am Owner or I'll be Concerned if it Don't see it
                       {
                          echo "<option value=\"~$pid\"> $pname </option>\n";
                          $product_ids_listed[$pid]=TRUE;
                          $product_names_listed[$product_name]=TRUE;
                       }
                   }
               }
          ?>
        </SELECT>

    </td>
  </tr>
  <?php
    if ($op=="Update")
      {
        $operation=$op;
  ?>
        <tr>
          <td colspan=2 id="<?php echo $selected_tag?>" style="background-image: url('http://pds1106.s3.amazonaws.com/images/bg-myads.gif'); background-repeat: repeat-x;"><?php include("myads_fetch_existing_product.php");?></td>
        </tr>
  <?php
      }
    else
      {
  ?>
        <tr>
          <td colspan=2 id="<?php echo $selected_tag?>" style="display:none; background-image: url('http://pds1106.s3.amazonaws.com/images/bg-myads.gif'); background-repeat: repeat-x;"></td>
        </tr>
  <?php
      }
  ?>
  <tr>
    <td align="right" valign="middle" width=180 class=size16><b>Your Affiliate URL:</b>
       <a href="#" onClick="return false" style="cursor:help">
       <img src="http://pds1106.s3.amazonaws.com/images/question1.png" border=0 style="vertical-align: middle;"  onmouseover="TagToTip('HELP-MYADS-103')"></a>
    </td>
    <td width=420>
      <?php
         $purl="http://";
         if ($op=="Update" && strlen($product_url) >= 7  && substr($product_url,0,7) == "http://")
           $purl=$product_url;
      ?>
      <input id="existing_product_url" name="existing_product_url" class="form_input" type="text" maxlength="100" value="<?php echo $purl?>" style="width:260px; background-color: #FFFDF7"> &nbsp;
      <input style="vertical-align: 1px;" type=button tabindex=-1 value="Test URL" onClick=myads_TestAffiliateURL(this.form,this.form.existing_product_url)>
    </td>
  </tr>

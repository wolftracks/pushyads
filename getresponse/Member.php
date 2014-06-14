<?php
/**
 */
class Member {
  /*
   *
   */
  private $memberInfo = array (
                                'firstname' => 'Tim',
                                'lastname'  => 'Wolf',
                                'email'     => 'tim@webtribune.com',
                              );

  public function getFirstName($args)
    {
      $member_id=$args["member_id"];
      return $this->memberInfo["firstname"];
    }

  public function getLastName($args)
    {
      $member_id=$args["member_id"];
      return $this->memberInfo["lastname"];
    }

  public function getMemberInfo($args)
    {
      $member_id=$args["member_id"];
      $this->memberInfo["member_id"]=$member_id;
      return $this->memberInfo;
    }
}
?>

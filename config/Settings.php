<?php
 
 	namespace app;
 	
	class Settings
	{
	  public $secret_key = "sk_test_Frm8tQlsUtNonW2HbrcnRLyI";
	 
	  public function GetValue($secret_key)
	  {
	      $this->secret_key = $secret_key;
	      return $this->secret_key;
	  }
	  
	}
?>
<?php

//definitions
  $max_messages=20;
  $message_text="";
  $message_file=null;
  $visible_messages=null;
  

  function receive_input()
  {
      global $message_text;
      $message_text = $_POST['message_text'];          
  }

  function save_input()
  {
      global $message_text;
      global $message_file;
      
      $message_file=fopen("chat.log", "a");
      fwrite($message_file,$message_text . "\n");
      fclose($message_file);
      $message_file=null;    
  }

  function prepare_output()
  {
      global $visible_messages;
      global $message_file;
      global $max_messages;
      
      $visible_messages=array();
      
      $message_file=fopen("chat.log","r");
      fseek($message_file, 0, SEEK_END);
      $file_size=ftell($message_file);
      fseek($message_file, 0, SEEK_SET);      
      $chat_text=fread($message_file, $file_size);
      fclose($message_file);
      $message_file=null;

      $messages_array=explode("\n",$chat_text);
      $messages_count = count($messages_array);
      
      if($messages_count > $max_messages){
          $visible_messages=array_slice($messages_array,-$max_messages);
      }
      else{
          $visible_messages=$messages_array;
      }
      
  }


//definitions








//script
if(isset($_POST['message_text'])){
    receive_input();
    save_input();
}

prepare_output();
//script

?>


<!DOCTYPE html>
<html>
	<head>
		<title> Тестовый чат </title>
		<style>@import url('style.css');</style>
	</head>
	<body>
		<div id="interface">
			<div id="chatview">
              <?php
                foreach($visible_messages as $message){
                    echo "<p>$message</p>\n";
                }
              ?>
			</div>
			<form id="form" method="post" action="index.php">
				<input name="message_text" type="text" id="usermsg" size="63" />
				<input type="submit" name="enter" id="enter" value="Send" />
			</form>
		</div>
	</body>
</html>

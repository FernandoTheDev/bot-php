<?php

/*
* By FernandoTheDev
*/

 $input = file_get_contents('php://input');
 
 $update = json_decode($input);
 
 $message = $update->message;
 
 $chat_id = $message->chat->id;
 
 $message_id = $update->message->message_id;
 $tipo = $message->chat->type;
 
 $text = $message->text;
 
 $id = $message->from->id;
 
 $is_bot = $message->from->is_bot;
 
 $is_premium = "no";
 
 if($message->from->is_premium){
 
     $is_premium = "yes";
     
 }
 
 $name = $message->from->first_name;

 $user = $message->chat->username;
 
 $data = $update->callback_query->data;
 
 $query_message_id = $update->callback_query->message->message_id;
 
 $query_chat_id = $update->callback_query->message->chat->id;
 
 $query_user = $update->callback_query->message->chat->username;
 
 $query_name = $update->callback_query->message->chat->first_name;
 
 $query_id = $update->callback_query->id;
 
function bot($method, $parameters) {

 $token = "Your Token";
 
 $options = array(
			 'http' => array(
			 'method'  => 'POST',
			 'content' => json_encode($parameters),
			 'header'=>  "Content-Type: application/json\r\n" .
	            "Accept: application/json\r\n"
			 )
			);
$context  = stream_context_create( $options );
		return file_get_contents('https://api.telegram.org/bot'.$token.'/'.$method, false, $context );
}

/*
* Let's Check if a specific text was sent by the user
*/

if (strpos($text, "/start") === 0){

/*
* Use Markdown on Text or change to html in parse_mode
*/

  $txt = "Hello *".$name."*";
  
  $button[] = ['text'=>"EditMessage",'callback_data'=>"editMessage 0"];
  
  $button[] = ['text'=>"",'callback_data'=>"NULL"];
        
  $button[] = ['text'=>"Show Alert 1",'callback_data'=>"showAlert 1"];
  
  $button[] = ['text'=>"Show Alert 2",'callback_data'=>"showAlert 2"];
  
  $menu['inline_keyboard'] = array_chunk($button, 2);
  
  bot("sendChatAction", 
    array(
    "chat_id" => $chat_id,
    "action" => "typing"));
    
  bot("sendMessage",
    array(
    "chat_id"=> $chat_id ,
    "text" => $txt,
    "reply_markup" => $menu,
    "reply_to_message_id"=> $message_id,
    "message_id" => $message_id,
    "parse_mode" => 'Markdown'));
    
}

/*
* Checking if an action is heard in the data variable (to find out if a button was clicked.)
*/

if($data){

  $callback = explode(" ", $data)[0];
  
  $dados = array(
   "chat_id" => $query_chat_id,
   "id" => $query_chat_id,
   "name" => $query_name,
   "user" => $query_user,
   "message_id" => $query_message_id,
   "query_message_id" => $query_message_id,
   "query_name" => $query_name,
   "query_id" => $query_id,
   "optional" => explode(" ", $data)[1],
   "query_user" => $query_user
   
   );
  if(function_exists($callback))
  {
    
  $callback($dados);
    
 } else {
    
    bot("answerCallbackQuery",
    array(
    "callback_query_id" => $query_id,
    "text" => "⚠️ | Function under development!",
    "show_alert"=> false,
    "cache_time" => 10));
    
 }
}
function editMessage($dados){

 $chat_id = $dados["chat_id"];
 $message_id = $dados["query_message_id"];
 $count = $dados["optional"] + 1;
 $name = $dados["name"];
 
 $txt = "Hello".$count." *".$name."*";
 
 $button[] = ['text'=>"EditMessage",'callback_data'=>"editMessage ".$count];

 $button[] = ['text'=>"",'callback_data'=>"NULL"];

 $button[] = ['text'=>"Show Alert 1",'callback_data'=>"showAlert 1"];
 
 $button[] = ['text'=>"Show Alert 2",'callback_data'=>"showAlert 2"];
 
 $menu['inline_keyboard'] = array_chunk($button, 2);
 
 bot("editMessageText",
    array(
    "chat_id"=> $chat_id ,
    "text" => $txt,
    "reply_markup" => $menu,
    "reply_to_message_id"=> $message_id,
    "message_id" => $message_id,
    "parse_mode" => 'Markdown'));
}

function showAlert($dados){

 $query_id = $dados["query_id"];
 $type = $dados["optional"];
 
 if($type == 1)
 {
   
 bot("answerCallbackQuery",
    array(
    "callback_query_id" => $query_id,
    "text" => "ShowAlert 1!",
    "show_alert"=> true,
    "cache_time" => 10));
   
 } else {
   
 bot("answerCallbackQuery",
    array(
    "callback_query_id" => $query_id,
    "text" => "ShowAlert 2",
    "show_alert"=> false,
    "cache_time" => 10));
   
 }
}

<?php
namespace Model;

class Telegram {

    public $api_url = "https://api.telegram.org/bot";
    public $api_token = NULL;
    public $raw;
    public $data = array();
    public $update_id = NULL;
    public $chat_id = NULL;
    public $chat_type = NULL;
    public $is_bot = NULL;
    public $first_name = NULL;
    public $last_name = NULL;
    public $username = NULL;
    public $text = NULL;
    public $callback = FALSE;
    public $channel_id = NULL;
    public $photo = FALSE;
    public $audio = FALSE;
    public $video = FALSE;
    public $document = FALSE;
    public $logger = FALSE;
    public $is_chanmember = FALSE;
    public $parse_mode = 'html';
    public $apiaction = 'sendMessage';
    public $inline_buttons = array();
    public $keyboard = array();
    public $inline = FALSE;

    function __construct(){
        $content = file_get_contents("php://input");
        $this->raw = $content;
        $this->data = json_decode($content, TRUE);
        $this->update_id = $this->data['update_id'];
        if(isset($this->data['callback_query'])){
            $this->callback = $this->data['callback_query'];
            $this->data = $this->data['callback_query'];
        }
        if(isset($this->data['inline_query'])){
            $this->inline = $this->data['inline_query'];
        }
        if(isset($this->data['message']['chat']['id'])){
            $this->chat_id = $this->data['message']['chat']['id'];
        }
        if(isset($this->data['message']['chat']['is_bot'])){
            $this->is_bot = $this->data['message']['chat']['is_bot'];
        }
        if(isset($this->data['message']['chat']['first_name'])){
            $this->first_name = $this->data['message']['chat']['first_name'];
        }
        if(isset($this->data['message']['chat']['last_name'])){
            $this->last_name = $this->data['message']['chat']['last_name'];
        }
        if(isset($this->data['message']['chat']['username'])){
            $this->username = $this->data['message']['chat']['username'];
        }
        if(isset($this->data['message']['text'])){
            $this->text = $this->data['message']['text'];
        }
        if(isset($this->data['message']['chat']['type'])){
            $this->chat_type = $this->data['message']['chat']['type'];
        }

    }

    public function settings($set=array()){
        if ( count( $set ) > 0 ) {
            foreach($set as $key => $val){
                $this->$key = $val;
            }
        };
    }

    public function text(){
        return $this->text;
    }

    public function chat_type(){
        return $this->chat_type;
    }

    public function logging()
    {
        $this->logger = TRUE;
        return $this;
    }

    public function inline()
    {
        return $this->inline;
    }

    public function commad($com=''){
        if($com == $this->text){
            return true;
        }else{
            return false;
        }
    }

    public function update_id(){
        return $this->update_id;
    }

    public function chat_id(){
        return $this->chat_id;
    }

    public function is_bot(){
        return $this->is_bot;
    }

    public function first_name(){
        return $this->first_name;
    }

    public function last_name(){
        return $this->last_name;
    }

    public function username(){
        return $this->username;
    }

    public function callback(){
        return $this->callback;
    }

    public function is_chanmember(){
        $chatmem = $this->getChatMember();
        
        if($chatmem['result']['status'] == 'member' || $chatmem['result']['status'] == 'creator'){
            $this->is_chanmember = true;
        }

        return $this->is_chanmember;
    }

    public function set_inlineButtons($data=array())
    {
        $this->inline_buttons = $data;
    }

    public function set_keyboard($data=array())
    {
        $this->keyboard = $data;
    }

    public function sendphoto($file='')
    {
        $this->photo = $file;   
    }

    public function sendAudio($file='')
    {
        $this->audio = $file;   
    }

    public function sendVideo($file='')
    {
        $this->video = $file;   
    }

    public function sendDocument($file='')
    {
        $this->document = $file;   
    }
    
    public function sendChatAction($action, $chat_id=''){
        $chat_id = ($chat_id != '') ? $chat_id : $this->chat_id;
        
        $actions = array(
            'typing',
            'upload_photo',
            'record_video',
            'upload_video',
            'record_audio',
            'upload_audio',
            'upload_document',
            'find_location',
        );
        $this->getTelegramjson('sendChatAction', compact('chat_id', 'action'));
        if (isset($action) && in_array($action, $actions)) {
            $this->getTelegramjson('sendChatAction', compact('chat_id', 'action'));
        }
        return $this;
    }

    public function send_message($message='', $chat_id=''){
        $content = array();
        if($chat_id == ''){
            $chat_id = $this->chat_id;
        }
        $content['chat_id'] = $chat_id;
        $content['disable_web_page_preview'] = true;
        if (is_array($message)) {
            $content['text'] = json_encode($message, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $content['parse_mode'] = $this->parse_mode;
            $result = $this->getTelegramjson($this->apiaction, $content);
        }
        if (!empty($this->keyboard)) {
            foreach ($this->keyboard as $key) {
                $keyb[] = $key;
            }
            $keyboard = $keyb;
        }
        if (isset($this->inline_buttons)) {
            $content['reply_markup']['inline_keyboard'] = $this->inline_buttons;
        }
        if (isset($keyboard)) {
            $content['reply_markup']['keyboard'] = $keyboard;
            $content['reply_markup']['resize_keyboard'] = true;
        }
        if ($this->photo) {
            $this->apiaction = 'sendPhoto';
            $content['photo'] = $this->photo;
            $content['caption'] = $message;
        }else if ($this->audio) {
            $this->apiaction = 'sendAudio';
            $content['audio'] = $this->audio;
            $content['caption'] = $message;
        }else if ($this->video) {
            $this->apiaction = 'sendVideo';
            $content['video'] = $this->video;
            $content['caption'] = $message;
        }else if ($this->document) {
            $this->apiaction = 'sendDocument';
            $content['document'] = $this->document;
            $content['caption'] = $message;
        }else{
            $content['text'] = $message;
        }
        $content['parse_mode'] = $this->parse_mode;
        $result = $this->getTelegramjson($this->apiaction, $content);
        if ($this->photo) {
            $this->apiaction = 'sendMessage';
            $this->photo = FALSE;
        }else if ($this->audio) {
            $this->apiaction = 'sendMessage';
            $this->audio = FALSE;
        }else if ($this->video) {
            $this->apiaction = 'sendMessage';
            $this->video = FALSE;
        }else if ($this->document) {
            $this->document = 'sendMessage';
            $this->video = FALSE;
        }
        return $result;
    }

    public function getChatMember($channel_id='', $chat_id=''){
        $content = array();
        if($chat_id == ''){
            $chat_id = $this->chat_id;
        }
        if($channel_id == ''){
            $channel_id = $this->channel_id;
        }
        $content['chat_id'] = $channel_id;
        $content['user_id'] = $chat_id;
        $res = $this->getTelegramjson("getChatMember", $content);
        return $res;
    }

    public function get_chatAdministrators($chat_id = null)
    {
        $chat_id = ( !is_null($chat_id) ) ? $chat_id : $this->chat_id;
        return $this->getTelegramjson("getChatAdministrators", compact('chat_id'));
    }

    public function getTelegramjson($action, $content)
    {
        $url = $this->api_url . $this->api_token . '/' . $action;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($content));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close($ch);
        if($this->logger){
            $this->log($result);
        }
        return json_decode($result, TRUE);
    }

    public function log($texto, $json=false){
        if(is_array($texto)) {
            if ($json) {
                $texto =  json_encode($texto, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }else{
                $texto = print_r($texto, TRUE);    
            }
        }
        $fp = fopen('/tmp/error.log', 'a');
        fwrite($fp, $texto ."\n");
        fclose($fp);
    }
}
?>
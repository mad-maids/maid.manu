<?php

namespace Controller;
use Model;

class Telegram
{
	public function index()
	{
		$tg = new Model\Telegram();
		
		$tg->settings([
			'api_token' => config_item('telegram_bot_token'),
			'parse_mode' => 'html' 
		]);
		if( $tg->inline() ){

        	$inline = $tg->inline();
        	$content['inline_query_id'] = $inline['id'];
        	$results = self::inline_search( $inline['query'] );
        	$content['results'] =  $results;
        	$content['cache_time'] = '1';
        	$res = $tg->getTelegramjson('answerInlineQuery', $content);
    	}
		
		if($tg->text() != null){

			if( preg_match('/^\/start(@.+)?$/', $tg->text() ) ){
				$tg->sendChatAction('typing');
				$tg->send_message('👋 Assalomu alaykum '.(!empty($tg->first_name) ? $tg->first_name : 'aziz foydalanuvchi').', '.config_item('telegram_bot_username').' ga xush kelibsiz');
			}else if( preg_match('/^\/search(@.+)?$/', $tg->text() ) ){
				$tg->sendChatAction('typing');
				$tg->send_message('Kerakli blogpostni izlash uchun robotga kalit so\'zni yuboring. Yoki xabar yozish maydoniga '.config_item('telegram_bot_username'). ' matnidan so\'ng izlash matnini kiritib blogpostlarni do\'stlaringiz bilan ulashing.');
			}else if(  preg_match('/^\/contact(@.+)?$/', $tg->text() )  ){
				$tg->sendChatAction('typing');
				$tg->send_message('Biz bilan ' . base_url('/contact.htm') . ' sahifasi orqali bog\'lanishingiz mumkin.');
			}else if(  preg_match('/^\/about(@.+)?$/', $tg->text() )  ){
				$tg->sendChatAction('typing');
				$tg->send_message('Biz haqimizda barafsiz ' . base_url('/about.htm') . ' sahifasi orqali ma\'lumot olishingiz mumkin.');
			}else{
				$tg->sendChatAction('typing');
				$items = search( $tg->text() );
				$message = "Balki sizlar quyidagilar qiziq bo'lar 🧐".PHP_EOL.PHP_EOL;
				if ($items) {
					$items = array_slice($items, 0, 10);
					$numItems = count($items);
					$x = 0;
					foreach ($items as $item) {
						$message .= "<a href=\"".base_url($item['url'])."\">{$item['title']}</a> ({$item['c']})";
						if(++$i != $numItems) {
    						$message .= PHP_EOL.str_repeat('-', 15).PHP_EOL;
  						}
					}
				}else{
					$message = "Men sizni tushuna olmadim 🤷‍♂️";	
				}
				$tg->send_message(  $message );
			}
		}
	}

	private function inline_search($q)
	{
		$res = [];
		$items = search($q);
		if($items){
			$parser = new Model\Md();
			foreach ($items as $item) {
				$md = file_get_contents( config_item('docs_path').ltrim( str_replace(['.html', '.htm'], '.md', $item['url']) , '/') );
				$content = (array)$parser->get( $md );
				$item['description'] = $content['yaml']['description'];
				$res[] = self::to_print( $item );
			}

			return $res;
		}

		return FALSE;
	}

	private function to_print( $item )
	{
		$keyboard = [
    		"inline_keyboard" => [
    			[
    				[
    					"text" => "👉 batafsil", "url" => base_url($item['url'])
    				]
    			]
    		]
		];
		$message = $item['title'];
		if (!strcmp($item['title'], $item['description'])) {
			$message .= PHP_EOL.str_repeat('-', 15).PHP_EOL;
			$message .= html_entity_decode($item['description']);
		}
		$message .= PHP_EOL.str_repeat('-', 15).PHP_EOL;
		$message .= base_url($item['url']);
		$res = [
			'type' => 'article',
			'id' => md5( $item['url'] ),
			'reply_markup' => $keyboard,
			'title' => $item['title'],
			'url' => base_url($item['url']),
			'parse_mode' => 'html',
			'message_text' => $message,
			'description' => html_entity_decode($item['description']),
			'disable_web_page_preview' => true
		];

		return $res;
	}
}
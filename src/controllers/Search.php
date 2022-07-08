<?php

namespace Controller;
use Model;

class Search
{
	
	public function index()
	{
		$data['html'] = $res['html'];
		$data['headline'] = "Izlash";
		$data['backButton'] =  '/';
		$data['term'] = xss_clean($_GET['term']);
		if ( isset( $_GET['term'] ) ) {
			if (strlen($_GET['term']) > 2) {
				$items = search($_GET['term']);
				if ($items) {
					$paging = new Model\Paging([
						'data' => $items,
						'limit' => 20,
						'base_url' => base_url($_SERVER['REQUEST_URI'])
					]);
					$paging->setPage( (isset($_GET['page']) ? $_GET['page'] : 1) );
					$data['items'] = build_menu( $paging->getData());
					$data['paging'] = (count($items) > 20) ? $paging->getLinks() : '';
				}else{
					$data['no_items'] = TRUE;
				}
			}else{
				$data['to_short'] = 2;
			}
		}
		Model\View::render( 'search', $data, "Izlash");
	}
}

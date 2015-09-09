<?php

App::uses('SitemapsController', 'Sitemap.Controller');
App::uses('SitemapAppController', 'Sitemap.Controller');
App::uses('AppController', 'Controller');

class BvwSitemapsController extends SitemapsController {

	public $uses = array('Page');

	public function display() {
		parent::display();
	}

	protected function _generateListOfStaticPages() {
		$sitemapPage = Cache::read('Sitemap.Page');
		if ($sitemapPage !== false) {
			return $sitemapPage;
		}

		$pages = $this->Page->find('list', array(
			'conditions' => array(
				'active' => 1,
			),
			'fields' => array(
				'id', 'slug',
			)
		));

		$pages[] = 'orders';
		$pages[] = 'contact-us';

		$return = array();
		foreach ($pages as $key => $slug) {
			$return[$key]['loc'] = DS.$slug;
			$return[$key]['changefreq'] = 'daily';
			$return[$key]['priority'] = '1.0';
		}

		Cache::write('Sitemap.Page', $return);

		return $return;
	}
}

<?php

namespace libs;

use model\ImageManager;

class URLgen {

	var $urlPrefix;

	public function __construct($prefix) {
		$this->urlPrefix = $prefix;
	}

	public function url($params) {
		$return = $this->urlPrefix;
		if (!$params) {
			return $return;
		}
		$first = true;
		foreach ($params as $parKey => $parVal) {
			$return.=($first ? "?" : "&") . "$parKey=$parVal";
			$first = false;
		}
		return $return;
	}

	public function aUrl($action) {
		return $this->url(['action' => $action]);
	}

	public function css($file) {
		return $this->urlPrefix . "css/" . $file;
	}

	public function js($file) {
		return $this->urlPrefix . "js/" . $file;
	}

	public function img($file) {
		$p = $this->urlPrefix . 'img/' .ImageManager::get($file);
		return $p;
	}
}

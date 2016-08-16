<?php

namespace Documentor\src\Application\Views;

class CoverageView extends BaseView
{
	private $toc = [];

	public function setTableOfContents(array $toc)
	{
		$this->toc = $toc;
	}

	public function getTableOfContents(array $toc = null)
	{
		$toc = $toc ?? $this->toc;
		$txt = '<ul>';

		foreach($toc as $method) {
			$txt .= is_array($method) ? $this->getTableOfContents($method) : '<li>' . $method;
		}

		$txt .= '</ul>';

		return $txt;
	}
}
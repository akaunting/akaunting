<?php

namespace Sabberworm\CSS;

interface Renderable {
	public function __toString();
	public function render(\Sabberworm\CSS\OutputFormat $oOutputFormat);
	public function getLineNo();
}
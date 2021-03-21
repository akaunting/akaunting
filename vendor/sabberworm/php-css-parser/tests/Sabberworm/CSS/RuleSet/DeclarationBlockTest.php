<?php

namespace Sabberworm\CSS\RuleSet;

use Sabberworm\CSS\Parser;
use Sabberworm\CSS\Rule\Rule;
use Sabberworm\CSS\Value\Size;

class DeclarationBlockTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @dataProvider expandBorderShorthandProvider
	 * */
	public function testExpandBorderShorthand($sCss, $sExpected) {
		$oParser = new Parser($sCss);
		$oDoc = $oParser->parse();
		foreach ($oDoc->getAllDeclarationBlocks() as $oDeclaration) {
			$oDeclaration->expandBorderShorthand();
		}
		$this->assertSame(trim((string) $oDoc), $sExpected);
	}

	public function expandBorderShorthandProvider() {
		return array(
			array('body{ border: 2px solid #000 }', 'body {border-width: 2px;border-style: solid;border-color: #000;}'),
			array('body{ border: none }', 'body {border-style: none;}'),
			array('body{ border: 2px }', 'body {border-width: 2px;}'),
			array('body{ border: #f00 }', 'body {border-color: #f00;}'),
			array('body{ border: 1em solid }', 'body {border-width: 1em;border-style: solid;}'),
			array('body{ margin: 1em; }', 'body {margin: 1em;}')
		);
	}

	/**
	 * @dataProvider expandFontShorthandProvider
	 * */
	public function testExpandFontShorthand($sCss, $sExpected) {
		$oParser = new Parser($sCss);
		$oDoc = $oParser->parse();
		foreach ($oDoc->getAllDeclarationBlocks() as $oDeclaration) {
			$oDeclaration->expandFontShorthand();
		}
		$this->assertSame(trim((string) $oDoc), $sExpected);
	}

	public function expandFontShorthandProvider() {
		return array(
			array(
				'body{ margin: 1em; }',
				'body {margin: 1em;}'
			),
			array(
				'body {font: 12px serif;}',
				'body {font-style: normal;font-variant: normal;font-weight: normal;font-size: 12px;line-height: normal;font-family: serif;}'
			),
			array(
				'body {font: italic 12px serif;}',
				'body {font-style: italic;font-variant: normal;font-weight: normal;font-size: 12px;line-height: normal;font-family: serif;}'
			),
			array(
				'body {font: italic bold 12px serif;}',
				'body {font-style: italic;font-variant: normal;font-weight: bold;font-size: 12px;line-height: normal;font-family: serif;}'
			),
			array(
				'body {font: italic bold 12px/1.6 serif;}',
				'body {font-style: italic;font-variant: normal;font-weight: bold;font-size: 12px;line-height: 1.6;font-family: serif;}'
			),
			array(
				'body {font: italic small-caps bold 12px/1.6 serif;}',
				'body {font-style: italic;font-variant: small-caps;font-weight: bold;font-size: 12px;line-height: 1.6;font-family: serif;}'
			),
		);
	}

	/**
	 * @dataProvider expandBackgroundShorthandProvider
	 * */
	public function testExpandBackgroundShorthand($sCss, $sExpected) {
		$oParser = new Parser($sCss);
		$oDoc = $oParser->parse();
		foreach ($oDoc->getAllDeclarationBlocks() as $oDeclaration) {
			$oDeclaration->expandBackgroundShorthand();
		}
		$this->assertSame(trim((string) $oDoc), $sExpected);
	}

	public function expandBackgroundShorthandProvider() {
		return array(
			array('body {border: 1px;}', 'body {border: 1px;}'),
			array('body {background: #f00;}', 'body {background-color: #f00;background-image: none;background-repeat: repeat;background-attachment: scroll;background-position: 0% 0%;}'),
			array('body {background: #f00 url("foobar.png");}', 'body {background-color: #f00;background-image: url("foobar.png");background-repeat: repeat;background-attachment: scroll;background-position: 0% 0%;}'),
			array('body {background: #f00 url("foobar.png") no-repeat;}', 'body {background-color: #f00;background-image: url("foobar.png");background-repeat: no-repeat;background-attachment: scroll;background-position: 0% 0%;}'),
			array('body {background: #f00 url("foobar.png") no-repeat center;}', 'body {background-color: #f00;background-image: url("foobar.png");background-repeat: no-repeat;background-attachment: scroll;background-position: center center;}'),
			array('body {background: #f00 url("foobar.png") no-repeat top left;}', 'body {background-color: #f00;background-image: url("foobar.png");background-repeat: no-repeat;background-attachment: scroll;background-position: top left;}'),
		);
	}

	/**
	 * @dataProvider expandDimensionsShorthandProvider
	 * */
	public function testExpandDimensionsShorthand($sCss, $sExpected) {
		$oParser = new Parser($sCss);
		$oDoc = $oParser->parse();
		foreach ($oDoc->getAllDeclarationBlocks() as $oDeclaration) {
			$oDeclaration->expandDimensionsShorthand();
		}
		$this->assertSame(trim((string) $oDoc), $sExpected);
	}

	public function expandDimensionsShorthandProvider() {
		return array(
			array('body {border: 1px;}', 'body {border: 1px;}'),
			array('body {margin-top: 1px;}', 'body {margin-top: 1px;}'),
			array('body {margin: 1em;}', 'body {margin-top: 1em;margin-right: 1em;margin-bottom: 1em;margin-left: 1em;}'),
			array('body {margin: 1em 2em;}', 'body {margin-top: 1em;margin-right: 2em;margin-bottom: 1em;margin-left: 2em;}'),
			array('body {margin: 1em 2em 3em;}', 'body {margin-top: 1em;margin-right: 2em;margin-bottom: 3em;margin-left: 2em;}'),
		);
	}

	/**
	 * @dataProvider createBorderShorthandProvider
	 * */
	public function testCreateBorderShorthand($sCss, $sExpected) {
		$oParser = new Parser($sCss);
		$oDoc = $oParser->parse();
		foreach ($oDoc->getAllDeclarationBlocks() as $oDeclaration) {
			$oDeclaration->createBorderShorthand();
		}
		$this->assertSame(trim((string) $oDoc), $sExpected);
	}

	public function createBorderShorthandProvider() {
		return array(
			array('body {border-width: 2px;border-style: solid;border-color: #000;}', 'body {border: 2px solid #000;}'),
			array('body {border-style: none;}', 'body {border: none;}'),
			array('body {border-width: 1em;border-style: solid;}', 'body {border: 1em solid;}'),
			array('body {margin: 1em;}', 'body {margin: 1em;}')
		);
	}

	/**
	 * @dataProvider createFontShorthandProvider
	 * */
	public function testCreateFontShorthand($sCss, $sExpected) {
		$oParser = new Parser($sCss);
		$oDoc = $oParser->parse();
		foreach ($oDoc->getAllDeclarationBlocks() as $oDeclaration) {
			$oDeclaration->createFontShorthand();
		}
		$this->assertSame(trim((string) $oDoc), $sExpected);
	}

	public function createFontShorthandProvider() {
		return array(
			array('body {font-size: 12px; font-family: serif}', 'body {font: 12px serif;}'),
			array('body {font-size: 12px; font-family: serif; font-style: italic;}', 'body {font: italic 12px serif;}'),
			array('body {font-size: 12px; font-family: serif; font-style: italic; font-weight: bold;}', 'body {font: italic bold 12px serif;}'),
			array('body {font-size: 12px; font-family: serif; font-style: italic; font-weight: bold; line-height: 1.6;}', 'body {font: italic bold 12px/1.6 serif;}'),
			array('body {font-size: 12px; font-family: serif; font-style: italic; font-weight: bold; line-height: 1.6; font-variant: small-caps;}', 'body {font: italic small-caps bold 12px/1.6 serif;}'),
			array('body {margin: 1em;}', 'body {margin: 1em;}')
		);
	}

	/**
	 * @dataProvider createDimensionsShorthandProvider
	 * */
	public function testCreateDimensionsShorthand($sCss, $sExpected) {
		$oParser = new Parser($sCss);
		$oDoc = $oParser->parse();
		foreach ($oDoc->getAllDeclarationBlocks() as $oDeclaration) {
			$oDeclaration->createDimensionsShorthand();
		}
		$this->assertSame(trim((string) $oDoc), $sExpected);
	}

	public function createDimensionsShorthandProvider() {
		return array(
			array('body {border: 1px;}', 'body {border: 1px;}'),
			array('body {margin-top: 1px;}', 'body {margin-top: 1px;}'),
			array('body {margin-top: 1em; margin-right: 1em; margin-bottom: 1em; margin-left: 1em;}', 'body {margin: 1em;}'),
			array('body {margin-top: 1em; margin-right: 2em; margin-bottom: 1em; margin-left: 2em;}', 'body {margin: 1em 2em;}'),
			array('body {margin-top: 1em; margin-right: 2em; margin-bottom: 3em; margin-left: 2em;}', 'body {margin: 1em 2em 3em;}'),
		);
	}

	/**
	 * @dataProvider createBackgroundShorthandProvider
	 * */
	public function testCreateBackgroundShorthand($sCss, $sExpected) {
		$oParser = new Parser($sCss);
		$oDoc = $oParser->parse();
		foreach ($oDoc->getAllDeclarationBlocks() as $oDeclaration) {
			$oDeclaration->createBackgroundShorthand();
		}
		$this->assertSame(trim((string) $oDoc), $sExpected);
	}

	public function createBackgroundShorthandProvider() {
		return array(
			array('body {border: 1px;}', 'body {border: 1px;}'),
			array('body {background-color: #f00;}', 'body {background: #f00;}'),
			array('body {background-color: #f00;background-image: url(foobar.png);}', 'body {background: #f00 url("foobar.png");}'),
			array('body {background-color: #f00;background-image: url(foobar.png);background-repeat: no-repeat;}', 'body {background: #f00 url("foobar.png") no-repeat;}'),
			array('body {background-color: #f00;background-image: url(foobar.png);background-repeat: no-repeat;}', 'body {background: #f00 url("foobar.png") no-repeat;}'),
			array('body {background-color: #f00;background-image: url(foobar.png);background-repeat: no-repeat;background-position: center;}', 'body {background: #f00 url("foobar.png") no-repeat center;}'),
			array('body {background-color: #f00;background-image: url(foobar.png);background-repeat: no-repeat;background-position: top left;}', 'body {background: #f00 url("foobar.png") no-repeat top left;}'),
		);
	}

	public function testOverrideRules() {
		$sCss = '.wrapper { left: 10px; text-align: left; }';
		$oParser = new Parser($sCss);
		$oDoc = $oParser->parse();
		$oRule = new Rule('right');
		$oRule->setValue('-10px');
		$aContents = $oDoc->getContents();
		$oWrapper = $aContents[0];

		$this->assertCount(2, $oWrapper->getRules());
		$aContents[0]->setRules(array($oRule));

		$aRules = $oWrapper->getRules();
		$this->assertCount(1, $aRules);
		$this->assertEquals('right', $aRules[0]->getRule());
		$this->assertEquals('-10px', $aRules[0]->getValue());
	}
	
	public function testRuleInsertion() {
		$sCss = '.wrapper { left: 10px; text-align: left; }';
		$oParser = new Parser($sCss);
		$oDoc = $oParser->parse();
		$aContents = $oDoc->getContents();
		$oWrapper = $aContents[0];

		$oFirst = $oWrapper->getRules('left');
		$this->assertCount(1, $oFirst);
		$oFirst = $oFirst[0];

		$oSecond = $oWrapper->getRules('text-');
		$this->assertCount(1, $oSecond);
		$oSecond = $oSecond[0];

		$oBefore = new Rule('left');
		$oBefore->setValue(new Size(16, 'em'));

		$oMiddle = new Rule('text-align');
		$oMiddle->setValue(new Size(1));

		$oAfter = new Rule('border-bottom-width');
		$oAfter->setValue(new Size(1, 'px'));

		$oWrapper->addRule($oAfter);
		$oWrapper->addRule($oBefore, $oFirst);
		$oWrapper->addRule($oMiddle, $oSecond);

		$aRules = $oWrapper->getRules();

		$this->assertSame($oBefore, $aRules[0]);
		$this->assertSame($oFirst, $aRules[1]);
		$this->assertSame($oMiddle, $aRules[2]);
		$this->assertSame($oSecond, $aRules[3]);
		$this->assertSame($oAfter, $aRules[4]);

		$this->assertSame('.wrapper {left: 16em;left: 10px;text-align: 1;text-align: left;border-bottom-width: 1px;}', $oDoc->render());
	}

}

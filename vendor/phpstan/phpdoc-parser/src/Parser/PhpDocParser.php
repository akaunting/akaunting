<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Parser;

use PHPStan\PhpDocParser\Ast;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Lexer\Lexer;

class PhpDocParser
{

	private const DISALLOWED_DESCRIPTION_START_TOKENS = [
		Lexer::TOKEN_UNION,
		Lexer::TOKEN_INTERSECTION,
	];

	/** @var TypeParser */
	private $typeParser;

	/** @var ConstExprParser */
	private $constantExprParser;

	public function __construct(TypeParser $typeParser, ConstExprParser $constantExprParser)
	{
		$this->typeParser = $typeParser;
		$this->constantExprParser = $constantExprParser;
	}


	public function parse(TokenIterator $tokens): Ast\PhpDoc\PhpDocNode
	{
		$tokens->consumeTokenType(Lexer::TOKEN_OPEN_PHPDOC);
		$tokens->tryConsumeTokenType(Lexer::TOKEN_PHPDOC_EOL);

		$children = [];

		if (!$tokens->isCurrentTokenType(Lexer::TOKEN_CLOSE_PHPDOC)) {
			$children[] = $this->parseChild($tokens);
			while ($tokens->tryConsumeTokenType(Lexer::TOKEN_PHPDOC_EOL) && !$tokens->isCurrentTokenType(Lexer::TOKEN_CLOSE_PHPDOC)) {
				$children[] = $this->parseChild($tokens);
			}
		}

		$tokens->consumeTokenType(Lexer::TOKEN_CLOSE_PHPDOC);

		return new Ast\PhpDoc\PhpDocNode(array_values($children));
	}


	private function parseChild(TokenIterator $tokens): Ast\PhpDoc\PhpDocChildNode
	{
		if ($tokens->isCurrentTokenType(Lexer::TOKEN_PHPDOC_TAG)) {
			return $this->parseTag($tokens);

		}

		return $this->parseText($tokens);
	}


	private function parseText(TokenIterator $tokens): Ast\PhpDoc\PhpDocTextNode
	{
		$text = '';
		while (true) {
			// If we received a Lexer::TOKEN_PHPDOC_EOL, exit early to prevent
			// them from being processed.
			$currentTokenType = $tokens->currentTokenType();
			if ($currentTokenType === Lexer::TOKEN_PHPDOC_EOL) {
				break;
			}
			$text .= $tokens->joinUntil(Lexer::TOKEN_PHPDOC_EOL, Lexer::TOKEN_CLOSE_PHPDOC, Lexer::TOKEN_END);
			$text = rtrim($text, " \t");

			// If we joined until TOKEN_PHPDOC_EOL, peak at the next tokens to see
			// if we have a multiline string to join.
			$currentTokenType = $tokens->currentTokenType();
			if ($currentTokenType !== Lexer::TOKEN_PHPDOC_EOL) {
				break;
			}

			// Peek at the next token to determine if it is more text that needs
			// to be combined.
			$tokens->pushSavePoint();
			$tokens->next();
			$currentTokenType = $tokens->currentTokenType();
			if ($currentTokenType !== Lexer::TOKEN_IDENTIFIER) {
				$tokens->rollback();
				break;
			}

			// There's more text on a new line, ensure spacing.
			$text .= "\n";
		}
		$text = trim($text, " \t");

		return new Ast\PhpDoc\PhpDocTextNode($text);
	}


	public function parseTag(TokenIterator $tokens): Ast\PhpDoc\PhpDocTagNode
	{
		$tag = $tokens->currentTokenValue();
		$tokens->next();
		$value = $this->parseTagValue($tokens, $tag);

		return new Ast\PhpDoc\PhpDocTagNode($tag, $value);
	}


	public function parseTagValue(TokenIterator $tokens, string $tag): Ast\PhpDoc\PhpDocTagValueNode
	{
		try {
			$tokens->pushSavePoint();

			switch ($tag) {
				case '@param':
				case '@phpstan-param':
				case '@psalm-param':
					$tagValue = $this->parseParamTagValue($tokens);
					break;

				case '@var':
				case '@phpstan-var':
				case '@psalm-var':
					$tagValue = $this->parseVarTagValue($tokens);
					break;

				case '@return':
				case '@phpstan-return':
				case '@psalm-return':
					$tagValue = $this->parseReturnTagValue($tokens);
					break;

				case '@throws':
				case '@phpstan-throws':
					$tagValue = $this->parseThrowsTagValue($tokens);
					break;

				case '@mixin':
					$tagValue = $this->parseMixinTagValue($tokens);
					break;

				case '@deprecated':
					$tagValue = $this->parseDeprecatedTagValue($tokens);
					break;

				case '@property':
				case '@property-read':
				case '@property-write':
				case '@phpstan-property':
				case '@phpstan-property-read':
				case '@phpstan-property-write':
				case '@psalm-property':
				case '@psalm-property-read':
				case '@psalm-property-write':
					$tagValue = $this->parsePropertyTagValue($tokens);
					break;

				case '@method':
				case '@phpstan-method':
				case '@psalm-method':
					$tagValue = $this->parseMethodTagValue($tokens);
					break;

				case '@template':
				case '@phpstan-template':
				case '@psalm-template':
				case '@template-covariant':
				case '@phpstan-template-covariant':
				case '@psalm-template-covariant':
					$tagValue = $this->parseTemplateTagValue($tokens);
					break;

				case '@extends':
				case '@phpstan-extends':
				case '@template-extends':
					$tagValue = $this->parseExtendsTagValue('@extends', $tokens);
					break;

				case '@implements':
				case '@phpstan-implements':
				case '@template-implements':
					$tagValue = $this->parseExtendsTagValue('@implements', $tokens);
					break;

				case '@use':
				case '@phpstan-use':
				case '@template-use':
					$tagValue = $this->parseExtendsTagValue('@use', $tokens);
					break;

				default:
					$tagValue = new Ast\PhpDoc\GenericTagValueNode($this->parseOptionalDescription($tokens));
					break;
			}

			$tokens->dropSavePoint();

		} catch (\PHPStan\PhpDocParser\Parser\ParserException $e) {
			$tokens->rollback();
			$tagValue = new Ast\PhpDoc\InvalidTagValueNode($this->parseOptionalDescription($tokens), $e);
		}

		return $tagValue;
	}


	private function parseParamTagValue(TokenIterator $tokens): Ast\PhpDoc\ParamTagValueNode
	{
		$type = $this->typeParser->parse($tokens);
		$isVariadic = $tokens->tryConsumeTokenType(Lexer::TOKEN_VARIADIC);
		$parameterName = $this->parseRequiredVariableName($tokens);
		$description = $this->parseOptionalDescription($tokens);
		return new Ast\PhpDoc\ParamTagValueNode($type, $isVariadic, $parameterName, $description);
	}


	private function parseVarTagValue(TokenIterator $tokens): Ast\PhpDoc\VarTagValueNode
	{
		$type = $this->typeParser->parse($tokens);
		$variableName = $this->parseOptionalVariableName($tokens);
		$description = $this->parseOptionalDescription($tokens, $variableName === '');
		return new Ast\PhpDoc\VarTagValueNode($type, $variableName, $description);
	}


	private function parseReturnTagValue(TokenIterator $tokens): Ast\PhpDoc\ReturnTagValueNode
	{
		$type = $this->typeParser->parse($tokens);
		$description = $this->parseOptionalDescription($tokens, true);
		return new Ast\PhpDoc\ReturnTagValueNode($type, $description);
	}


	private function parseThrowsTagValue(TokenIterator $tokens): Ast\PhpDoc\ThrowsTagValueNode
	{
		$type = $this->typeParser->parse($tokens);
		$description = $this->parseOptionalDescription($tokens, true);
		return new Ast\PhpDoc\ThrowsTagValueNode($type, $description);
	}

	private function parseMixinTagValue(TokenIterator $tokens): Ast\PhpDoc\MixinTagValueNode
	{
		$type = $this->typeParser->parse($tokens);
		$description = $this->parseOptionalDescription($tokens, true);
		return new Ast\PhpDoc\MixinTagValueNode($type, $description);
	}

	private function parseDeprecatedTagValue(TokenIterator $tokens): Ast\PhpDoc\DeprecatedTagValueNode
	{
		$description = $this->parseOptionalDescription($tokens);
		return new Ast\PhpDoc\DeprecatedTagValueNode($description);
	}


	private function parsePropertyTagValue(TokenIterator $tokens): Ast\PhpDoc\PropertyTagValueNode
	{
		$type = $this->typeParser->parse($tokens);
		$parameterName = $this->parseRequiredVariableName($tokens);
		$description = $this->parseOptionalDescription($tokens);
		return new Ast\PhpDoc\PropertyTagValueNode($type, $parameterName, $description);
	}


	private function parseMethodTagValue(TokenIterator $tokens): Ast\PhpDoc\MethodTagValueNode
	{
		$isStatic = $tokens->tryConsumeTokenValue('static');
		$returnTypeOrMethodName = $this->typeParser->parse($tokens);

		if ($tokens->isCurrentTokenType(Lexer::TOKEN_IDENTIFIER)) {
			$returnType = $returnTypeOrMethodName;
			$methodName = $tokens->currentTokenValue();
			$tokens->next();

		} elseif ($returnTypeOrMethodName instanceof Ast\Type\IdentifierTypeNode) {
			$returnType = $isStatic ? new Ast\Type\IdentifierTypeNode('static') : null;
			$methodName = $returnTypeOrMethodName->name;
			$isStatic = false;

		} else {
			$tokens->consumeTokenType(Lexer::TOKEN_IDENTIFIER); // will throw exception
			exit;
		}

		$parameters = [];
		$tokens->consumeTokenType(Lexer::TOKEN_OPEN_PARENTHESES);
		if (!$tokens->isCurrentTokenType(Lexer::TOKEN_CLOSE_PARENTHESES)) {
			$parameters[] = $this->parseMethodTagValueParameter($tokens);
			while ($tokens->tryConsumeTokenType(Lexer::TOKEN_COMMA)) {
				$parameters[] = $this->parseMethodTagValueParameter($tokens);
			}
		}
		$tokens->consumeTokenType(Lexer::TOKEN_CLOSE_PARENTHESES);

		$description = $this->parseOptionalDescription($tokens);
		return new Ast\PhpDoc\MethodTagValueNode($isStatic, $returnType, $methodName, $parameters, $description);
	}


	private function parseMethodTagValueParameter(TokenIterator $tokens): Ast\PhpDoc\MethodTagValueParameterNode
	{
		switch ($tokens->currentTokenType()) {
			case Lexer::TOKEN_IDENTIFIER:
			case Lexer::TOKEN_OPEN_PARENTHESES:
			case Lexer::TOKEN_NULLABLE:
				$parameterType = $this->typeParser->parse($tokens);
				break;

			default:
				$parameterType = null;
		}

		$isReference = $tokens->tryConsumeTokenType(Lexer::TOKEN_REFERENCE);
		$isVariadic = $tokens->tryConsumeTokenType(Lexer::TOKEN_VARIADIC);

		$parameterName = $tokens->currentTokenValue();
		$tokens->consumeTokenType(Lexer::TOKEN_VARIABLE);

		if ($tokens->tryConsumeTokenType(Lexer::TOKEN_EQUAL)) {
			$defaultValue = $this->constantExprParser->parse($tokens);

		} else {
			$defaultValue = null;
		}

		return new Ast\PhpDoc\MethodTagValueParameterNode($parameterType, $isReference, $isVariadic, $parameterName, $defaultValue);
	}

	private function parseTemplateTagValue(TokenIterator $tokens): Ast\PhpDoc\TemplateTagValueNode
	{
		$name = $tokens->currentTokenValue();
		$tokens->consumeTokenType(Lexer::TOKEN_IDENTIFIER);

		if ($tokens->tryConsumeTokenValue('of') || $tokens->tryConsumeTokenValue('as')) {
			$bound = $this->typeParser->parse($tokens);

		} else {
			$bound = null;
		}

		$description = $this->parseOptionalDescription($tokens);

		return new Ast\PhpDoc\TemplateTagValueNode($name, $bound, $description);
	}

	private function parseExtendsTagValue(string $tagName, TokenIterator $tokens): Ast\PhpDoc\PhpDocTagValueNode
	{
		$baseType = new IdentifierTypeNode($tokens->currentTokenValue());
		$tokens->consumeTokenType(Lexer::TOKEN_IDENTIFIER);

		$type = $this->typeParser->parseGeneric($tokens, $baseType);

		$description = $this->parseOptionalDescription($tokens);

		switch ($tagName) {
			case '@extends':
				return new Ast\PhpDoc\ExtendsTagValueNode($type, $description);
			case '@implements':
				return new Ast\PhpDoc\ImplementsTagValueNode($type, $description);
			case '@use':
				return new Ast\PhpDoc\UsesTagValueNode($type, $description);
		}

		throw new \PHPStan\ShouldNotHappenException();
	}

	private function parseOptionalVariableName(TokenIterator $tokens): string
	{
		if ($tokens->isCurrentTokenType(Lexer::TOKEN_VARIABLE)) {
			$parameterName = $tokens->currentTokenValue();
			$tokens->next();
		} elseif ($tokens->isCurrentTokenType(Lexer::TOKEN_THIS_VARIABLE)) {
			$parameterName = '$this';
			$tokens->next();

		} else {
			$parameterName = '';
		}

		return $parameterName;
	}


	private function parseRequiredVariableName(TokenIterator $tokens): string
	{
		$parameterName = $tokens->currentTokenValue();
		$tokens->consumeTokenType(Lexer::TOKEN_VARIABLE);

		return $parameterName;
	}


	private function parseOptionalDescription(TokenIterator $tokens, bool $limitStartToken = false): string
	{
		if ($limitStartToken) {
			foreach (self::DISALLOWED_DESCRIPTION_START_TOKENS as $disallowedStartToken) {
				if (!$tokens->isCurrentTokenType($disallowedStartToken)) {
					continue;
				}

				$tokens->consumeTokenType(Lexer::TOKEN_OTHER); // will throw exception
			}
		}

		return $this->parseText($tokens)->text;
	}

}

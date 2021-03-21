<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\TypeHints;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\SniffSettingsHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function sprintf;
use function str_repeat;
use function strlen;
use function substr;
use function substr_count;
use const T_DECLARE;
use const T_LNUMBER;
use const T_OPEN_TAG;
use const T_STRING;
use const T_WHITESPACE;

class DeclareStrictTypesSniff implements Sniff
{

	public const CODE_DECLARE_STRICT_TYPES_MISSING = 'DeclareStrictTypesMissing';

	public const CODE_INCORRECT_STRICT_TYPES_FORMAT = 'IncorrectStrictTypesFormat';

	public const CODE_INCORRECT_WHITESPACE_BETWEEN_OPEN_TAG_AND_DECLARE = 'IncorrectWhitespaceBetweenOpenTagAndDeclare';

	public const CODE_INCORRECT_WHITESPACE_AFTER_DECLARE = 'IncorrectWhitespaceAfterDeclare';

	/** @var int */
	public $newlinesCountBetweenOpenTagAndDeclare = 0;

	/** @var int */
	public $newlinesCountAfterDeclare = 2;

	/** @var int */
	public $spacesCountAroundEqualsSign = 1;

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_OPEN_TAG,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $openTagPointer
	 */
	public function process(File $phpcsFile, $openTagPointer): void
	{
		if (TokenHelper::findPrevious($phpcsFile, T_OPEN_TAG, $openTagPointer - 1) !== null) {
			return;
		}

		$tokens = $phpcsFile->getTokens();
		$declarePointer = TokenHelper::findNextEffective($phpcsFile, $openTagPointer + 1);

		if ($declarePointer === null || $tokens[$declarePointer]['code'] !== T_DECLARE) {
			$fix = $phpcsFile->addFixableError(
				sprintf('Missing declare(%s).', $this->getStrictTypeDeclaration()),
				$openTagPointer,
				self::CODE_DECLARE_STRICT_TYPES_MISSING
			);
			if ($fix) {
				$phpcsFile->fixer->beginChangeset();
				$phpcsFile->fixer->addContent(
					$openTagPointer,
					sprintf('declare(%s);%s', $this->getStrictTypeDeclaration(), $phpcsFile->eolChar)
				);
				$phpcsFile->fixer->endChangeset();
			}
			return;
		}

		$strictTypesPointer = null;
		for ($i = $tokens[$declarePointer]['parenthesis_opener'] + 1; $i < $tokens[$declarePointer]['parenthesis_closer']; $i++) {
			if ($tokens[$i]['code'] !== T_STRING || $tokens[$i]['content'] !== 'strict_types') {
				continue;
			}

			$strictTypesPointer = $i;
			break;
		}

		if ($strictTypesPointer === null) {
			$fix = $phpcsFile->addFixableError(
				sprintf('Missing declare(%s).', $this->getStrictTypeDeclaration()),
				$declarePointer,
				self::CODE_DECLARE_STRICT_TYPES_MISSING
			);
			if ($fix) {
				$phpcsFile->fixer->beginChangeset();
				$phpcsFile->fixer->addContentBefore(
					$tokens[$declarePointer]['parenthesis_closer'],
					', ' . $this->getStrictTypeDeclaration()
				);
				$phpcsFile->fixer->endChangeset();
			}
			return;
		}

		/** @var int $numberPointer */
		$numberPointer = TokenHelper::findNext($phpcsFile, T_LNUMBER, $strictTypesPointer + 1);
		if ($tokens[$numberPointer]['content'] !== '1') {
			$fix = $phpcsFile->addFixableError(
				sprintf(
					'Expected %s, found %s.',
					$this->getStrictTypeDeclaration(),
					TokenHelper::getContent($phpcsFile, $strictTypesPointer, $numberPointer)
				),
				$declarePointer,
				self::CODE_DECLARE_STRICT_TYPES_MISSING
			);
			if ($fix) {
				$phpcsFile->fixer->beginChangeset();
				$phpcsFile->fixer->replaceToken($numberPointer, '1');
				$phpcsFile->fixer->endChangeset();
			}
			return;
		}

		$strictTypesContent = TokenHelper::getContent($phpcsFile, $strictTypesPointer, $numberPointer);
		$spacesCountAroundEqualsSign = SniffSettingsHelper::normalizeInteger($this->spacesCountAroundEqualsSign);
		$format = sprintf('strict_types%1$s=%1$s1', str_repeat(' ', $spacesCountAroundEqualsSign));
		if ($strictTypesContent !== $format) {
			$fix = $phpcsFile->addFixableError(
				sprintf(
					'Expected %s, found %s.',
					$format,
					$strictTypesContent
				),
				$strictTypesPointer,
				self::CODE_INCORRECT_STRICT_TYPES_FORMAT
			);
			if ($fix) {
				$phpcsFile->fixer->beginChangeset();
				$phpcsFile->fixer->replaceToken($strictTypesPointer, $format);
				for ($i = $strictTypesPointer + 1; $i <= $numberPointer; $i++) {
					$phpcsFile->fixer->replaceToken($i, '');
				}
				$phpcsFile->fixer->endChangeset();
			}
		}

		$pointerBeforeDeclare = TokenHelper::findPreviousExcluding($phpcsFile, T_WHITESPACE, $declarePointer - 1);

		$whitespaceBefore = '';
		if ($pointerBeforeDeclare === $openTagPointer) {
			$whitespaceBefore .= substr($tokens[$openTagPointer]['content'], strlen('<?php'));
		}

		if ($pointerBeforeDeclare + 1 !== $declarePointer) {
			$whitespaceBefore .= TokenHelper::getContent($phpcsFile, $pointerBeforeDeclare + 1, $declarePointer - 1);
		}

		$requiredNewlinesCountBetweenOpenTagAndDeclare = SniffSettingsHelper::normalizeInteger(
			$this->newlinesCountBetweenOpenTagAndDeclare
		);
		if ($requiredNewlinesCountBetweenOpenTagAndDeclare === 0) {
			if ($whitespaceBefore !== ' ') {
				$fix = $phpcsFile->addFixableError(
					'There must be a single space between the PHP open tag and declare statement.',
					$declarePointer,
					self::CODE_INCORRECT_WHITESPACE_BETWEEN_OPEN_TAG_AND_DECLARE
				);
				if ($fix) {
					$phpcsFile->fixer->beginChangeset();
					$phpcsFile->fixer->replaceToken($openTagPointer, '<?php ');
					for ($i = $openTagPointer + 1; $i < $declarePointer; $i++) {
						$phpcsFile->fixer->replaceToken($i, '');
					}
					$phpcsFile->fixer->endChangeset();
				}
			}
		} else {
			$newlinesCountBefore = substr_count($whitespaceBefore, $phpcsFile->eolChar);
			if ($newlinesCountBefore !== $requiredNewlinesCountBetweenOpenTagAndDeclare) {
				$fix = $phpcsFile->addFixableError(
					sprintf(
						'Expected %d newlines between PHP open tag and declare statement, found %d.',
						$requiredNewlinesCountBetweenOpenTagAndDeclare,
						$newlinesCountBefore
					),
					$declarePointer,
					self::CODE_INCORRECT_WHITESPACE_BETWEEN_OPEN_TAG_AND_DECLARE
				);
				if ($fix) {
					$phpcsFile->fixer->beginChangeset();
					$phpcsFile->fixer->replaceToken($openTagPointer, '<?php');
					for ($i = $openTagPointer + 1; $i < $declarePointer; $i++) {
						$phpcsFile->fixer->replaceToken($i, '');
					}
					for ($i = 0; $i < $requiredNewlinesCountBetweenOpenTagAndDeclare; $i++) {
						$phpcsFile->fixer->addNewline($openTagPointer);
					}
					$phpcsFile->fixer->endChangeset();
				}
			}
		}

		/** @var int $declareSemicolonPointer */
		$declareSemicolonPointer = TokenHelper::findNextEffective($phpcsFile, $tokens[$declarePointer]['parenthesis_closer'] + 1);
		$pointerAfterWhitespaceEnd = TokenHelper::findNextExcluding($phpcsFile, T_WHITESPACE, $declareSemicolonPointer + 1);
		if ($pointerAfterWhitespaceEnd === null) {
			return;
		}

		$whitespaceAfter = TokenHelper::getContent($phpcsFile, $declareSemicolonPointer + 1, $pointerAfterWhitespaceEnd - 1);

		$requiredNewlinesCountAfter = SniffSettingsHelper::normalizeInteger($this->newlinesCountAfterDeclare);
		$newlinesCountAfter = substr_count($whitespaceAfter, $phpcsFile->eolChar);

		if ($newlinesCountAfter === $requiredNewlinesCountAfter) {
			return;
		}

		$fix = $phpcsFile->addFixableError(
			sprintf(
				'Expected %d newlines after declare statement, found %d.',
				$requiredNewlinesCountAfter,
				$newlinesCountAfter
			),
			$declarePointer,
			self::CODE_INCORRECT_WHITESPACE_AFTER_DECLARE
		);
		if (!$fix) {
			return;
		}

		$phpcsFile->fixer->beginChangeset();
		for ($i = $declareSemicolonPointer + 1; $i < $pointerAfterWhitespaceEnd; $i++) {
			$phpcsFile->fixer->replaceToken($i, '');
		}
		for ($i = 0; $i < $requiredNewlinesCountAfter; $i++) {
			$phpcsFile->fixer->addNewline($declareSemicolonPointer);
		}
		$phpcsFile->fixer->endChangeset();
	}

	protected function getStrictTypeDeclaration(): string
	{
		return sprintf(
			'strict_types%s=%s1',
			str_repeat(' ', SniffSettingsHelper::normalizeInteger($this->spacesCountAroundEqualsSign)),
			str_repeat(' ', SniffSettingsHelper::normalizeInteger($this->spacesCountAroundEqualsSign))
		);
	}

}

<?php

namespace Doctrine\Common\Annotations;

use function array_merge;
use function count;
use function explode;
use function strtolower;
use function token_get_all;

use const PHP_VERSION_ID;
use const T_AS;
use const T_COMMENT;
use const T_DOC_COMMENT;
use const T_NAME_FULLY_QUALIFIED;
use const T_NAME_QUALIFIED;
use const T_NAMESPACE;
use const T_NS_SEPARATOR;
use const T_STRING;
use const T_USE;
use const T_WHITESPACE;

/**
 * Parses a file for namespaces/use/class declarations.
 */
class TokenParser
{
    /**
     * The token list.
     *
     * @phpstan-var list<mixed[]>
     */
    private $tokens;

    /**
     * The number of tokens.
     *
     * @var int
     */
    private $numTokens;

    /**
     * The current array pointer.
     *
     * @var int
     */
    private $pointer = 0;

    /**
     * @param string $contents
     */
    public function __construct($contents)
    {
        $this->tokens = token_get_all($contents);

        // The PHP parser sets internal compiler globals for certain things. Annoyingly, the last docblock comment it
        // saw gets stored in doc_comment. When it comes to compile the next thing to be include()d this stored
        // doc_comment becomes owned by the first thing the compiler sees in the file that it considers might have a
        // docblock. If the first thing in the file is a class without a doc block this would cause calls to
        // getDocBlock() on said class to return our long lost doc_comment. Argh.
        // To workaround, cause the parser to parse an empty docblock. Sure getDocBlock() will return this, but at least
        // it's harmless to us.
        token_get_all("<?php\n/**\n *\n */");

        $this->numTokens = count($this->tokens);
    }

    /**
     * Gets the next non whitespace and non comment token.
     *
     * @param bool $docCommentIsComment If TRUE then a doc comment is considered a comment and skipped.
     * If FALSE then only whitespace and normal comments are skipped.
     *
     * @return mixed[]|string|null The token if exists, null otherwise.
     */
    public function next($docCommentIsComment = true)
    {
        for ($i = $this->pointer; $i < $this->numTokens; $i++) {
            $this->pointer++;
            if (
                $this->tokens[$i][0] === T_WHITESPACE ||
                $this->tokens[$i][0] === T_COMMENT ||
                ($docCommentIsComment && $this->tokens[$i][0] === T_DOC_COMMENT)
            ) {
                continue;
            }

            return $this->tokens[$i];
        }

        return null;
    }

    /**
     * Parses a single use statement.
     *
     * @return array<string, string> A list with all found class names for a use statement.
     */
    public function parseUseStatement()
    {
        $groupRoot     = '';
        $class         = '';
        $alias         = '';
        $statements    = [];
        $explicitAlias = false;
        while (($token = $this->next())) {
            if (! $explicitAlias && $token[0] === T_STRING) {
                $class .= $token[1];
                $alias  = $token[1];
            } elseif ($explicitAlias && $token[0] === T_STRING) {
                $alias = $token[1];
            } elseif (
                PHP_VERSION_ID >= 80000 &&
                ($token[0] === T_NAME_QUALIFIED || $token[0] === T_NAME_FULLY_QUALIFIED)
            ) {
                $class .= $token[1];

                $classSplit = explode('\\', $token[1]);
                $alias      = $classSplit[count($classSplit) - 1];
            } elseif ($token[0] === T_NS_SEPARATOR) {
                $class .= '\\';
                $alias  = '';
            } elseif ($token[0] === T_AS) {
                $explicitAlias = true;
                $alias         = '';
            } elseif ($token === ',') {
                $statements[strtolower($alias)] = $groupRoot . $class;
                $class                          = '';
                $alias                          = '';
                $explicitAlias                  = false;
            } elseif ($token === ';') {
                $statements[strtolower($alias)] = $groupRoot . $class;
                break;
            } elseif ($token === '{') {
                $groupRoot = $class;
                $class     = '';
            } elseif ($token === '}') {
                continue;
            } else {
                break;
            }
        }

        return $statements;
    }

    /**
     * Gets all use statements.
     *
     * @param string $namespaceName The namespace name of the reflected class.
     *
     * @return array<string, string> A list with all found use statements.
     */
    public function parseUseStatements($namespaceName)
    {
        $statements = [];
        while (($token = $this->next())) {
            if ($token[0] === T_USE) {
                $statements = array_merge($statements, $this->parseUseStatement());
                continue;
            }

            if ($token[0] !== T_NAMESPACE || $this->parseNamespace() !== $namespaceName) {
                continue;
            }

            // Get fresh array for new namespace. This is to prevent the parser to collect the use statements
            // for a previous namespace with the same name. This is the case if a namespace is defined twice
            // or if a namespace with the same name is commented out.
            $statements = [];
        }

        return $statements;
    }

    /**
     * Gets the namespace.
     *
     * @return string The found namespace.
     */
    public function parseNamespace()
    {
        $name = '';
        while (
            ($token = $this->next()) && ($token[0] === T_STRING || $token[0] === T_NS_SEPARATOR || (
            PHP_VERSION_ID >= 80000 &&
            ($token[0] === T_NAME_QUALIFIED || $token[0] === T_NAME_FULLY_QUALIFIED)
            ))
        ) {
            $name .= $token[1];
        }

        return $name;
    }

    /**
     * Gets the class name.
     *
     * @return string The found class name.
     */
    public function parseClass()
    {
        // Namespaces and class names are tokenized the same: T_STRINGs
        // separated by T_NS_SEPARATOR so we can use one function to provide
        // both.
        return $this->parseNamespace();
    }
}

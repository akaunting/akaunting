<?php

/*
 * KKSzymanowski/Traitor
 * Add a trait use statement to existing class
 *
 * @package KKSzymanowski/Traitor
 * @author Kuba Szymanowski <kuba.szymanowski@inf24.pl>
 * @link https://github.com/kkszymanowski/traitor
 * @license MIT
 */

namespace Traitor\Handlers;

use Exception;
use PhpParser\Error;
use PhpParser\Lexer;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\TraitUse;
use PhpParser\Node\Stmt\Use_;

class AbstractTreeHandler implements Handler
{
    /** @var array */
    protected $content;

    /** @var string */
    protected $trait;

    /** @var string */
    protected $traitShortName;

    /** @var string */
    protected $class;

    /** @var string */
    protected $classShortName;

    /** @var array */
    protected $syntaxTree;

    /** @var Namespace_ */
    protected $namespace;

    /** @var array */
    protected $importStatements;

    /** @var array */
    protected $classes;

    /** @var Class_ */
    protected $classAbstractTree;

    /** @var string */
    protected $lineEnding = "\n";

    /**
     * @param  array  $content
     * @param  string  $trait
     * @param  string  $class
     */
    public function __construct($content, $trait, $class)
    {
        $this->content = $content;

        $this->determineLineEnding();

        $this->trait = $trait;
        $traitParts = explode('\\', $trait);
        $this->traitShortName = array_pop($traitParts);

        $this->class = $class;
        $classParts = explode('\\', $class);
        $this->classShortName = array_pop($classParts);
    }

    /**
     * @return $this
     */
    public function handle()
    {
        $this->buildSyntaxTree()
             ->addTraitImport()
             ->buildSyntaxTree()
             ->addTraitUseStatement();

        return $this;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return implode($this->content);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->content;
    }

    /**
     * @return $this
     *
     * @throws Exception
     */
    protected function buildSyntaxTree()
    {
        $this->parseContent()
             ->retrieveNamespace()
             ->retrieveImports()
             ->retrieveClasses()
             ->findClassDefinition();

        return $this;
    }

    /**
     * @return $this
     */
    protected function addTraitImport()
    {
        if ($this->hasTraitImport()) {
            return $this;
        }

        $lastImport = $this->getLastImport();
        if ($lastImport === false) {
            $lineNumber = $this->classAbstractTree->getLine() - 1;
            $newImport = 'use '.$this->trait.';'.$this->lineEnding;

            array_splice($this->content, $lineNumber, 0, $this->lineEnding);
        } else {
            $lineNumber = $this->getLastImport()->getAttribute('endLine');
            $newImport = 'use '.$this->trait.';'.$this->lineEnding;
        }

        array_splice($this->content, $lineNumber, 0, $newImport);

        return $this;
    }

    /**
     * @return $this
     */
    protected function addTraitUseStatement()
    {
        if ($this->alreadyUsesTrait()) {
            return $this;
        }

        $this->openBracketsIfNecessary();

        $line = $this->getNewTraitUseLine();

        $newTraitUse = static::getIndentation($this->content[$line]).'use '.$this->traitShortName.';'.$this->lineEnding;

        array_splice($this->content, $line, 0, $newTraitUse);

        return $this;
    }

    /**
     * @return $this
     *
     * @throws Exception
     */
    protected function parseContent()
    {
        $flatContent = implode($this->content);

        try {
            $parser = $this->getParser();
            $this->syntaxTree = $parser->parse($flatContent);
        } catch (Error $e) {
            throw new Exception('Error on parsing '.$this->classShortName." class\n".$e->getMessage());
        }

        return $this;
    }

    /**
     * @return $this
     *
     * @throws Exception
     */
    protected function retrieveNamespace()
    {
        $namespaceNode = null;
        foreach ($this->syntaxTree as $item) {
            if ($item instanceof Namespace_) {
                $namespaceNode = $item;
            }
        }

        if (! $namespaceNode) {
//            var_dump($this->syntaxTree);die;
//            throw new Exception("Could not locate namespace definition for class '".$this->classShortName."'");
        }

        $this->namespace = $namespaceNode;

        return $this;
    }

    /**
     * @return $this
     */
    protected function retrieveImports()
    {
        if ($this->namespace === null) {
            $this->importStatements = array_filter($this->syntaxTree, function ($statement) {
                return $statement instanceof Use_;
            });
        } else {
            $this->importStatements = array_filter($this->namespace->stmts, function ($statement) {
                return $statement instanceof Use_;
            });
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function retrieveClasses()
    {
        if ($this->namespace === null) {
            $this->classes = array_filter($this->syntaxTree, function ($statement) {
                return $statement instanceof Class_;
            });
        } else {
            $this->classes = array_filter($this->namespace->stmts, function ($statement) {
                return $statement instanceof Class_;
            });
        }

        return $this;
    }

    /**
     * @return \PhpParser\Node\Stmt\Use_
     */
    protected function getLastImport()
    {
        return end($this->importStatements);
    }

    /**
     * @return bool
     */
    protected function hasTraitImport()
    {
        foreach ($this->importStatements as $statement) {
            if ($statement->uses[0]->name->toString() == $this->trait) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function alreadyUsesTrait()
    {
        $traitUses = array_filter($this->classAbstractTree->stmts, function ($statement) {
            return $statement instanceof TraitUse;
        });

        /** @var TraitUse $statement */
        foreach ($traitUses as $statement) {
            foreach ($statement->traits as $traitUse) {
                if ($traitUse->toString() == $this->trait
                    || $traitUse->toString() == $this->traitShortName
                ) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @return $this
     *
     * @throws Exception
     */
    protected function findClassDefinition()
    {
        foreach ($this->classes as $class) {
            if ($class->name == $this->classShortName) {
                $this->classAbstractTree = $class;

                return $this;
            }
        }

        throw new Exception('Class '.$this->classShortName.' not found');
    }

    /**
     * @return int
     *
     * @throws Exception
     */
    protected function getNewTraitUseLine()
    {
        // If the first statement is a trait use, insert the new trait use before it.
        if (isset($this->classAbstractTree->stmts[0])) {
            $firstStatement = $this->classAbstractTree->stmts[0];

            if ($firstStatement instanceof TraitUse) {
                return $firstStatement->getLine() - 1;
            }
        }

        // If the first statement is not a trait use, insert the new one just after the opening bracket.
        for ($line = $this->classAbstractTree->getLine() - 1; $line < count($this->content); $line++) {
            if (strpos($this->content[$line], '{') !== false) {
                return $line + 1;
            }
        }

        throw new Exception("Opening bracket not found in class [$this->classShortName]");
    }

    protected function openBracketsIfNecessary()
    {
        for ($line = $this->classAbstractTree->getLine() - 1; $line < count($this->content); $line++) {
            $trimmedLine = rtrim($this->content[$line]);

            if (substr($trimmedLine, strlen($trimmedLine) - 2) == '{}') {
                $trimmedLine = rtrim(substr($trimmedLine, 0, strlen($trimmedLine) - 2));
                if (strlen($trimmedLine) == 0) {
                    $this->content[$line] = '{'.$this->lineEnding;
                    array_splice($this->content, $line + 1, 0, '}'.$this->lineEnding);
                } else {
                    $this->content[$line] = $trimmedLine.$this->lineEnding;
                    array_splice($this->content, $line + 1, 0, '{'.$this->lineEnding);
                    array_splice($this->content, $line + 2, 0, '}'.$this->lineEnding);
                }

                $this->buildSyntaxTree();
                break;
            }
        }
    }

    /**
     * Default line ending is set to LF.
     *
     * If there is at least one line in the provided file
     * and it contains CR+LF, change line ending CR+LF.
     *
     * @return $this
     */
    protected function determineLineEnding()
    {
        if (isset($this->content[0]) && strpos($this->content[0], "\r\n") !== false) {
            $this->lineEnding = "\r\n";
        }

        return $this;
    }

    /**
     * @param $line
     * @return string
     */
    protected static function getIndentation($line)
    {
        preg_match('/^\s*/', $line, $match);

        if (isset($match[0])) {
            $match[0] = trim($match[0], "\n\r");

            if (strlen($match[0]) > 0) {
                return $match[0];
            }
        }

        return str_repeat(' ', 4);
    }

    protected function getParser()
    {
        $refParser = new \ReflectionClass('\PhpParser\Parser');

        if (! $refParser->isInterface()) {
            // If we are running nikic/php-parser 1.*
            return new \PhpParser\Parser(new Lexer());
        } else {
            // If we are running nikic/php-parser 2.*, 3.* or 4.*
            return (new \PhpParser\ParserFactory)->create(\PhpParser\ParserFactory::PREFER_PHP7);
        }
    }
}

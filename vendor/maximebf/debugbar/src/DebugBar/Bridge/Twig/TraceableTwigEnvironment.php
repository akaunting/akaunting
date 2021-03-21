<?php
/*
 * This file is part of the DebugBar package.
 *
 * (c) 2013 Maxime Bouroumeau-Fuseau
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DebugBar\Bridge\Twig;

use DebugBar\DataCollector\TimeDataCollector;
use Twig_CompilerInterface;
use Twig_Environment;
use Twig_ExtensionInterface;
use Twig_LexerInterface;
use Twig_LoaderInterface;
use Twig_NodeInterface;
use Twig_NodeVisitorInterface;
use Twig_ParserInterface;
use Twig_TokenParserInterface;
use Twig_TokenStream;

/**
 * Wrapped a Twig Environment to provide profiling features
 * 
 * @deprecated
 */
class TraceableTwigEnvironment extends Twig_Environment
{
    protected $twig;

    protected $renderedTemplates = array();

    protected $timeDataCollector;

    /**
     * @param Twig_Environment $twig
     * @param TimeDataCollector $timeDataCollector
     */
    public function __construct(Twig_Environment $twig, TimeDataCollector $timeDataCollector = null)
    {
        $this->twig = $twig;
        $this->timeDataCollector = $timeDataCollector;
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array(array($this->twig, $name), $arguments);
    }

    public function getRenderedTemplates()
    {
        return $this->renderedTemplates;
    }

    public function addRenderedTemplate(array $info)
    {
        $this->renderedTemplates[] = $info;
    }

    public function getTimeDataCollector()
    {
        return $this->timeDataCollector;
    }

    public function getBaseTemplateClass()
    {
        return $this->twig->getBaseTemplateClass();
    }

    public function setBaseTemplateClass($class)
    {
        $this->twig->setBaseTemplateClass($class);
    }

    public function enableDebug()
    {
        $this->twig->enableDebug();
    }

    public function disableDebug()
    {
        $this->twig->disableDebug();
    }

    public function isDebug()
    {
        return $this->twig->isDebug();
    }

    public function enableAutoReload()
    {
        $this->twig->enableAutoReload();
    }

    public function disableAutoReload()
    {
        $this->twig->disableAutoReload();
    }

    public function isAutoReload()
    {
        return $this->twig->isAutoReload();
    }

    public function enableStrictVariables()
    {
        $this->twig->enableStrictVariables();
    }

    public function disableStrictVariables()
    {
        $this->twig->disableStrictVariables();
    }

    public function isStrictVariables()
    {
        return $this->twig->isStrictVariables();
    }

    public function getCache($original = true)
    {
        return $this->twig->getCache($original);
    }

    public function setCache($cache)
    {
        $this->twig->setCache($cache);
    }

    public function getCacheFilename($name)
    {
        return $this->twig->getCacheFilename($name);
    }

    public function getTemplateClass($name, $index = null)
    {
        return $this->twig->getTemplateClass($name, $index);
    }

    public function getTemplateClassPrefix()
    {
        return $this->twig->getTemplateClassPrefix();
    }

    public function render($name, array $context = array())
    {
        return $this->loadTemplate($name)->render($context);
    }

    public function display($name, array $context = array())
    {
        $this->loadTemplate($name)->display($context);
    }

    public function loadTemplate($name, $index = null)
    {
        $cls = $this->twig->getTemplateClass($name, $index);

        if (isset($this->twig->loadedTemplates[$cls])) {
            return $this->twig->loadedTemplates[$cls];
        }

        if (!class_exists($cls, false)) {
            if (false === $cache = $this->getCacheFilename($name)) {
                eval('?>'.$this->compileSource($this->getLoader()->getSource($name), $name));
            } else {
                if (!is_file($cache) || ($this->isAutoReload() && !$this->isTemplateFresh($name, filemtime($cache)))) {
                    $this->writeCacheFile($cache, $this->compileSource($this->getLoader()->getSource($name), $name));
                }

                require_once $cache;
            }
        }

        if (!$this->twig->runtimeInitialized) {
            $this->initRuntime();
        }

        return $this->twig->loadedTemplates[$cls] = new TraceableTwigTemplate($this, new $cls($this));
    }

    public function isTemplateFresh($name, $time)
    {
        return $this->twig->isTemplateFresh($name, $time);
    }

    public function resolveTemplate($names)
    {
        return $this->twig->resolveTemplate($names);
    }

    public function clearTemplateCache()
    {
        $this->twig->clearTemplateCache();
    }

    public function clearCacheFiles()
    {
        $this->twig->clearCacheFiles();
    }

    public function getLexer()
    {
        return $this->twig->getLexer();
    }

    public function setLexer(Twig_LexerInterface $lexer)
    {
        $this->twig->setLexer($lexer);
    }

    public function tokenize($source, $name = null)
    {
        return $this->twig->tokenize($source, $name);
    }

    public function getParser()
    {
        return $this->twig->getParser();
    }

    public function setParser(Twig_ParserInterface $parser)
    {
        $this->twig->setParser($parser);
    }

    public function parse(Twig_TokenStream $tokens)
    {
        return $this->twig->parse($tokens);
    }

    public function getCompiler()
    {
        return $this->twig->getCompiler();
    }

    public function setCompiler(Twig_CompilerInterface $compiler)
    {
        $this->twig->setCompiler($compiler);
    }

    public function compile(Twig_NodeInterface $node)
    {
        return $this->twig->compile($node);
    }

    public function compileSource($source, $name = null)
    {
        return $this->twig->compileSource($source, $name);
    }

    public function setLoader(Twig_LoaderInterface $loader)
    {
        $this->twig->setLoader($loader);
    }

    public function getLoader()
    {
        return $this->twig->getLoader();
    }

    public function setCharset($charset)
    {
        $this->twig->setCharset($charset);
    }

    public function getCharset()
    {
        return $this->twig->getCharset();
    }

    public function initRuntime()
    {
        $this->twig->initRuntime();
    }

    public function hasExtension($name)
    {
        return $this->twig->hasExtension($name);
    }

    public function getExtension($name)
    {
        return $this->twig->getExtension($name);
    }

    public function addExtension(Twig_ExtensionInterface $extension)
    {
        $this->twig->addExtension($extension);
    }

    public function removeExtension($name)
    {
        $this->twig->removeExtension($name);
    }

    public function setExtensions(array $extensions)
    {
        $this->twig->setExtensions($extensions);
    }

    public function getExtensions()
    {
        return $this->twig->getExtensions();
    }

    public function addTokenParser(Twig_TokenParserInterface $parser)
    {
        $this->twig->addTokenParser($parser);
    }

    public function getTokenParsers()
    {
        return $this->twig->getTokenParsers();
    }

    public function getTags()
    {
        return $this->twig->getTags();
    }

    public function addNodeVisitor(Twig_NodeVisitorInterface $visitor)
    {
        $this->twig->addNodeVisitor($visitor);
    }

    public function getNodeVisitors()
    {
        return $this->twig->getNodeVisitors();
    }

    public function addFilter($name, $filter = null)
    {
        $this->twig->addFilter($name, $filter);
    }

    public function getFilter($name)
    {
        return $this->twig->getFilter($name);
    }

    public function registerUndefinedFilterCallback($callable)
    {
        $this->twig->registerUndefinedFilterCallback($callable);
    }

    public function getFilters()
    {
        return $this->twig->getFilters();
    }

    public function addTest($name, $test = null)
    {
        $this->twig->addTest($name, $test);
    }

    public function getTests()
    {
        return $this->twig->getTests();
    }

    public function getTest($name)
    {
        return $this->twig->getTest($name);
    }

    public function addFunction($name, $function = null)
    {
        $this->twig->addFunction($name, $function);
    }

    public function getFunction($name)
    {
        return $this->twig->getFunction($name);
    }

    public function registerUndefinedFunctionCallback($callable)
    {
        $this->twig->registerUndefinedFunctionCallback($callable);
    }

    public function getFunctions()
    {
        return $this->twig->getFunctions();
    }

    public function addGlobal($name, $value)
    {
        $this->twig->addGlobal($name, $value);
    }

    public function getGlobals()
    {
        return $this->twig->getGlobals();
    }

    public function mergeGlobals(array $context)
    {
        return $this->twig->mergeGlobals($context);
    }

    public function getUnaryOperators()
    {
        return $this->twig->getUnaryOperators();
    }

    public function getBinaryOperators()
    {
        return $this->twig->getBinaryOperators();
    }

    public function computeAlternatives($name, $items)
    {
        return $this->twig->computeAlternatives($name, $items);
    }
}

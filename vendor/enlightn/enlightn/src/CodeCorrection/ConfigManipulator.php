<?php

namespace Enlightn\Enlightn\CodeCorrection;

use Illuminate\Filesystem\Filesystem;
use PhpParser\Lexer\Emulative;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\CloningVisitor;
use PhpParser\Parser\Php7;
use PhpParser\PrettyPrinter\Standard;

class ConfigManipulator
{
    use ConstructsConfigurationAST;

    /**
     * Get the config values as pretty printed code.
     *
     * @param array $configValues
     * @return string
     */
    public function get($configValues = [])
    {
        $items = [];

        foreach ($configValues as $key => $configValue) {
            $items[] = new ArrayItem(
                $this->getConfiguration($configValue),
                new String_($key)
            );
        }

        $ast = [new Array_($items)];

        return (new Standard(['shortArraySyntax' => true]))->prettyPrint($ast);
    }

    /**
     * Modify the config file with the given config values.
     *
     * @param string $configFilePath
     * @param array $configValues
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function replace(string $configFilePath, $configValues = [])
    {
        $lexer = new Emulative([
            'usedAttributes' => [
                'comments',
                'startLine', 'endLine',
                'startTokenPos', 'endTokenPos',
            ],
        ]);
        $parser = new Php7($lexer);

        $ast = $parser->parse((new Filesystem)->get($configFilePath));
        $oldTokens = $lexer->getTokens();

        $traverser = new NodeTraverser;
        $traverser->addVisitor(new CloningVisitor);

        $config = require $configFilePath;

        foreach ($configValues as $key => $configValue) {
            if (isset($config[$key])) {
                $traverser->addVisitor(new ConfigReplacementNodeVisitor($key, $configValue));
            }
        }

        $newAst = $traverser->traverse($ast);

        foreach ($configValues as $key => $configValue) {
            if (! isset($config[$key])
                && isset($newAst[0])
                && $newAst[0] instanceof Return_
                && $newAst[0]->expr instanceof Array_) {
                $newAst[0]->expr->items[] = new ArrayItem(
                    $this->getConfiguration($configValue),
                    new String_($key)
                );
            }
        }

        $newCode = (new Standard(['shortArraySyntax' => true]))->printFormatPreserving($newAst, $ast, $oldTokens);

        (new Filesystem)->put($configFilePath, $newCode);
    }
}

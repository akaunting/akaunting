<?php

namespace Enlightn\Enlightn\CodeCorrection;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class ConfigReplacementNodeVisitor extends NodeVisitorAbstract
{
    use ConstructsConfigurationAST;

    /**
     * @var string
     */
    protected $configKey;

    /**
     * @var string|array
     */
    protected $configValue;

    public function __construct($configKey = null, $configValue = null)
    {
        $this->configKey = $configKey;
        $this->configValue = $configValue;
    }

    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Expr\ArrayItem
            && $node->key instanceof Node\Scalar\String_
            && $node->key->value === $this->configKey) {
            $node->value = $this->getConfiguration($this->configValue);
        }
    }
}

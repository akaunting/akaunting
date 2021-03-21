<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\CommonMark\Extension\HeadingPermalink;

use League\CommonMark\Block\Element\Document;
use League\CommonMark\Block\Element\Heading;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Exception\InvalidOptionException;
use League\CommonMark\Extension\HeadingPermalink\Slug\SlugGeneratorInterface as DeprecatedSlugGeneratorInterface;
use League\CommonMark\Inline\Element\Code;
use League\CommonMark\Inline\Element\Text;
use League\CommonMark\Node\Node;
use League\CommonMark\Normalizer\SlugNormalizer;
use League\CommonMark\Normalizer\TextNormalizerInterface;
use League\CommonMark\Util\ConfigurationAwareInterface;
use League\CommonMark\Util\ConfigurationInterface;

/**
 * Searches the Document for Heading elements and adds HeadingPermalinks to each one
 */
final class HeadingPermalinkProcessor implements ConfigurationAwareInterface
{
    const INSERT_BEFORE = 'before';
    const INSERT_AFTER = 'after';

    /** @var TextNormalizerInterface|DeprecatedSlugGeneratorInterface */
    private $slugNormalizer;

    /** @var ConfigurationInterface */
    private $config;

    /**
     * @param TextNormalizerInterface|DeprecatedSlugGeneratorInterface|null $slugNormalizer
     */
    public function __construct($slugNormalizer = null)
    {
        if ($slugNormalizer instanceof DeprecatedSlugGeneratorInterface) {
            @trigger_error(sprintf('Passing a %s into the %s constructor is deprecated; use a %s instead', DeprecatedSlugGeneratorInterface::class, self::class, TextNormalizerInterface::class), E_USER_DEPRECATED);
        }

        $this->slugNormalizer = $slugNormalizer ?? new SlugNormalizer();
    }

    public function setConfiguration(ConfigurationInterface $configuration)
    {
        $this->config = $configuration;
    }

    public function __invoke(DocumentParsedEvent $e): void
    {
        $this->useNormalizerFromConfigurationIfProvided();

        $walker = $e->getDocument()->walker();

        while ($event = $walker->next()) {
            $node = $event->getNode();
            if ($node instanceof Heading && $event->isEntering()) {
                $this->addHeadingLink($node, $e->getDocument());
            }
        }
    }

    private function useNormalizerFromConfigurationIfProvided(): void
    {
        $generator = $this->config->get('heading_permalink/slug_normalizer');
        if ($generator === null) {
            return;
        }

        if (!($generator instanceof DeprecatedSlugGeneratorInterface || $generator instanceof TextNormalizerInterface)) {
            throw new InvalidOptionException('The heading_permalink/slug_normalizer option must be an instance of ' . TextNormalizerInterface::class);
        }

        $this->slugNormalizer = $generator;
    }

    private function addHeadingLink(Heading $heading, Document $document): void
    {
        $text = $this->getChildText($heading);
        if ($this->slugNormalizer instanceof DeprecatedSlugGeneratorInterface) {
            $slug = $this->slugNormalizer->createSlug($text);
        } else {
            $slug = $this->slugNormalizer->normalize($text, $heading);
        }

        $slug = $this->ensureUnique($slug, $document);

        $headingLinkAnchor = new HeadingPermalink($slug);

        switch ($this->config->get('heading_permalink/insert', 'before')) {
            case self::INSERT_BEFORE:
                $heading->prependChild($headingLinkAnchor);

                return;
            case self::INSERT_AFTER:
                $heading->appendChild($headingLinkAnchor);

                return;
            default:
                throw new \RuntimeException("Invalid configuration value for heading_permalink/insert; expected 'before' or 'after'");
        }
    }

    /**
     * @deprecated Not needed in 2.0
     */
    private function getChildText(Node $node): string
    {
        $text = '';

        $walker = $node->walker();
        while ($event = $walker->next()) {
            if ($event->isEntering() && (($child = $event->getNode()) instanceof Text || $child instanceof Code)) {
                $text .= $child->getContent();
            }
        }

        return $text;
    }

    private function ensureUnique(string $proposed, Document $document): string
    {
        // Quick path, it's a unique ID
        if (!isset($document->data['heading_ids'][$proposed])) {
            $document->data['heading_ids'][$proposed] = true;

            return $proposed;
        }

        $extension = 0;
        do {
            ++$extension;
        } while (isset($document->data['heading_ids']["$proposed-$extension"]));

        $document->data['heading_ids']["$proposed-$extension"] = true;

        return "$proposed-$extension";
    }
}

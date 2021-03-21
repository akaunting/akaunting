<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2020 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\Readline;

use Hoa\Console\Console;
use Hoa\Console\Cursor;
use Hoa\Console\Readline\Readline as HoaReadline;
use Psy\Exception\BreakException;

/**
 * Hoa\Console Readline implementation.
 */
class HoaConsole implements Readline
{
    /** @var HoaReadline */
    private $hoaReadline;

    /** @var string|null */
    private $lastPrompt;

    /**
     * @return bool
     */
    public static function isSupported()
    {
        return \class_exists(Console::class, true);
    }

    public function __construct()
    {
        $this->hoaReadline = new HoaReadline();
        $this->hoaReadline->addMapping('\C-l', function () {
            $this->redisplay();

            return HoaReadline::STATE_NO_ECHO;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function addHistory($line)
    {
        $this->hoaReadline->addHistory($line);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function clearHistory()
    {
        $this->hoaReadline->clearHistory();

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function listHistory()
    {
        $i = 0;
        $list = [];
        while (($item = $this->hoaReadline->getHistory($i++)) !== null) {
            $list[] = $item;
        }

        return $list;
    }

    /**
     * {@inheritdoc}
     */
    public function readHistory()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @throws BreakException if user hits Ctrl+D
     *
     * @return string
     */
    public function readline($prompt = null)
    {
        $this->lastPrompt = $prompt;

        return $this->hoaReadline->readLine($prompt);
    }

    /**
     * {@inheritdoc}
     */
    public function redisplay()
    {
        $currentLine = $this->hoaReadline->getLine();
        Cursor::clear('all');
        echo $this->lastPrompt, $currentLine;
    }

    /**
     * {@inheritdoc}
     */
    public function writeHistory()
    {
        return true;
    }
}

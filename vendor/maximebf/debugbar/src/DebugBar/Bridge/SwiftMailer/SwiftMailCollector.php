<?php
/*
 * This file is part of the DebugBar package.
 *
 * (c) 2013 Maxime Bouroumeau-Fuseau
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DebugBar\Bridge\SwiftMailer;

use DebugBar\DataCollector\AssetProvider;
use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\Renderable;
use Swift_Mailer;
use Swift_Plugins_MessageLogger;

/**
 * Collects data about sent mails
 *
 * http://swiftmailer.org/
 */
class SwiftMailCollector extends DataCollector implements Renderable, AssetProvider
{
    protected $messagesLogger;

    public function __construct(Swift_Mailer $mailer)
    {
        $this->messagesLogger = new Swift_Plugins_MessageLogger();
        $mailer->registerPlugin($this->messagesLogger);
    }

    public function collect()
    {
        $mails = array();
        foreach ($this->messagesLogger->getMessages() as $msg) {
            $mails[] = array(
                'to' => $this->formatTo($msg->getTo()),
                'subject' => $msg->getSubject(),
                'headers' => $msg->getHeaders()->toString()
            );
        }
        return array(
            'count' => count($mails),
            'mails' => $mails
        );
    }

    protected function formatTo($to)
    {
        if (!$to) {
            return '';
        }

        $f = array();
        foreach ($to as $k => $v) {
            $f[] = (empty($v) ? '' : "$v ") . "<$k>";
        }
        return implode(', ', $f);
    }

    public function getName()
    {
        return 'swiftmailer_mails';
    }

    public function getWidgets()
    {
        return array(
            'emails' => array(
                'icon' => 'inbox',
                'widget' => 'PhpDebugBar.Widgets.MailsWidget',
                'map' => 'swiftmailer_mails.mails',
                'default' => '[]',
                'title' => 'Mails'
            ),
            'emails:badge' => array(
                'map' => 'swiftmailer_mails.count',
                'default' => 'null'
            )
        );
    }

    public function getAssets()
    {
        return array(
            'css' => 'widgets/mails/widget.css',
            'js' => 'widgets/mails/widget.js'
        );
    }
}

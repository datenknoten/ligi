<?php

namespace Datenknoten\MessageCustomisationBundle\Composer;

use FOS\MessageBundle\ModelManager\MessageManagerInterface;
use FOS\MessageBundle\Sender\SenderInterface;
use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\ModelManager\ThreadManagerInterface;
use FOS\MessageBundle\MessageBuilder\ReplyMessageBuilder;
use FOS\MessageBundle\Composer\ComposerInterface;
use Datenknoten\MessageCustomisationBundle\Form\Builder\NewThreadMessageBuilder;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Factory for message builders
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class Composer implements ComposerInterface
{
    /**
     * Message manager
     *
     * @var MessageManagerInterface
     */
    protected $messageManager;

    /**
     * Thread manager
     *
     * @var ThreadManagerInterface
     */
    protected $threadManager;

    protected $managerRegistry;

    public function __construct(MessageManagerInterface $messageManager, ThreadManagerInterface $threadManager, ManagerRegistry $managerRegistry)
    {
        $this->messageManager = $messageManager;
        $this->threadManager = $threadManager;
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * Starts composing a message, starting a new thread
     *
     * @return NewThreadMessageBuilder
     */
    public function newThread()
    {
        $thread = $this->threadManager->createThread();
        $message = $this->messageManager->createMessage();

        return new NewThreadMessageBuilder($message, $thread,$this->managerRegistry);
    }

    /**
     * Starts composing a message in a reply to a thread
     *
     * @return ReplyMessageBuilder
     */
    public function reply(ThreadInterface $thread)
    {
        $message = $this->messageManager->createMessage();

        return new ReplyMessageBuilder($message, $thread);
    }
}

<?php

namespace Datenknoten\MessageCustomisationBundle\Form\Builder;

use FOS\MessageBundle\Model\MessageInterface;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Sender\SenderInterface;
use Doctrine\Common\Collections\Collection;
use FOS\MessageBundle\MessageBuilder\AbstractMessageBuilder;
use Doctrine\Common\Persistence\ManagerRegistry;
use FOS\MessageBundle\Model\ThreadInterface;

/**
 * Fluent interface message builder for new thread messages
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class NewThreadMessageBuilder extends AbstractMessageBuilder
{
    private $managerRegistry;

    public function __construct(MessageInterface $message, ThreadInterface $thread, ManagerRegistry $managerRegistry)
    {
        parent::__construct($message,$thread);
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * The thread subject
     *
     * @param  string
     * @return NewThreadMessageBuilder (fluent interface)
     */
    public function setSubject($subject)
    {
        $this->thread->setSubject($subject);

        return $this;
    }

    public function setItem($item)
    {
        if ($item instanceof Item) {
            $this->thread->setItem($item);
        } else if(is_string($item)) {
            $id = $item;
            $entityManager = $this->managerRegistry->getManagerForClass("LigiBundle:Item");
            $item = $entityManager
                  ->getRepository('LigiBundle:Item')
                  ->find($id);
            $this->thread->setItem($item);
        }
        return $this;
    }

    /**
     * @param  ParticipantInterface $recipient
     * @return NewThreadMessageBuilder (fluent interface)
     */
    public function addRecipient($recipient)
    {
        if ($recipient instanceof ParticipantInterface) {
            $this->thread->addParticipant($recipient);
        } else if(is_string($recipient)) {
            $id = $recipient;
            $entityManager = $this->managerRegistry->getManagerForClass("LigiBundle:User");
            $user = $entityManager
                  ->getRepository('LigiBundle:User')
                  ->find($id);
            $this->thread->addParticipant($user);
        }

        return $this;
    }

    /**
     * @param  Collection $recipients
     * @return NewThreadMessageBuilder
     */
    public function addRecipients(Collection $recipients)
    {
        foreach ($recipients as $recipient) {
            $this->addRecipient($recipient);
        }

        return $this;
    }

}

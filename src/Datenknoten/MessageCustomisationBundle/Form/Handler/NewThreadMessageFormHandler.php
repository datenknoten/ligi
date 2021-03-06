<?php

namespace Datenknoten\MessageCustomisationBundle\Form\Handler;

use FOS\MessageBundle\FormModel\AbstractMessage;
use Datenknoten\MessageCustomisationBundle\Form\Model\NewThreadMessage;
use FOS\MessageBundle\FormHandler\AbstractMessageFormHandler;

class NewThreadMessageFormHandler extends AbstractMessageFormHandler
{
    /**
     * Composes a message from the form data
     *
     * @param AbstractMessage $message
     * @return MessageInterface the composed message ready to be sent
     * @throws InvalidArgumentException if the message is not a NewThreadMessage
     */
    public function composeMessage(AbstractMessage $message)
    {
        if (!$message instanceof NewThreadMessage) {
            throw new \InvalidArgumentException(sprintf('Message must be a NewThreadMessage instance, "%s" given', get_class($message)));
        }

        return $this->composer->newThread()
            ->setSubject($message->getSubject())
            ->addRecipient($message->getRecipient())
            ->setSender($this->getAuthenticatedParticipant())
            ->setBody($message->getBody())
            ->setItem($message->getItem())
            ->getMessage();
    }
}

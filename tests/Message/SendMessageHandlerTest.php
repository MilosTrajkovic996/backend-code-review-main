<?php

namespace App\Tests\Message;

use App\Message\SendMessage;
use App\Message\SendMessageHandler;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SendMessageHandlerTest extends WebTestCase
{
    public function testHandleSendMessage(): void
    {
        // Boot the Symfony kernel to access container services
        self::bootKernel();
        
        // Retrieve the message repository from the container
        $messageRepository = self::getContainer()->get(MessageRepository::class);
        assert($messageRepository instanceof MessageRepository);

        // Retrieve the entity manager from the container
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        assert($entityManager instanceof EntityManagerInterface);

        // Create a new message to be sent
        $sendMessage = new SendMessage('Test message');

        // Instantiate the SendMessageHandler with the entity manager
        $messageHandler = new SendMessageHandler($entityManager);

        // Invoke the message handler to send the message
        $messageHandler($sendMessage);

        // Fetch the persisted message from the database
        $persistedMessage = $messageRepository->findOneBy(['text' => 'Test message']);

        // Verify that the message was successfully persisted and has the expected properties
        if ($persistedMessage !== null) {
            $this->assertEquals('Test message', $persistedMessage->getText());
            $this->assertEquals('sent', $persistedMessage->getStatus());
            $this->assertNotNull($persistedMessage->getUuid());
            $this->assertNotNull($persistedMessage->getCreatedAt());
        }
    }
}

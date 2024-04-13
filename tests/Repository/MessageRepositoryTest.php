<?php
declare(strict_types=1);

namespace Repository;

use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\ResetDatabase;

class MessageRepositoryTest extends KernelTestCase
{
    use ResetDatabase;
    
    public function test_it_has_connection(): void
    {
        self::bootKernel();
        
        $messageRepository = self::getContainer()->get(MessageRepository::class);
        assert($messageRepository instanceof MessageRepository);
        
        $this->assertSame([], $messageRepository->findAll());
    }

}
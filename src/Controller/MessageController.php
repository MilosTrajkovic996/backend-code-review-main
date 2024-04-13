<?php
declare(strict_types=1);

namespace App\Controller;

use App\Message\SendMessage;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;


class MessageController extends AbstractController
{
    // CODE REVIEW

    // In the previous version function appears to be attempting to list messages without specifying a method, which could lead to unexpected behavior.
    // Additionally, it retrieves a MessageRepository instance using the parameter name `$messages`, which can be confusing.
    // It then calls a `by()` method on the MessageRepository, which is unconventional and unclear.
    // The response format is also not optimal, as it directly encodes the messages array into JSON without proper error handling.
    

    /**
     * This function defines a route for listing messages and specifies the HTTP method as GET, which is clear and appropriate.
     * It retrieves the status parameter from the request query string and validates it to ensure it's one of the allowed values ('sent' or 'read').
     * If the status parameter is invalid, it returns a 400 Bad Request response with an error message, providing clear feedback to the client.
     * It then retrieves messages from the MessageRepository based on the provided status or fetches all messages if status is not provided.
     * The retrieved messages are transformed into a suitable format for the response, enhancing readability and consistency.
     * Finally, it returns a JsonResponse with the formatted messages, providing a clear and standardized response format.
    */
    #[Route('/messages', methods: ['GET'])]
    public function list(Request $request, MessageRepository $messageRepository): JsonResponse
    {
        // Retrieve messages based on request parameters
        $status = (string) $request->query->get('status');
    
        // Validate the status parameter
        if ($status !=='' && !in_array($status, ['sent', 'read'], true)) {
            // If the status parameter is provided but is not one of the allowed values, return a 400 Bad Request response
            return new JsonResponse(['error' => 'Invalid status parameter.'], Response::HTTP_BAD_REQUEST);
        }
    
        // Retrieve messages based on the provided status, or fetch all messages if status is not provided
        $messages = $messageRepository->byStatus($status);
    
        // Transform messages into a suitable format for response
        $formattedMessages = array_map(function ($message) {
            return [
                'uuid' => $message->getUuid(),
                'text' => $message->getText(),
                'status' => $message->getStatus(),
            ];
        }, $messages);
    
        // Return response with JSON content
        return new JsonResponse(['messages' => $formattedMessages]);
    }

    #[Route('/messages/send', methods: ['GET'])]
    public function send(Request $request, MessageBusInterface $bus): Response
    {

        //CODE REVIEW
        //The query parameter might be of mixed types depending on the client's request. To ensure that it's always treated as a string, we can explicitly cast it to a string
        //This will ensure that $text is always a string, which matches the expectation of the SendMessage constructor parameter.
        $text = (string) $request->query->get('text');
        
        if (!$text) {
            return new Response('Text is required', 400);
        }
        
        $bus->dispatch(new SendMessage($text));
        
        return new Response('Successfully sent', 204);
    }
}
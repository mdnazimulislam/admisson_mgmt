<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use OpenAI\Laravel\Facades\OpenAI;

class ChatBotController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500'
        ]);

        $userMessage = $request->message;
        
        // Define school-specific context
        $schoolContext = "
        You are an AI assistant for Boni School's admission system. 
        Here's what you need to know:
        
        SCHOOL INFO:
        - School Name: Boni School
        - Classes Available: Play Group to Class 9
        - Admission Process: Online application → Review → Admit Card → Test
        
        ADMISSION REQUIREMENTS:
        - Student photo (JPG/PNG)
        - Birth certificate
        - Guardian NID (optional)
        - Complete application form
        
        PROCESS:
        1. Fill online application form
        2. Upload required documents
        3. Submit application
        4. Wait for admin review
        5. Download admit card (if approved)
        6. Attend admission test
        
        Please provide helpful, accurate information about admissions. 
        If asked about something outside admissions, politely redirect to admission topics.
        Keep responses concise and friendly.
        ";

        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $schoolContext
                    ],
                    [
                        'role' => 'user',
                        'content' => $userMessage
                    ]
                ],
                'max_tokens' => 150,
                'temperature' => 0.7
            ]);

            $botResponse = $response->choices[0]->message->content;

        } catch (\Exception $e) {
            // Fallback responses for common questions
            $botResponse = $this->getFallbackResponse($userMessage);
        }

        return response()->json([
            'response' => $botResponse
        ]);
    }

    private function getFallbackResponse($message)
    {
        $message = strtolower($message);
        
        if (str_contains($message, 'admission') || str_contains($message, 'apply')) {
            return "To apply for admission at Boni School, please visit our online application form. We accept applications for classes from Play Group to Class 9. You'll need to upload a student photo, birth certificate, and fill out the complete form.";
        }
        
        if (str_contains($message, 'document') || str_contains($message, 'requirement')) {
            return "Required documents for admission: 1) Student photograph (JPG/PNG), 2) Birth certificate (PDF/Image), 3) Guardian NID (optional). Please ensure all documents are clear and readable.";
        }
        
        if (str_contains($message, 'class') || str_contains($message, 'grade')) {
            return "Boni School offers admission from Play Group, Nursery, KG, and Classes 1-9. Please select the appropriate class when filling out the application form.";
        }
        
        if (str_contains($message, 'status') || str_contains($message, 'check')) {
            return "You can check your application status using your Application ID on our status check page. You'll receive an admit card if your application is approved.";
        }
        
        if (str_contains($message, 'contact') || str_contains($message, 'phone') || str_contains($message, 'email')) {
            return "For admission inquiries, please contact the school office or use this chat system for immediate assistance. Make sure to provide your contact information in the application form.";
        }
        
        return "I'm here to help with Boni School admission questions! You can ask me about the application process, required documents, class availability, or checking your application status.";
    }
}

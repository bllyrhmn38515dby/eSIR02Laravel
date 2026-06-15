<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\Referral;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(Request $request, Referral $referral)
    {
        $request->validate(['body' => 'required|string']);

        $message = Message::create([
            'referral_id' => $referral->id,
            'user_id' => auth()->id(),
            'body' => $request->body,
        ]);

        $message->load('user');

        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'success' => true,
            'message' => $message,
            'time' => $message->created_at->format('H:i')
        ]);
    }
}

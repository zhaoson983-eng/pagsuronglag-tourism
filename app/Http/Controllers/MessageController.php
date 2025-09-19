<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Show message threads for the logged-in customer
     */
    public function index()
    {
        $user = Auth::user();

        $threads = Message::where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })
            ->with(['sender', 'receiver'])
            ->latest()
            ->get()
            ->unique(function ($message) {
                $participants = [$message->sender_id, $message->receiver_id];
                sort($participants);
                return implode('_', $participants);
            });

        return view('customer.messages.index', compact('threads'));
    }

    /**
     * Show message threads for the business owner
     */
    public function indexOwner()
    {
        $user = Auth::user();

        $threads = Message::where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })
            ->with(['sender', 'receiver'])
            ->latest()
            ->get()
            ->unique(function ($message) {
                $participants = [$message->sender_id, $message->receiver_id];
                sort($participants);
                return implode('_', $participants);
            });

        return view('business.messages.index', compact('threads'));
    }

    /**
     * Show full thread with a specific user
     * ✅ Allows initiating a new conversation
     */
    public function thread(User $user)
    {
        $me = Auth::user();

        $messages = Message::where(function ($q) use ($me, $user) {
                $q->where('sender_id', $me->id)->where('receiver_id', $user->id);
            })
            ->orWhere(function ($q) use ($me, $user) {
                $q->where('sender_id', $user->id)->where('receiver_id', $me->id);
            })
            ->with([
                'sender',
                'receiver',
                'order.orderItems.product',
                'order.business',
                'order.customer'
            ])
            ->orderBy('created_at')
            ->get();

        // Mark messages as read when viewing the thread
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $me->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('messages.thread', compact('messages', 'user'));
    }

    /**
     * Send a new message
     */
    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content'     => 'required|string|max:2000',
            'order_id'    => 'nullable|exists:orders,id',
        ]);

        $sender = Auth::user();
        $receiver = User::findOrFail($request->receiver_id);

        if ($sender->id === $receiver->id) {
            return back()->with('error', 'You cannot send a message to yourself.');
        }

        Message::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'order_id' => $request->order_id,
            'content' => $request->content,
        ]);

        return back()->with('success', 'Message sent!');
    }
}
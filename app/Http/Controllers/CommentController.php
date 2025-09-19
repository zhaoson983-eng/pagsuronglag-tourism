<?php

namespace App\Http\Controllers;

use App\Models\TouristSpot;
use App\Models\TouristSpotComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, TouristSpot $touristSpot)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $comment = TouristSpotComment::create([
            'tourist_spot_id' => $touristSpot->id,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully',
            'comment' => [
                'id' => $comment->id,
                'comment' => $comment->comment,
                'user_name' => Auth::user()->name,
                'created_at' => $comment->created_at->format('M d, Y'),
            ]
        ]);
    }

    /**
     * Get comments for a tourist spot
     */
    public function getComments(TouristSpot $touristSpot)
    {
        try {
            $comments = $touristSpot->comments()
                ->with('user.profile')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'comment' => $comment->comment,
                        'created_at_human' => $comment->created_at->diffForHumans(),
                        'user' => [
                            'id' => $comment->user->id,
                            'name' => $comment->user->name,
                            'profile_picture' => $comment->user->profile ? $comment->user->profile->profile_picture : null,
                            'profile_avatar' => null // Not using profile_avatar anymore
                        ]
                    ];
                });

            return response()->json([
                'success' => true,
                'comments' => $comments
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load comments'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TouristSpotComment $comment)
    {
        // Check if the authenticated user owns this comment
        if (Auth::id() !== $comment->user_id) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized to delete this comment'
            ], 403);
        }

        try {
            $comment->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Comment deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to delete comment'
            ], 500);
        }
    }
}

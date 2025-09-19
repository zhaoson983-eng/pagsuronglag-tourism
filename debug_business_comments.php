<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\BusinessComment;
use App\Models\User;
use App\Models\Profile;

echo "=== Business Comments Debug ===\n";

$comments = BusinessComment::with('user.profile')->get();
echo "Total business comments: " . $comments->count() . "\n\n";

foreach ($comments as $comment) {
    echo "Comment ID: " . $comment->id . "\n";
    echo "User ID: " . $comment->user_id . "\n";
    echo "User Name: " . $comment->user->name . "\n";
    echo "User has profile: " . ($comment->user->profile ? 'Yes' : 'No') . "\n";
    
    if ($comment->user->profile) {
        echo "Profile picture: " . ($comment->user->profile->profile_picture ?: 'NULL') . "\n";
    }
    
    echo "Comment: " . substr($comment->comment, 0, 50) . "...\n";
    echo "---\n";
}

echo "\n=== All Users with Profiles ===\n";
$usersWithProfiles = User::with('profile')->whereHas('profile')->get();
echo "Users with profiles: " . $usersWithProfiles->count() . "\n";

foreach ($usersWithProfiles as $user) {
    echo "User: " . $user->name . " - Profile Picture: " . ($user->profile->profile_picture ?: 'NULL') . "\n";
}

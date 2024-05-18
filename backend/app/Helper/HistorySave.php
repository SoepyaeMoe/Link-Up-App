<?php
namespace App\Helper;

use App\Models\HistoryTracker;

class HistorySave
{
   static function save($user, $content, $owner)
   {
        HistoryTracker::create([
            'user' => $user->id,
            'content' => $content,
            'owner' => $owner->id
        ]);
   }
}
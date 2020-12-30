<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('item_commented.{profileId}', function ($user, $profileId) {
    return $user->profile->id === $profileId;
});
Broadcast::channel('item_deleted.{profileId}', function ($user, $profileId) {
    return $user->profile->id === $profileId;
});
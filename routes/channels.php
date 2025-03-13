<?php

use App\Models\Scraper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('private.scraper.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('scraper.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

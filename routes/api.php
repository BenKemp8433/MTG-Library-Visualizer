<?php

use App\Http\Controllers\CardController;
use Illuminate\Support\Facades\Route;

Route::post('/cards/', [CardController::class, 'getCards'])->name('api.cards.get');

<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function getCards(Request $request): Collection
    {
        return Card::query()
                   ->whereIn('name', explode("\n", $request->toArray()['nameList']))
                   ->orderby('type')
                   ->get();
    }
}

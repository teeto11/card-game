<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class Homecontroller extends Controller
{

    public function home()
    {
        $random_card_value = $this->getRandomCardValue();
        $win_stat = 0;
        $loss_stat = 0;
        $this->getLastSavedCardValue($random_card_value);
        session()->put('correct_counter', 0);
        session()->put('incorrect_counter', 0);
        return view('home', compact('random_card_value','win_stat','loss_stat'));
    }

    public function getRandomCardValue()
    {
        $array_values = array(
            2, 3, 4, 5, 6, 7, 8, 9, 10, "J", "Q", "K", "A",
            2, 3, 4, 5, 6, 7, 8, 9, 10, "J", "Q", "K", "A",
            2, 3, 4, 5, 6, 7, 8, 9, 10, "J", "Q", "K", "A",
            2, 3, 4, 5, 6, 7, 8, 9, 10, "J", "Q", "K", "A"
        );

        $random_numbers = rand(0, 51);
        $random_card = $array_values[$random_numbers];
        return $random_card;
    }
 
    public function submitCardGuess(Request $request)
    {
        $correct_counter = 0;
        $incorrect_counter = 0;
        $random_card_value = $this->getRandomCardValue();

        if (($request->input == 1) && ($random_card_value > $request->card_value)) {
            $correct_counter = session()->increment('correct_counter');
        } else if (($request->input == 1) && ($random_card_value < $request->card_value)) {
            $incorrect_counter = session()->increment('incorrect_counter');
        } else if (($request->input == 1) && ($random_card_value == $request->card_value)) {
            $incorrect_counter = session()->increment('incorrect_counter');
        }

        if (($request->input == 0) && ($random_card_value < $request->card_value)) {
            $correct_counter = session()->increment('correct_counter');
        } else if (($request->input == 0) && ($random_card_value > $request->card_value)) {
            $incorrect_counter = session()->increment('incorrect_counter');
        } else if (($request->input == 0) && ($random_card_value == $request->card_value)) {
            $incorrect_counter = session()->increment('incorrect_counter');
        }

        $data = [
            "new_card_value" => $random_card_value,
            "correct_counter" => $correct_counter,
            "incorrect_counter" => $incorrect_counter,
            "wins" => session()->get('correct_counter'),
            "loses" => session()->get('incorrect_counter')
        ];

        return response()->json(["msg" => "success", "data" => $data]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\OilPrice;
use App\Models\Deposit;
use App\Models\OilData;
use App\Models\Transaction;
use App\Models\Redeem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index()
    {
        // 3 data deposit user yang sedang login, terbaru
        $latestDeposits = Deposit::where('user_id', Auth::id())->latest()->take(3)->get();

        // 3 data redeem user yang sedang login, terbaru
        $latestRedeems = Redeem::where('user_id', Auth::id())->latest()->take(3)->get();

        return view('front.index', compact(
            'latestDeposits',
            'latestRedeems'
        ));
    }

    public function deposit()
    {
        // oil data user terbaru
        $latestOilData = OilData::where('user_id', Auth::id())->latest()->first();

        // Ambil 10 data deposit terbaru dengan pagination
        $latestDeposits = Deposit::where('user_id', Auth::id())->latest()->paginate(5);

        // Ambil 10 data redeem terbaru dengan pagination
        $latestRedeems = Redeem::where('user_id', Auth::id())->latest()->paginate(5);

        return view('front.deposit', compact(
            'latestOilData',
            'latestDeposits',
            'latestRedeems'
        ));
    }

}

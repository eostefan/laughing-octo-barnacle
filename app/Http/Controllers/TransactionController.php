<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $accounts = Account::withTrashed()->where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

        $account_ids = $accounts->pluck('id')->toArray();
        $transactions = Transaction::whereIn('account_id', $account_ids)->get();

        return response()->json([
            'transactions' => $transactions
        ]);
    }

    public function store(Request $request)
    {

        $accounts = Auth::user()->accounts();

        $validated = $request->validate([
            'description' => 'required|string|max:100',
            'ammount' => 'required|numeric',
            'currency' => 'required|string|max:10',
            'category' =>'required|string|max:50',
            'type' => 'required|string|max:10'
        ]);

        $account = $accounts->where('type', $validated['type'])->first();

        $validated['account_id'] = $account->id;
        $validated['initial_account_id'] = $account->id;

        $transaction = Transaction::create($validated);

        if ($validated['category'] == 'income') {
            $account->balance += $validated['ammount'];
            $account->save();
        } else {
            $account->balance -= $validated['ammount'];
            $account->save();
        }

        return response()->json(['transaction' => $transaction]);

    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{

    public function index()
    {
        return response()->json([
            'accounts' => Account::where('user_id', Auth::user()->id)->withTrashed()->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'type' => 'required',
            'institution' => 'required|string|max:255',
            'balance' => 'required|numeric',
            'currency' => 'required|string|max:3'
        ]);

        $validated['user_id'] = Auth::id();

        Account::create($validated);

        return redirect()->back()->with('success', 'Account created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $account = Account::findOrFail($id);
        $account->delete();

        return redirect()->back()->with('success', 'Account deleted successfully.');
    }
}

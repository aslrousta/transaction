<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * Transfers currency between two accounts.
     *
     * @param Request $request
     */
    public function transfer(Request $request)
    {
        $validator = Validator::make($request->post(), [
            'from'   => 'required|integer',
            'to'     => 'required|integer',
            'amount' => 'required|integer',
        ]);

        $validator->validate();
        $data = $validator->getData();
    }
}

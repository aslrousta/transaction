<?php

namespace App\Http\Controllers;

use App\Services\Transaction\InsufficientBalanceException;
use App\Services\Transaction\InvalidAccountException;
use App\Services\Transaction\TransactionManagerInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * Transfers currency between two accounts.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function transfer(Request $request)
    {
        $validator = Validator::make($request->post(), [
            'from'   => 'required|integer',
            'to'     => 'required|integer',
            'amount' => 'required|integer',
        ]);
        $validator->validate();

        $data   = $validator->getData();
        $from   = $data['from'];
        $to     = $data['to'];
        $amount = $data['amount'];

        /** @var TransactionManagerInterface $transactionManager */
        $transactionManager = app(TransactionManagerInterface::class);

        try {
            $balance = $transactionManager->transfer($from, $to, $amount);
            return Response::json(['balance' => $balance]);
        } catch (InvalidAccountException $e) {
            return Response::json(['error' => 'Account Not Found'], 404);
        } catch (InsufficientBalanceException $e) {
            return Response::json(['error' => 'Insufficient Balance'], 406);
        }
    }
}

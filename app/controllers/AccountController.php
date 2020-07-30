<?php


namespace App\controllers;


use Framework\Controller;
use Framework\ResponseApi;

class AccountController extends Controller
{

    protected function getModel(): string
    {
        return 'account_model';
    }

    public function transfer($c, $request)
    {
        $accountRecipient = $c['account_model'];
        $accountSender = $c['account_model'];

        $dataRequest = $request->request->all();

        $numberSender= $request->attributes->get(1);
        $numberRecipient = $request->attributes->get(2);

        $accountRecipientData = $accountRecipient->get(['number' => $numberRecipient]);
        $accountSenderData = $accountSender->get(['number' => $numberSender]);

        if($accountRecipientData && $accountSenderData && isset($dataRequest['cpf'])) {
            $userRecipientData = $c['user_model']->get(['id' => $accountRecipientData['users_id']]);
            if($dataRequest['cpf'] === $userRecipientData['cpf']){
                $accountRecipientData['balance'] += (float)$dataRequest['value'];
                $accountSenderData['balance'] -= (float)$dataRequest['value'];

                $accountRecipient->update(['number' => $numberRecipient], $accountRecipientData);
                $accountSender->update(['number' => $numberSender], $accountSenderData);
            }

            return ResponseApi::jsonResponse(false, "tranfer sucessful", $accountSender);
        }

        return ResponseApi::jsonResponse(true, "data request invalid");
    }

    public function deposit($c, $request)
    {
        $accountRecipient = $c['account_model'];

        $dataRequest = $request->request->all();
        $numberRecipient = $request->attributes->get(1);

        $accountRecipientData = $accountRecipient->get(['number' => $numberRecipient]);
        if ($accountRecipientData) {
            $accountRecipientData['balance'] += (float)$dataRequest['value'];
            $accountRecipient->update(['number'=> $numberRecipient], $accountRecipientData);
            return ResponseApi::jsonResponse(false, 'deposit successfully completed', ['new balance' => $accountRecipientData['balance']]);
        }

        return ResponseApi::jsonResponse(true, 'account not found');
    }

    public function withdrawal($c, $request)
    {
        $accountRecipient = $c['account_model'];

        $dataRequest = $request->request->all();
        $numberRecipient = $request->attributes->get(1);

        $accountRecipientData = $accountRecipient->get(['number' => $numberRecipient]);
        if ($accountRecipientData) {
            if ($accountRecipientData['balance'] >= $dataRequest['value']){
                $accountRecipientData['balance'] -= (float)$dataRequest['value'];
                $accountRecipient->update(['number'=> $numberRecipient], $accountRecipientData);
                return ResponseApi::jsonResponse(false, 'deposit successfully completed', ['new balance' => $accountRecipientData['balance']]);
            }

            return ResponseApi::jsonResponse(true, 'insufficient funds');
        }

        return ResponseApi::jsonResponse(true, 'account not found');
    }
}
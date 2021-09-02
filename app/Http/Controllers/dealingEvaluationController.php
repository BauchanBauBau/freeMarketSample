<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Dealing_status;
use App\Evaluation;
use App\Item;
use App\user;

use Illuminate\Support\Facades\Mail;

class dealingEvaluationController extends Controller
{
    public function buyerEvaluation(Request $request){ //購入者が出品者を評価する．
        $dealingStatus = Dealing_status::find($request->id);
        $evaluation = new Evaluation;
        $form = $request->all();
        $evaluation->fill($form);

        $evaluation->dealingStatus_id = $dealingStatus->id;
        $evaluation->item_id = $dealingStatus->item_id;
        $evaluation->seller_id = $dealingStatus->seller_id;
        $evaluation->buyer_id = $dealingStatus->buyer_id;

        $evaluation->buyerEvaluation = 0;//買手に対する
        $evaluation->buyerComment = 0; //買手に対する

        $evaluation->save();

        $dealingStatus->evaluated += 1; //evaluatedが2になれば取引終了．
        $dealingStatus->save();

            //メール送信
            $item = Item::find($dealingStatus->item_id);
            $seller = User::find($item->user_id);
            Mail::send('mail.evaluatedMailBuyer', [ //sellerに対してメールを送信する
                "item" => $item,
                "dealingStatus" => $dealingStatus,
            ], 
            function($message) use($item, $seller) { //無名関数に変数を渡すには，後ろにuse ($変数)と記載する．

                $message
                ->to($seller->email)
                ->subject("商品「" . $item->name . "」の受け取りが完了しました．購入者を評価してください．");
            });

        return redirect()->route('statusBuyer', ['id' => $dealingStatus->id]);
    }

    public function sellerEvaluation(Request $request){ //出品者が購入者を評価する．
        $dealingStatus = Dealing_status::find($request->id);
        
        $evaluation = Evaluation::where('dealingStatus_id', '=', $dealingStatus->id)->first();
        $evaluation->buyerEvaluation = $request->input('buyerEvaluation');
        $evaluation->buyerComment = $request->input('buyerComment');

        $evaluation->save();

        $dealingStatus->evaluated += 1; //evaluatedが2になれば取引終了．
        $dealingStatus->save();

                    //メール送信
                    $item = Item::find($dealingStatus->item_id);
                    $buyer = User::find($item->user_id);
                    Mail::send('mail.evaluatedMailSeller', [ //buyerに対してメールを送信する
                        "item" => $item,
                        "dealingStatus" => $dealingStatus,
                    ], 
                    function($message) use($item, $buyer) { //無名関数に変数を渡すには，後ろにuse ($変数)と記載する．
        
                        $message
                        ->to($buyer->email)
                        ->subject("商品「" . $item->name . "」の取引が終了しましました．");
                    });

        return redirect()->route('statusSeller', ['id' => $dealingStatus->id]);
    }

}
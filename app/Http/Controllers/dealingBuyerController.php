<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Item;
use App\Dealing_status;
use App\Dealing_message;
use App\Evaluation;
use App\User;
use App\Item_comment;
use App\Item_good;

use Illuminate\Support\Facades\Mail;

class dealingBuyerController extends Controller
{
    public function buy(Request $request){
        $item = Item::find($request->id);
        $dealingStatus = new Dealing_status;
        $dealingStatus->item_id = $item->id;
        $dealingStatus->seller_id = $item->user_id;
        $dealingStatus->buyer_id = Auth::id();
        $dealingStatus->payed = 0;
        $dealingStatus->shipped = 0;
        $dealingStatus->shippingNumber = 0;
        $dealingStatus->evaluated = 0;

        $dealingStatus->save();

        $item->buyer_id = $dealingStatus->buyer_id;

        $item->save();

        $comments = Item_comment::where('item_id', '=', $item->id)->get();
        foreach($comments as $comment){
            $comment->buyed = 1;
            $comment->save();
        }
        $goods = Item_good::where('item_id', '=', $item->id)->get();
        foreach($goods as $good){
            $good->buyed = 1;
            $good->save();
        }

            //メール送信
            $seller = User::find($dealingStatus->seller_id);
            $buyer = User::find($dealingStatus->buyer_id);

            Mail::send('mail.buyMailSeller', [ //sellerに対してメールを送信する
                "item" => $item,
                "dealingStatus" => $dealingStatus,
            ], 
            function($message) use($item, $seller) { //無名関数に変数を渡すには，後ろにuse ($変数)と記載する．

                $message
                ->to($seller->email)
                ->subject("商品「" . $item->name . "」が購入されました．取引を開始してください．");
            });
            
            Mail::send('mail.buyMailBuyer', [ //buyerに対してメールを送信する
                "item" => $item,
                "dealingStatus" => $dealingStatus,
            ], 
            function($message) use($item, $buyer) { //無名関数に変数を渡すには，後ろにuse ($変数)と記載する．

                $message
                ->to($buyer->email)
                ->subject("商品「" . $item->name . "」を購入しました．取引を開始してください．");
            });


        return redirect()->route('itemDetail', ['id' => $item->id]);
    }

    public function statusBuyer(Request $request)
    {
        $dealingStatus = Dealing_status::find($request->id);

        if($dealingStatus->buyer_id == Auth::id()){
            $item = Item::find($dealingStatus->item_id);
            $messages = Dealing_message::where('dealingStatus_id', '=' ,$dealingStatus->id)->get();
            
            return view('dealingStatus.statusBuyer', ['dealingStatus' => $dealingStatus, 'item' => $item, 'messages' => $messages]);
        
        }else{
            return redirect('/');
        }
    }

    public function statusBuyerPayed(Request $request)
    {
        $dealingStatus = Dealing_status::find($request->id);
        $item = Item::find($dealingStatus->item_id);
        if($dealingStatus->payed < 1){
            $dealingStatus->payed = 1; //1は支払済み
            $dealingStatus->save();

                //メール送信
                $seller = User::find($item->user_id);
                Mail::send('mail.buyerMailPayed', [ //sellerに対してメールを送信する
                    "item" => $item,
                    "dealingStatus" => $dealingStatus,
                ], 
                function($message) use($item, $seller) { //無名関数に変数を渡すには，後ろにuse ($変数)と記載する．

                    $message
                    ->to($seller->email)
                    ->subject("商品「" . $item->name . "」の代金が支払われました．発送してください．");
                });

            return redirect()->route('statusBuyer', ['id' => $dealingStatus->id]);
        }else{
            return redirect()->route('statusBuyer', ['id' => $dealingStatus->id]);
        };
    }

    public function statusSkip(Request $request)
    {
        $status = Dealing_status::find($request->id);
        $evaluation = Evaluation::where('dealingStatus_id', '=', $status->id)->first();
        
        if($status->payed > 0 && $status->shipped < 1){
            $status->shipped = 1;
            if($status->item->shippingMethod > 1 && $status->item->shippingMethod < 6)
            {
                $status->shippingNumber = 123456;
            }
            $status->save();
        }elseif($status->payed > 0 && $status->shipped > 0 && $status->evaluated < 2){
            $evaluation->buyerEvaluation = random_int(0, 1);
            $evaluation->buyerComment = "このコメントは相手からの評価をスキップした場合に作成されます．なお評価はランダムで決まるようになっています．";
            $evaluation->save();
            
            $status->evaluated =2;
            $status->save();
        }

        return redirect()->route('statusBuyer', ['id' => $status->id]);
    }
}

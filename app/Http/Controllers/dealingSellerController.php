<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Item;
use App\Dealing_status;
use App\Dealing_message;
use App\User;

use Illuminate\Support\Facades\Mail;

class dealingSellerController extends Controller
{
    public function statusSeller(Request $request)
    {
        $dealingStatus = Dealing_status::find($request->id);
        
        if($dealingStatus->seller_id == Auth::id()){
            $item = Item::find($dealingStatus->item_id);
            $messages = Dealing_message::where('dealingStatus_id', '=' ,$dealingStatus->id)->get();
            
            if(count($messages) > 0){
                //midokusは未読sである．
                $midokus = Dealing_message::where('dealingStatus_id', '=' ,$dealingStatus->id)
                ->where('user_id', '!=', $dealingStatus->seller_id)
                ->where('kidoku', '<', 1)
                ->where('messageDelete', '<', 1)
                ->get();
                foreach($midokus as $midoku){
                    $midoku->kidoku = 1;
                    $midoku->save();
                }
            }

            return view('dealingStatus.statusSeller', ['dealingStatus' => $dealingStatus, 'item' => $item, 'messages' => $messages]);
        
        }else{
            return redirect('/');
        }
    }

    public function statusSellerShipped(Request $request)
    {
        $dealingStatus = Dealing_status::find($request->id);
        $item = Item::find($dealingStatus->item_id);

        if($dealingStatus->shipped < 1){
            if($item->shippingMethod > 0 && $item->shippingMethod < 6){
                $dealingStatus->shippingNumber = $request->input('shippingNumber');
            }else{
                $dealingStatus->shippingNumber = 0;
            }
            
            $dealingStatus->shipped = 1; //1は発送済み
            $dealingStatus->save();

                //メール送信
                $buyer = User::find($item->buyer);
                Mail::send('mail.sellerMailShipped', [ //sellerに対してメールを送信する
                    "item" => $item,
                    "dealingStatus" => $dealingStatus,
                ], 
                function($message) use($item, $buyer) { //無名関数に変数を渡すには，後ろにuse ($変数)と記載する．

                    $message
                    ->to($buyer->email)
                    ->subject("商品「" . $item->name . "」が発送されました．");
                });


            return redirect()->route('statusSeller', ['id' => $dealingStatus->id]);
        }else{
            return redirect()->route('statusSeller', ['id' => $dealingStatus->id]);
        };
    }

    public function dealingMessage(Request $request) //売手・買手共同のメッセージ用メソッド
    {
        //$Mailファサードには既に$messageが存在するため，別途メッセージ用の変数に$Messageを使用する．
        //$Messageに$formを挿入する．
        $Message = new Dealing_message;
        $form = $request->all();
        $Message->fill($form);

        //下段で$Messageの要素を以下の変数の値に更新する．
        $dealingStatus = Dealing_status::find($request->id); //redirectでも使用する．
        $itemId = $dealingStatus->item_id;
        $sellerId = $dealingStatus->seller_id;
        $buyerId = $dealingStatus->buyer_id;
        $userId = Auth::id();

        //$Messageの要素を上段の変数の値へ更新する．
        $Message->dealingStatus_id = $dealingStatus->id;
        $Message->item_id = $itemId;
        $Message->seller_id = $sellerId;
        $Message->buyer_id = $buyerId;
        $Message->user_id = $userId;
        $Message->kidoku = 0;
        $Message->messageDelete = 0;

        $Message->save();

            //メール送信
            $item = Item::find($dealingStatus->item_id);
            $seller = User::find($Message->seller_id);
            $buyer = User::find($Message->buyer_id);
            if(Auth::id() == $buyer->id){ //sellerに対してメールを送信する
                Mail::send('mail.messageMailSeller', [
                    "buyer" => $buyer,
                    "seller" => $seller,
                    "item" => $item,
                    "dealingStatus" => $dealingStatus,
                    "Message" => $Message 
                ], 
                function($message) use($item, $seller, $buyer) { //無名関数に変数を渡すには，後ろにuse ($変数)と記載する．

                    $message
                    ->to($seller->email)
                    ->subject("取引中の商品「" . $item->name . "」について" . 
                    $buyer->nickName . "様からメッセージが届いています．");
                });
            }elseif(Auth::id() == $seller->id){ //buyerに対してメールを送信する
                Mail::send('mail.messageMailBuyer', [
                    "buyer" => $buyer,
                    "seller" => $seller,
                    "item" => $item,
                    "dealingStatus" => $dealingStatus,
                    "Message" => $Message 
                ], 
                function($message) use($item, $seller, $buyer) { //無名関数に変数を渡すには，後ろにuse ($変数)と記載する．

                    $message
                    ->to($buyer->email)
                    ->subject("取引中の商品「" . $item->name . "」について" . 
                    $seller->nickName . "様からメッセージが届いています．");
                });

            }
        
        if($userId == $dealingStatus->buyer_id){
            return redirect()->route('statusBuyer', ['id' => $dealingStatus->id]);
        }elseif($userId == $dealingStatus->seller_id){
            return redirect()->route('statusSeller', ['id' => $dealingStatus->id]);
        }
    }

    public function dealingMessageDelete(Request $request){ //売手・買手共同のメッセージ用メソッド
        $message = Dealing_message::find($request->id);
        $message->messageDelete = 1;
        $message->save();

        if($message->buyer_id == Auth::id()){
            return redirect()->route('statusBuyer', ['id' => $message->dealingStatus_id]);
        }elseif($message->seller_id == Auth::id()){
            return redirect()->route('statusSeller', ['id' => $message->dealingStatus_id]);
        }
    }

}
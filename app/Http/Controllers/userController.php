<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\User;
use App\Item_comment;
use App\Item_good;
use App\Dealing_status;
use App\Dealing_message;
use App\Evaluation;
use App\Item;
use App\Inquiry;

use Illuminate\Support\Facades\Mail;

class userController extends Controller
{   
    public function userInfo(Request $request)
    {   
        if(Auth::user()->role_id == 1){
            $user = User::find($request->id);
        }else{
            $user = Auth::user();
        }
        $dealingBuy = count(Dealing_status::where('evaluated', '<', 1)
        ->where('buyer_id', '=', $user->id)->get());
        $dealingSell = count(Dealing_status::where('evaluated', '<', 1)
        ->where('seller_id', '=', $user->id)->get());

        $dealing = $dealingBuy + $dealingSell;

        return view('user.userInfo', ['user' => $user, 'dealing' => $dealing]);
    }

    
    public function userEditGet(Request $request)
    {
        if(Auth::user()->role_id == 1){
            $user = User::find($request->id);       
        }else{
            $user = Auth::user();
        }
        return view('user.userEdit', ['user' => $user]);
    }

    public function userEditPost(Request $request)
    {
        if(Auth::user()->role_id == 1){
            $user = User::find($request->id);
        }else{
            $user = Auth::user();
        }
        $form = $request->all();

        $user->fill($form)->save();

        return redirect('/');
    }

    public function userDelete(Request $request){
        $userDel = User::find($request->id);

        Item::where('user_id', '=', $userDel->id)
        ->where('buyer_id', '<', 1)
        ->delete();

        Item_comment::where('user_id', '=', $userDel->id)->delete();

        Item_good::where('user_id', '=', $userDel->id)->delete();

        Inquiry::where('user_id', '=', $userDel->id)->delete();

        $userDel->delete();
        if(url('userIndex')){
            return redirect('userIndex');
        }else{
            return redirect('/');
        }

    }


    public function userCommentedItem(Request $request) //他のユーザーが出品した商品で，自分がコメントしたものを表示する．
    {
        $user = Auth::id();
        if($request->input('status') == 0){
            $commentedItems = Item_comment::where('user_id', '!=', $user)
            ->where('commentTo_id', '=', $user)
            ->where('kidoku', '<', 1)
            ->where('commentDelete', '<', 1)
            ->where('buyed', '<', 1)
            ->get()->unique('item_id'); //「->unique('item_id')」で重複を防止する．
            $status = 0;
        }elseif($request->input('status') == 1){
            $commentedItems = Item_comment::where('user_id', '!=', $user)
            ->where('watcher_id', '=', $user)
            ->where('commentDelete', '<', 1)
            ->where('buyed', '<', 1)
            ->get()->unique('item_id'); //「->unique('item_id')」で重複を防止する．
            $status = 1;
        }

        return view('user.userCommentedItem', [
            'user' => $user, 
            'commentedItems' => $commentedItems,
            'status' => $status
        ]);
    }

    public function userCommentedItemByWatcher(Request $request) //自分が出品した商品で，他のユーザーからコメントがあったものを表示する．
    {
        $user = Auth::id();
        if($request->input('status') == 0){
            $commentedItems = Item_comment::where('user_id', '=', $user)
            ->where('watcher_id', '!=', $user)
            ->where('kidoku', '<', 1)
            ->where('commentDelete', '<', 1)
            ->where('buyed', '<', 1)
            ->get()->unique('item_id'); //「->unique('item_id')」で重複を防止する．
            $status = 0;
        }elseif($request->input('status') == 1){
            $commentedItems = Item_comment::where('user_id', '=', $user)
            ->where('commentDelete', '<', 1)
            ->where('buyed', '<', 1)
            ->get()->unique('item_id'); //「->unique('item_id')」で重複を防止する．
            $status = 1;
        }

        return view('user.userCommentedItemByWatcher', [
            'user' => $user,
            'commentedItems' => $commentedItems,
            'status' => $status
        ]);
    }

    public function userGood()
    {
        $user = Auth::user();
        $goodItems = Item_good::where('watcher_id', '=', $user->id)
        ->where('buyed', '<', 1)
        ->get();
        return view('user.userGood', ['goodItems' => $goodItems]);
    }

    public function userGoodByWatcher()
    {
        $user = Auth::user();
        $goodItems = Item_good::where('watcher_id', '!=', $user->id)
        ->where('user_id', '=', $user->id)
        ->where('buyed', '<', 1)
        ->get();
        return view('user.userGoodByWatcher', ['goodItems' => $goodItems]);
    }
    
    public function userDealingBuy(Request $request)
    {
        $user = Auth::user();
        
        if($request->input('selectStatus')==0){
            $dealingStatuses = Dealing_status::where('buyer_id', '=', $user->id)
            ->where('evaluated', '<', 2)
            ->get();
        }elseif($request->input('selectStatus')==1){
            $dealingStatuses = Dealing_status::where('buyer_id', '=', $user->id)
            ->where('payed', '<', 1)
            ->get(); //1は「支払いをしてください」．
        }elseif($request->input('selectStatus')==2){
            $dealingStatuses = Dealing_status::where('buyer_id', '=', $user->id)
            ->where('payed', '>', 0)
            ->where('shipped', '<', 1)
            ->get(); //2は「発送をお待ちください」
        }elseif($request->input('selectStatus')==3){
            $dealingStatuses = Dealing_status::where('buyer_id', '=', $user->id)
            ->where('payed', '>', 0)
            ->where('shipped', '>', 0)
            ->where('evaluated', '<', 1)
            ->get(); //3は「受け取り商品が到着したら出品者を評価をしてください評価をしてください」
        }elseif($request->input('selectStatus')==4){
            $dealingStatuses = Dealing_status::where('buyer_id', '=', $user->id)
            ->where('payed', '>', 0)
            ->where('shipped', '>', 0)
            ->where('evaluated', '>', 0)
            ->where('evaluated', '<', 2)
            ->get(); //4は「出品者からの評価をお待ちください」
        }

        return view('user.userDealingBuy', [
        'user' => $user,
        'dealingStatuses' => $dealingStatuses
        ]);
    }

    public function userDealingSell(Request $request)
    {
        $user = Auth::user();
        
        if($request->input('selectStatus')==0){
            $dealingStatuses = Dealing_status::where('seller_id', '=', $user->id)
            ->where('evaluated', '<', 2)
            ->get();
        }elseif($request->input('selectStatus')==1){
            $dealingStatuses = Dealing_status::where('seller_id', '=', $user->id)
            ->where('payed', '<', 1)->get();//1は「支払いをお待ちください」．
        }elseif($request->input('selectStatus')==2){
            $dealingStatuses = Dealing_status::where('seller_id', '=', $user->id)
            ->where('payed', '>', 0)
            ->where('shipped', '<', 1)
            ->get();//2は「発送してください」．
        }elseif($request->input('selectStatus')==3){
            $dealingStatuses = Dealing_status::where('seller_id', '=', $user->id)
            ->where('payed', '>', 0)
            ->where('shipped', '>', 0)
            ->where('evaluated', '<', 1)
            ->get();//3は「出品者の評価をお待ちください」．
        }elseif($request->input('selectStatus')==4){
            $dealingStatuses = Dealing_status::where('seller_id', '=', $user->id)
            ->where('payed', '>', 0)
            ->where('shipped', '>', 0)
            ->where('evaluated', '>', 0)
            ->where('evaluated', '<', 2)
            ->get();//4は「出品者の評価をしてください」．
        }

        return view('user.userDealingSell', [
        'user' => $user,
        'dealingStatuses' => $dealingStatuses
        ]);
    }

    public function userDealingEnd(Request $request)
    {
        $user = User::find($request->id);

        if($request->input('selectDealing') == 0){
            $type = "all";

            $goodBuy = count(Evaluation::where('buyer_id', '=', $user->id)
            ->where('buyerEvaluation', '<', 1)->get());
            $goodSell = count(Evaluation::where('seller_id', '=', $user->id)
            ->where('sellerEvaluation', '<', 1)->get());

            $badBuy = count(Evaluation::where('buyer_id', '=', $user->id)
            ->where('buyerEvaluation', '>', 0)->get());

            $badSell = count(Evaluation::where('seller_id', '=', $user->id)
            ->where('sellerEvaluation', '>', 0)->get());

            $good = $goodBuy + $goodSell;
            $bad = $badBuy + $badSell;

            $ends = Evaluation::where('buyer_id', '=', $user->id)
            ->orWhere('seller_id', '=', $user->id)
            ->get();

        }elseif($request->input('selectDealing') == 1){
            $type = "buy";
            $good = count(Evaluation::where('buyer_id', '=', $user->id)
            ->where('buyerEvaluation', '<', 1)->get());
            
            $bad = count(Evaluation::where('buyer_id', '=', $user->id)
            ->where('buyerEvaluation', '>', 0)->get());

            $ends = Evaluation::where('buyer_id', '=', $user->id)->get();

        }elseif($request->input('selectDealing') == 2){
            $type = "sell";
            $good = count(Evaluation::where('seller_id', '=', $user->id)
            ->where('sellerEvaluation', '<', 1)->get());

            $bad = count(Evaluation::where('seller_id', '=', $user->id)
            ->where('sellerEvaluation', '>', 0)->get());

            $ends = Evaluation::where('seller_id', '=', $user->id)->get();

        }

        return view('user.userDealingEnd', [
        'user' => $user,
        'type' => $type,
        'good' => $good,
        'bad' => $bad,
        'ends' => $ends
        ]);
    }

    public function userRegisteredItem(Request $request)
    {
        $user = User::find($request->id);
        if($request->input('selectStatus') == 0){
            $status = "all";
            $items = Item::where('user_id', '=', $user->id)->get();
        }elseif($request->input('selectStatus') == 1){
            $status = "selling";
            $items = Item::where('user_id', '=', $user->id)
            ->where('buyer_id', '=', 0)->get();
        }elseif($request->input('selectStatus') == 2){
            $status = "soldOut";
            $items = Item::where('user_id', '=', $user->id)
            ->where('buyer_id', '!=', 0)->get();
        }

        $goodBuy = count(Evaluation::where('buyer_id', '=', $user->id)
        ->where('buyerEvaluation', '<', 1)->get());
        $goodSell = count(Evaluation::where('seller_id', '=', $user->id)
        ->where('sellerEvaluation', '<', 1)->get());

        $badBuy = count(Evaluation::where('buyer_id', '=', $user->id)
        ->where('buyerEvaluation', '>', 0)->get());

        $badSell = count(Evaluation::where('seller_id', '=', $user->id)
        ->where('sellerEvaluation', '>', 0)->get());

        $good = $goodBuy + $goodSell;
        $bad = $badBuy + $badSell;

        return view('user.userRegisteredItem', [
            'user' => $user,
            'status' => $status,
            'items' => $items,
            'good' => $good,
            'bad' => $bad
        ]);
    }

    public function userPage(Request $request){
        $user = Auth::id();
        
        //お問い合わせ
        $inquiry = Inquiry::where('inquiryTo_id', '=', $user)
        ->where('kidoku', '<', 1)
        ->get();

        //コメントした商品
        $commentedItems = Item_comment::where('user_id', '!=', $user)
        ->where('commentTo_id', '=', $user)
        ->where('kidoku', '<', 1)
        ->where('commentDelete', '<', 1)
        ->where('buyed', '<', 1)
        ->get()->unique('item_id'); //「->unique('item_id')」で重複を防止する．

        //コメントが来た商品
        $commentedItemsByWatcher = Item_comment::where('user_id', '=', $user)
        ->where('watcher_id', '!=', $user)
        ->where('kidoku', '<', 1)
        ->where('commentDelete', '<', 1)
        ->where('buyed', '<', 1)
        ->get()->unique('item_id'); //「->unique('item_id')」で重複を防止する．
        
        //「いいね」した商品
        $goodItems = Item_good::where('watcher_id', '=', $user)
        ->where('buyed', '<', 1)
        ->get();

        //「いいね」された商品
        $goodItemsByWatcher = Item_good::where('watcher_id', '!=', $user)
        ->where('user_id', '=', $user)
        ->where('buyed', '<', 1)
        ->get();

        //取引中の商品（購入）
        $dealingStatusBuy = Dealing_status::where('buyer_id', '=', $user)
        ->where('evaluated', '<', 2)
        ->get();
        
            //取引中の商品（購入）のメッセージ
            $dealingStatusBuyMessage = Dealing_message::where('buyer_id', '=', $user)
            ->where('kidoku', '<', 1)
            ->where('messageDelete', '<', 1)
            ->get();

        //取引中の商品（販売）
        $dealingStatusSell = Dealing_status::where('seller_id', '=', $user)
        ->where('evaluated', '<', 2)
        ->get();

            //取引中の商品（販売）のメッセージ
            $dealingStatusSellMessage = Dealing_message::where('seller_id', '=', $user)
            ->where('kidoku', '<', 1)
            ->where('messageDelete', '<', 1)
            ->get();

        return view('user.userPage', [
            'inquiry' => count($inquiry),
            'commentedItems' => count($commentedItems),
            'commentedItemsByWatcher' => count($commentedItemsByWatcher),
            'goodItems' => count($goodItems),
            'goodItemsByWatcher' => count($goodItemsByWatcher),
            'dealingStatusBuy' => count($dealingStatusBuy),
            'dealingStatusBuyMessage' => count($dealingStatusBuyMessage),
            'dealingStatusSell' => count($dealingStatusSell),
            'dealingStatusSellMessage' => count($dealingStatusSellMessage)
        ]);
    }

    public function userIndex(){ //管理ユーザー用
        $superUser = User::where('role_id', '=', 1)->first();
        if(Auth::id() == $superUser->id){
            $users = User::where('id', '!=', $superUser->id)->get();
            foreach($users as $user){ //出品した商品の数を計算する．
                $user->items = count(Item::where('user_id', '=', $user->id)
                ->where('buyer_id', '<', 1)
                ->get());
                $user->save();
            }
            
            return view('user.admin.userIndex', ['users' => $users]);
        }else{
            return redirect('/');
        }

    }

    public function userInquiryGet(Request $request){
        $superUser = User::where('role_id', '=', 1)->first();

        if(Auth::id() == $superUser->id){
            $user = User::find($request->id);
        }else{
            $user = Auth::user();
        }

        $inquiries = Inquiry::where('user_id', '=', $user->id)
        ->orWhere('inquiryTo_id', '=', $user->id)
        ->get();

        if(count($inquiries) > 0){ //既読にする
            if(Auth::id() == $superUser->id){
                //midokusは未読sである．
                $midokus = Inquiry::where('user_id', '=', $user->id)
                ->where('inquiryTo_id', '=', $superUser->id)
                ->where('kidoku', '<', 1)->get();

                foreach($midokus as $midok){
                    $midok->kidoku = 1;
                    $midok->save();
                }

            }elseif(Auth::id() == $user->id){
                $midokus = Inquiry::where('user_id', '=', $superUser->id)
                ->where('inquiryTo_id', '=', $user->id)
                ->where('kidoku', '<', 1)->get();
                
                foreach($midokus as $midok){
                    $midok->kidoku = 1;
                    $midok->save();
                }
            }
        }

        return view('user.admin.userInquiry', ['user' => $user, 'superUser' => $superUser, 'inquiries' => $inquiries]);
    }

    public function userInquiryPost(Request $request){
        $inquiry = new Inquiry;
        $form = $request->all();
        $inquiry->fill($form);

        //下段で$inquiryの要素を以下の変数の値に更新する．
        $userId = Auth::id();
        $superUser = User::where('role_id', '=', 1)->first();
        $inquirer = User::find($request->id);
        
        //$Messageの要素を上段の変数の値へ更新する．
        if($userId == $superUser->id){
            $inquiry->inquiryTo_id = $inquirer->id;
        }elseif($userId == $inquirer->id){
            $inquiry->inquiryTo_id = $superUser->id;
        }
        $inquiry->user_id = $userId; 
        $inquiry->kidoku = 0;
        $inquiry->save();

            
            //メール送信
            if(Auth::id() == $inquirer->id){ //superUserに対してメールを送信する
                Mail::send('mail.inquiryMailSuperUser', [
                    "superUser" => $superUser,
                    "inquirer" => $inquirer,
                    "inquiry" => $inquiry,
                ], 
                function($message) use($superUser, $inquirer) { //無名関数に変数を渡すには，後ろにuse ($変数)と記載する．

                    $message
                    ->to($superUser->email)
                    ->subject($inquirer->nickName . "様からお問い合わせがあります．");
                });
            }elseif(Auth::id() == $superUser->id){ //inquirerに対してメールを送信する
                Mail::send('mail.inquiryMailInquirer', [
                    "superUser" => $superUser,
                    "inquirer" => $inquirer,
                    "inquiry" => $inquiry,
                ], 
                function($message) use($inquirer) { //無名関数に変数を渡すには，後ろにuse ($変数)と記載する．

                    $message
                    ->to($inquirer->email)
                    ->subject("お問い合わせの回答がありました．");
                });

            }

        if($inquiry->user_id == $superUser->id){
            return redirect()->route('userInquiryGet', ['id' => $inquirer->id]);
        }else{
            return redirect()->route('userInquiryGet', ['id' => $userId]);
        }
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Category;
use App\Item;
use App\User;
use App\Item_comment;
use App\Item_good;
use App\Dealing_status;
use App\Evaluation;

use Illuminate\Support\Facades\Mail;

class itemController extends Controller
{
    public function itemIndex(Request $request)
    {
        $items = Item::all();
        $categories = Category::all();
        
        if($request->input('order') == 0){
            $items = Item::where('buyer_id', '<', 1)->get();
        }elseif($request->input('order') == 1){
            $items = Item::where('buyer_id', '>', 0)->get();
        }
        
        return view('item.itemIndex', ['items' => $items, 'categories' => $categories]);
    }

    public function itemRegisterGet() //出品メソッドget用 Route::get('/itemRegister','mainController@itemRegist')->name('itemRegist')
    {   
        $categories = Category::get();

        return view('item.itemRegister', ['categories' => $categories]); //['○○○.blade.php側で用いる変数名' => $○○○.blade.php側へ渡す変数]
    }

    public function itemRegisterPost(Request $request) //出品メソッドpost用 Route::post('/itemRegister','mainController@itemRegister')->name('itemRegister')
    {   
        $this->validate($request, Item::$rules);

        //$itemに$formを挿入する．
        $item = new Item;
        $form = $request->all();
        $item->fill($form);

        //$itemの要素を更新（更新される値は条件による）．
        if(isset($item->image)){
            $path = $request->file('image')->store('public/image');
            $item->image = basename($path);
        }else{
            $item->image = null;
        }

        //$itemの要素を更新．
        $item->user_id = Auth::id();
        $item->buyer_id = 0;

        $item->save();
        
        return redirect('/');
    }

    public function itemDetail(Request $request)
    {   
        $itemDetail = Item::find($request->id);
        $category = Category::find($itemDetail->category_id); //該当する商品のカテゴリー名を表示
        $comments = Item_comment::where('item_id','=', $itemDetail->id)->get(); //該当する商品の商品のコメントを表示
        $watchers = Item_comment::where('item_id','=', $itemDetail->id)
        ->where('watcher_id', '!=', $itemDetail->user_id)->get()->unique('watcher_id');
        $goods = Item_good::where('item_id','=', $itemDetail->id)->get(); //該当する商品の「いいね」の情報を取得
        $goodCount = Item_good::where('item_id', '=', $itemDetail->id)
        ->where('watcher_id', '=', Auth::id())->get(); //該当する商品をユーザーが「いいね」しているかどうかを判定する(count($goodCount)が1であれば「いいね」している)．
        
        $dealingStatus = Dealing_status::where('item_id', '=', $itemDetail->id)
        ->where('seller_id', '=', $itemDetail->user_id)
        ->where('buyer_id', '=', $itemDetail->buyer_id)
        ->first(); //該当する商品が購入された場合，取引画面へ遷移するために，dealing_statusesテーブルにおける当該取引のIDを取得する変数．

        $goodBuy = count(Evaluation::where('buyer_id', '=', $itemDetail->user_id)
        ->where('buyerEvaluation', '<', 1)->get());
        $goodSell = count(Evaluation::where('seller_id', '=', $itemDetail->user_id)
        ->where('sellerEvaluation', '<', 1)->get());

        $badBuy = count(Evaluation::where('buyer_id', '=', $itemDetail->user_id)
        ->where('buyerEvaluation', '>', 0)->get());

        $badSell = count(Evaluation::where('seller_id', '=', $itemDetail->user_id)
        ->where('sellerEvaluation', '>', 0)->get());

        $goodDealing = $goodBuy + $goodSell;
        $badDealing = $badBuy + $badSell;


        return view('item.itemDetail',
        ['itemDetail' => $itemDetail,
         'category' => $category,
         'comments' => $comments,
         'watchers' => $watchers,
         'goods' => $goods,
         'goodCount' => $goodCount,
         'dealingStatus' => $dealingStatus,
         'goodDealing' => $goodDealing,
         'badDealing' => $badDealing
        ]);
    }

    public function itemDelete(Request $request){
        $itemDetail = Item::find($request->id);
        
        Item_comment::where('item_id', '=', $itemDetail->id)->delete();

        $itemGood = Item_good::where('item_id', '=', $itemDetail->id)->get();
        if(!is_null($itemGood)){
            Item_good::where('item_id', '=', $itemDetail->id)->delete();
        }

        $itemDetail->delete();
        
        return redirect('/');
    }

    public function itemDetailGood(Request $request) //いいね用のメソッド      
    {  
        $itemDetail = Item::find($request->id);//最後のreturn redirect()->route('itemDetail', ['id' => $itemDetail->id]);用
        /*
        以下$goodsIndividualは，Item_goodsテーブルのitem_idカラムの値とitemsテーブルのidが一致し，
        且つItem_goodsテーブルのwatcherカラムの値とitemsテーブルのuser_idカラムの値が一致するデータを取得し，
        そのデータの個数を計算する．
        */
        $goodsIndividual = count(Item_good::where('item_id', '=', $itemDetail->id)
        ->where('watcher_id','=', Auth::id())->get());

        if($goodsIndividual < 1){

            $itemGood = new Item_good;

            //下段で$itemGoodの要素を以下の変数の値に更新する．
            $userId = Auth::id();

            //$itemGoodの要素を上段の変数の値へ更新する．
            $itemGood->watcher_id = $userId;
            $itemGood->user_id = $itemDetail->user_id;
            $itemGood->item_id = $itemDetail->id;

            $itemGood->save();

            return redirect()->route('itemDetail', ['id' => $itemDetail->id]);
        }else{
            $itemGood = Item_good::where('item_id', '=', $itemDetail->id)->get();
            if(!is_null($itemGood)){
            Item_good::where('item_id', '=', $itemDetail->id)->delete();
            }
            return redirect()->route('itemDetail', ['id' => $itemDetail->id]);
        }
    }

    public function itemDetailComment(Request $request) //商品のコメントを記載するためのメソッド．
    {   
        $itemDetail = Item::find($request->id);//$commentへの代入 & 最後のreturn redirect()->route('itemDetail', ['id' => $itemDetail->id]);用

        //$commentに$formを挿入する．
        $comment = new Item_comment;
        $form = $request->all();
        $comment->fill($form);

        //下段で$commentsの要素を以下の変数の値に更新する．
        $item = Item::find($request->id);
        $watcher = Auth::id();

        //$commentの要素を上段の変数の値へ更新する．
        $comment->item_id = $item->id;
        $comment->user_id = $itemDetail->user_id;
        $comment->watcher_id = $watcher;
        if($itemDetail->user_id != Auth::id()){
            $comment->commentTo_id = 0;
        }
        $comment->commentDelete = 0;
        $comment->buyed = 0;

        $comment->save();
        
        //メール送信
        $seller = User::find($itemDetail->user_id);
        $sellerMailAddress = $seller->email;

        $watcher = User::find($comment->watcher_id);
        $watcherMailAddress = $watcher->email;

        $commentTo = User::find($comment->commentTo_id);

            //メール送信
            if(Auth::id() != $itemDetail->user_id){ //sellerに対してメールを送信する
                Mail::send('mail.commentMailSeller', [
                    "watcher" => $watcher,
                    "seller" => $seller,
                    "itemDetail" => $itemDetail,
                    "comment" => $comment 
                ], 
                function($message) use($itemDetail, $seller, $watcher) { //無名関数に変数を渡すには，後ろにuse ($変数)と記載する．

                    $message
                    ->to($seller->email)
                    ->subject("商品「" . $itemDetail->name . "」について" . 
                    $watcher->nickName . "様からコメントが届いています．");
                });
            }elseif(Auth::id() == $itemDetail->user_id){ //watcherに対してメールを送信する
                Mail::send('mail.commentMailWatcher', [
                    "commentTo" => $commentTo,
                    "seller" => $seller,
                    "itemDetail" => $itemDetail,
                    "comment" => $comment 
                ], 
                function($message) use($itemDetail, $seller, $commentTo) { //無名関数に変数を渡すには，後ろにuse ($変数)と記載する．

                    $message
                    ->to($commentTo->email)
                    ->subject("商品「" . $itemDetail->name . "」について" . 
                    $seller->nickName . "様からコメントが届いています．");
                });

            }

        return redirect()->route('itemDetail', ['id' => $itemDetail->id]);
    }

    public function itemCommentDelete(Request $request){
        $comment = Item_comment::find($request->id);
        $comment->commentDelete = 1;
        $comment->save();
        
        return redirect()->route('itemDetail', ['id' => $comment->item_id]);
    }

    public function itemEditGet(Request $request)
    {   
        $item = Item::find($request->id);
        $selectedCategory = Category::find($item->category_id);
        $categories = Category::where('id','!=', $selectedCategory->id)->get(); //$selectedCategoryで取得したデータ以外のデータ取得する
        $user = User::find($item->user_id);
        return view('item.itemEdit', ['item' => $item, 'selectedCategory' => $selectedCategory, 'categories' => $categories, 'user' => $user]);
    }

    public function itemEditPost(Request $request)
    {   
        $this->validate($request, Item::$rules);

        $item = Item::find($request->id); 

        $form = $request->all(); //以下$requestのインスタンス($form)の要素は「$form['要素名']」として取得する．
        if ($request->remove == 'true') {
            $form['image'] = null;
        } elseif ($request->file('image')) {
            $path = $request->file('image')->store('public/image');
            $form['image'] = basename($path);
        } else {
            $form['image'] = $item->image;
        }

        $item->fill($form)->save();
        
        return redirect()->route('itemDetail', ['id' => $item->id]);
    }

    public function itemSearch(Request $request)
    {
        $name = $request->input('name');
        $condition = $request->input('condition');
        $status = $request->input('status');
        $priceMin = $request->input('priceMin');
        $priceMax = $request->input('priceMax');
        $query = Item::query();

        if(isset($name)) {
            $query->where('name', 'like', '%'.$name.'%');
        }
        if(isset($condition)) {
            $query->where('condition', '=', intval($condition));
        }

        if(isset($status)) {
            if(intval($status) < 1){ //intvalで文字列を数値化
                $query->where('buyer', '<', 1);
                
            }else{
                $query->where('buyer', '>', 0);
            }
        }

        if(isset($priceMin)) {
            $query->where('price', '>', $priceMin - 1);
        }

        if(isset($priceMax)) {
            $query->where('price', '<', $priceMax + 1);
        }

        if(!isset($name) && !isset($condition) && !isset($status) && !isset($priceMin) && !isset($priceMax))
        {
            $items = null;
        }else{
            $items = $query->get();
        }
        
        return view('item.itemSearch', ['items' => $items]);
    }

    public function category(){
        $topCategories = Category::where('parentCategory_id', '=', 0)
        ->orWhere('parentCategory_id', '=', null)->get();
        $underCategories = Category::where('parentCategory_id', '!=', 0)
        ->orWhere('parentCategory_id', '!=', null)->get();
    }
}
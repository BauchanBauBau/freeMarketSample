<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

//ホーム画面．
Route::get('/','itemController@itemIndex')->name('itemIndex');

//商品検索
Route::get('itemSearch', 'itemController@itemSearch')->name('itemSearch');

//出品
Route::get('/itemRegister','itemController@itemRegisterGet')->name('itemRegisterGet')->middleware('verified');
Route::post('/itemRegister','itemController@itemRegisterPost')->name('itemRegisterPost')->middleware('verified');

//個別商品ページ
Route::get('/itemDetail/{id}', 'itemController@itemDetail')->name('itemDetail');
Route::post('/itemDetail/{id}', 'itemController@itemDetailComment')->name('itemDetailComment'); //itemDetailから商品のコメントを登録する．
Route::post('/itemDetail/good/{id}', 'itemController@itemDetailGood')->name('itemDetailGood'); //itemDetailで「いいね」を登録する．

//商品削除
Route::post('/itemDelete/{id}', 'itemController@itemDelete')->name('itemDelete')->middleware('verified');

//コメント・メッセージ削除
Route::post('/itemCommentDelete/{id}', 'itemController@itemCommentDelete')->name('itemCommentDelete')->middleware('verified');
Route::post('/dealingMessageDelete/{id}', 'dealingSellerController@dealingMessageDelete')->name('dealingMessageDelete')->middleware('verified');

//商品編集ページ
Route::get('/itemEdit/{id}', 'itemController@itemEditGet')->name('itemEditGet')->middleware('verified');
Route::post('/itemEdit/{id}', 'itemController@itemEditPost')->name('itemEditPost')->middleware('verified');

//ユーザー情報編集ページ
Route::get('/userInfo/{id}', 'userController@userInfo')->name('userInfo')->middleware('verified');
Route::get('/userEdit/{id}', 'userController@userEditGet')->name('userEditGet')->middleware('verified');
Route::post('/userEdit/{id}', 'userController@userEditPost')->name('userEditPost')->middleware('verified');
Route::post('/userDelelte/{id}', 'userController@userDelete')->name('userDelete')->middleware('verified');
Route::get('/userPage/{id}', 'userController@userPage')->name('userPage')->middleware('verified');

//ユーザーが商品にコメントを登録したり，いいねをした商品へのリンクを表示するページ
Route::get('/userCommentedItem/{id}', 'userController@userCommentedItem')->name('userCommentedItem')->middleware('verified');
Route::get('/userCommentedItemByWatcher/{id}', 'userController@userCommentedItemByWatcher')->name('userCommentedItemByWatcher')->middleware('verified');
Route::get('/userGood/{id}', 'userController@userGood')->name('userGood')->middleware('verified');
Route::get('/userGoodByWatcher/{id}', 'userController@userGoodByWatcher')->name('userGoodByWatcher')->middleware('verified');
Route::get('/userDealingBuy/{id}', 'userController@userDealingBuy')->name('userDealingBuy')->middleware('verified');
Route::get('/userDealingSell/{id}', 'userController@userDealingSell')->name('userDealingSell')->middleware('verified');
Route::get('/userDealingEnd/{id}', 'userController@userDealingEnd')->name('userDealingEnd')->middleware('verified');
Route::get('/userDealingEnd/{id}', 'userController@userDealingEnd')->name('userDealingEnd')->middleware('verified');
Route::get('/userRegisteredItem/{id}', 'userController@userRegisteredItem')->name('userRegisteredItem');

//購入用メソッド
Route::post('/buy/{id}', 'dealingBuyerController@buy')->name('buy')->middleware('verified');

//取引状況
Route::get('/statusBuyer/{id}', 'dealingBuyerController@statusBuyer')->name('statusBuyer')->middleware('verified');
Route::get('/statusSeller/{id}', 'dealingSellerController@statusSeller')->name('statusSeller')->middleware('verified');
Route::post('/statusBuyerPayed/{id}', 'dealingBuyerController@statusBuyerPayed')->name('statusBuyerPaid')->middleware('verified');
Route::post('/statusSellerShipped/{id}', 'dealingSellerController@statusSellerShipped')->name('statusSellerShipped')->middleware('verified');
Route::post('/statusSkip/{id}', 'dealingBuyerController@statusSkip')->name('statusSkip')->middleware('verified');//取引スキップ
//（注意）取引メッセージはコード量の少ないdealingSellerControllerへ記載している．
Route::post('/dealingMessage/{id}', 'dealingSellerController@dealingMessage')->name('dealingMessage')->middleware('verified');//取引メッセージ

//出品者・購入者の評価
Route::post('/buyerEvaluation/{id}', 'dealingEvaluationController@buyerEvaluation')->name('buyerEvaluation')->middleware('verified');
Route::post('/sellerEvaluation/{id}', 'dealingEvaluationController@sellerEvaluation')->name('sellerEvaluation')->middleware('verified');

//ユーザー一覧（管理者用）
Route::get('/userIndex', 'userController@userIndex')->name('userIndex')->middleware('verified');

//お問い合わせ用
Route::get('/userInquiry/{id}', 'userController@userInquiryGet')->name('userInquiryGet')->middleware('verified');
Route::post('/userInquiry/{id}', 'userController@userInquiryPost')->name('userInquiryPost')->middleware('auth');

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

@extends('layouts.top')

<link rel="stylesheet" href="{{ asset('css/user/userDealingEnd.css') }}">
<title>終了した取引</title>

@section('content')

@if(count($ends) < 1)
    <div class="select">
        <h3><a href="{{ action('userController@userRegisteredItem', ['id' => $user->id]) }}">{{ $user->nickName }}</a>様の終了した取引
            @if($type == 0)（全て）
            @elseif($type == 1)（購入）
            @elseif($type == 2)（販売）
            @endif
            【{{ count($ends) }}件】
        </h3>
        <h5>該当する取引はありません．</h5>
        <form action="{{ action('userController@userDealingEnd', ['id' => $user]) }}">
            <select class="form-control col-md-4 offset-md-4" name="selectDealing" id="selectDealing">
                <option value="0" @if($type == 0) selected @endif>全て</option>
                <option value="1" @if($type == 1) selected @endif>購入</option>
                <option value="2" @if($type == 2) selected @endif>販売</option>
            </select>
            <button type="submit" class="btn btn-primary col-md-4 offset-md-4">表示</button>
        </form>
    </div>
@else
    <div class="select">
        <h3><a href="{{ action('userController@userRegisteredItem', ['id' => $user->id]) }}">{{ $user->nickName }}</a>様の終了した取引
            @if($type == 0)（全て）
            @elseif($type == 1)（購入）
            @elseif($type == 2)（販売）
            @endif
            【{{ count($ends) }}件】
        </h3>
        <h5>
            （
            評価
            【良い：{{ $good }}件（{{ round($good / ($good + $bad) * 100, 0) }}％）】
            【悪い：{{ $bad }}件（{{ round($bad / ($good + $bad) * 100, 0) }}％）】
            ）
        </h5>
        <form action="{{ action('userController@userDealingEnd', ['id' => $user]) }}">
            <select class="form-control col-md-4 offset-md-4" name="selectDealing" id="selectDealing">
                <option value="0">全て</option>
                <option value="1">購入</option>
                <option value="2">販売</option>
            </select>
            <button type="submit" class="btn btn-primary col-md-4 offset-md-4">表示</button>
        </form>
        @foreach($ends as $end)
            <div class="dealings row no-gutters">
                <a div class="col-md-3" href="{{ action('itemController@itemDetail', ['id' => $end->item->id]) }}">
                    <table id="table1" class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>{{ number_format($end->item->price) }}円
                                    @if($end->item->shippingOption == 0)（送料込み）
                                    @else（着払い）
                                    @endif</td>
                            </tr>
                            <tr>
                                <td>
                                    @if($end->item->condition == 0)新品・未使用
                                    @elseif($end->item->condition == 1)新品・未使用に近い
                                    @elseif($end->item->condition == 2)目立った傷や汚れ無し
                                    @elseif($end->item->condition == 3)傷や汚れ有り
                                    @elseif($end->item->condition == 4)全体的に状態が悪い
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ $end->item->days }}日以内に{{ $end->item->userAddress }}から発送
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </a div>
                @if($type == 0)
                    @if(isset($end->userSeller->id))
                        @if($end->seller_id != $user->id && $end->seller_id != $user->id)
                            <a div class="col-md-9" href="{{ action('userController@userRegisteredItem', ['id' => $end->userSeller->id]) }}">
                        @endif
                    @endif
                    @if(isset($end->userBuyer->id))
                        @if($end->buyer_id != $user->id && $end->buyer_id != $user->id )
                            <a div class="col-md-9" href="{{ action('userController@userRegisteredItem', ['id' => $end->userBuyer->id]) }}">
                        @endif
                    @endif
                            <table id="table2" class="table table-bordered col-md-9">
                                <tbody>
                                    <tr>
                                        <td>
                                            @if($end->buyer_id == $user->id)出品者
                                            @elseif($end->seller_id == $user->id)購入者
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($end->userSeller->id))
                                                @if($end->buyer_id == $user->id){{ $end->userSeller->nickName }} 様
                                                @endif    
                                            @else
                                                出品したユーザー情報は削除されました．
                                            @endif
                                            @if(isset($end->userBuyer->id))
                                                @if($end->seller_id == $user->id){{ $end->userBuyer->nickName }} 様
                                                @endif    
                                            @else
                                                出品したユーザー情報は削除されました．
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            @if($end->buyer_id == $user->id)出品者からの評価
                                            @elseif($end->seller_id == $user->id)購入者からの評価
                                            @endif
                                        </td>
                                        <td>@if($end->buyer_id == $user->id)
                                                @if($end->buyerEvaluation == 0 )良い
                                                @else 悪い
                                                @endif
                                            @elseif($end->seller_id == $user->id)
                                                @if($end->sellerEvaluation == 0 )良い
                                                @else 悪い
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            @if($end->buyer_id == $user->id)出品者からのコメント
                                            @elseif($end->seller_id == $user->id)購入者からのコメント
                                            @endif
                                        </td>
                                        <td>
                                            @if($end->buyer_id == $user->id)
                                                @if(isset($end->buyerComment))
                                                    {{ $end->buyerComment }}
                                                @else
                                                    コメントはありません．
                                                @endif
                                            @elseif($end->seller_id == $user->id)
                                                @if(isset($end->sellerComment))
                                                    {{ $end->sellerComment }}
                                                @else
                                                    コメントはありません．
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </a div>
                @else
                    @if($type == 1 && isset($end->userSeller->id))
                        <a div class="col-md-9" href="{{ action('userController@userRegisteredItem', ['id' => $end->userSeller->id]) }}">
                    @endif        
                    @if($type == 2 && isset($end->userBuyer->id))
                        <a div class="col-md-9" href="{{ action('userController@userRegisteredItem', ['id' => $end->userBuyer->id]) }}">
                    @endif
                        <table id="table2" class="table table-bordered col-md-9">
                            <tbody>
                                <tr>
                                    <td>
                                        @if($type == 1)出品者
                                        @elseif($type == 2)購入者
                                        @endif
                                    </td>
                                    <td>
                                        @if($type == 1 && isset($end->userSeller->id))
                                            {{ $end->userSeller->nickName }} 様
                                        @elseif($type == 2 && isset($end->userBuyer->id))
                                            {{ $end->userBuyer->nickName }} 様
                                        @else
                                            出品したユーザー情報は削除されました．
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        @if($type == 1)出品者からの評価
                                        @elseif($type == 2)購入者からの評価
                                        @endif
                                    </td>
                                    <td>@if($type == 1)
                                            @if($end->buyerEvaluation == 0 )良い
                                            @else 悪い
                                            @endif
                                        @elseif($type == 2)
                                            @if($end->sellerEvaluation == 0 )良い
                                            @else 悪い
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        @if($type == 1)出品者からのコメント
                                        @elseif($type == 2)購入者からのコメント
                                        @endif
                                    </td>
                                    <td>
                                        @if($type == 1)
                                            @if(isset($end->buyerComment)){{ $end->buyerComment }}
                                            @else
                                                コメントはありません．                                            @endif
                                        @elseif($type == 2)
                                            @if($end->sellerComment){{ $end->sellerComment }}
                                            @else
                                                コメントはありません．
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </a div>
                @endif
            </div>
        @endforeach
    </div>
@endif
@endsection
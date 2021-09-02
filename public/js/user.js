//<script src="{{ asset('js/user.js') }}" defer></script>

/*
（参考）
https://into-the-program.com/javascript-get-address-zipcode-search-api/
（注意）
以下のコードも使用するhtmlに記載する．
<script src="https://cdn.jsdelivr.net/npm/fetch-jsonp@1.1.3/build/fetch-jsonp.min.js"></script>
*/
let search = document.getElementById('search');
search.addEventListener('click', ()=>{
    let api = 'https://zipcloud.ibsnet.co.jp/api/search?zipcode=';
    let error = document.getElementById('error');
    let input = document.getElementById('postalCode');
    let address1 = document.getElementById('addressPref');
    let address2 = document.getElementById('addressCity');
    let address3 = document.getElementById('addressOther');
    let param = input.value.replace("-",""); //入力された郵便番号から「-」を削除
    let url = api + param;

    fetchJsonp(url, {
        timeout: 10000, //タイムアウト時間
    })
    .then((response)=>{
        error.textContent = ''; //HTML側のエラーメッセージ初期化
        return response.json();
    })
    .then((data)=>{
        if(data.status === 400){ //エラー時
            error.textContent = data.message;
        }else if(data.results === null){
            error.textContent = '郵便番号から住所が見つかりませんでした。';
        }else{
            address1.value = data.results[0].address1;
            address2.value = data.results[0].address2;
            address3.value = data.results[0].address3;
        }
    })
    .catch((ex)=>{ //例外処理
        console.log(ex);
    });
}, false);

//onClick="xxxAlert(event);return false;"
//削除確認画面
function deleteAlert(e){
    if(!window.confirm('削除しますか？')){
       return false;
    }
    document.submit();
 };

 //更新確認画面
function updateAlert(e){
    if(!window.confirm('更新しますか？')){
       return false;
    }
    document.submit();
 };

 //ユーザー登録確認画面（resources\views\auth\register.blade.php）
 function registerAlert(e){
    if(!window.confirm('ユーザー登録しますか？')){
       return false;
    }
    document.submit();
 };
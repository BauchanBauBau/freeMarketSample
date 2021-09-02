//<script src="{{ asset('js/item.js') }}" defer></script>

function change() {

  var parent = document.getElementById( "shippingOption" ) ;

  var child = document.getElementById("shippingMethod") ;

  if( parent.value > 0) {
    child.options[0].style.display="none"
    child.options[1].style.display="none"
    child.options[2].selected = true; 
  }else{
    child.options[0].style.display=""
    child.options[1].style.display=""
    child.options[2].style.display=""
    child.options[3].style.display=""
    child.options[4].style.display=""
    child.options[5].style.display=""
  }
}

function change2() {

  var parent = document.getElementById( "shippingOption" ) ;

  var child = document.getElementById("shippingMethod") ;

  if( parent.value > 0) {
    child.options[0].style.display="none"
    child.options[1].style.display="none"
    child.options[2].selected = false; 
  }else{
    child.options[0].style.display=""
    child.options[1].style.display=""
    child.options[2].style.display=""
    child.options[3].style.display=""
    child.options[4].style.display=""
    child.options[5].style.display=""
  }
}

//onClick="xxxAlert(event);return false;"
//購入確認画面
function buyAlert(e){
  if(!window.confirm('本当に購入しますか？')){
     return false;
  }
  document.submit();
};

//削除確認画面
function deleteAlert(e){
   if(!window.confirm('本当に削除しますか？')){
      return false;
   }
   document.submit();
};

//出品確認画面
function registerAlert(e){
  if(!window.confirm('出品しますか？')){
     return false;
  }
  document.submit();
};

//コメント・メッセージ投稿確認画面
function postAlert(e){
  if(!window.confirm('投稿しますか？')){
     return false;
  }
  document.submit();
};

//コメント・メッセージ投稿確認画面
function updateAlert(e){
  if(!window.confirm('更新しますか？')){
     return false;
  }
  document.submit();
};
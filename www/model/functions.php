<?php

//変数に関する情報をダンプする関数
function dd($var){
  var_dump($var);
  exit();
}

//リダイレクト用の関数
function redirect_to($url){
  header('Location: ' . $url);
  exit;
}

//htmlエスケープ用の関数
function h($s){
	return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

//グローバル変数GETからの情報を取得する関数
function get_get($name){
   //isset関数でGETの情報が入っていることを確認する
  if(isset($_GET[$name]) === true){
    //GETの情報を返す
    return $_GET[$name];
  };
  //GET情報が入っていなかった場合、nullを返している？
  return '';
}

//グローバル変数POSTからの情報を取得する関数
function get_post($name){
  //isset関数でPOSTの情報が入っていることを確認する
  if(isset($_POST[$name]) === true){
    //POSTの情報を返す
    return $_POST[$name];
  };
  //POST情報が入っていなかった場合、nullを返している？
  return '';
}

//グローバル変数FILESで受け取ったファイルを連粗配列にして返す関数
function get_file($name){
  //グローバル変数FILESで受け取ったファイルが入っているか確認する
  if(isset($_FILES[$name]) === true){
    //ファイルの名前を返す
    return $_FILES[$name];
  };
  //$_FILESを連想配列にして返す
  return array();
}

function get_session($name){
  if(isset($_SESSION[$name]) === true){
    return $_SESSION[$name];
  };
  return '';
}

function set_session($name, $value){
  $_SESSION[$name] = $value;
}

//$_SESSION['__errors'][]にエラーメッセージを格納する
function set_error($error){
  $_SESSION['__errors'][] = $error;
}

function get_errors(){
  $errors = get_session('__errors');
  if($errors === ''){
    return array();
  }
  set_session('__errors',  array());
  return $errors;
}

function has_error(){
  return isset($_SESSION['__errors']) && count($_SESSION['__errors']) !== 0;
}

//$_SESSION['__messages'][]の配列にメッセージを格納する
function set_message($message){
  $_SESSION['__messages'][] = $message;
}

function get_messages(){
  $messages = get_session('__messages');
  if($messages === ''){
    return array();
  }
  set_session('__messages',  array());
  return $messages;
}

function is_logined(){
  return get_session('user_id') !== '';
}

//
function get_upload_filename($file){
  if(is_valid_upload_image($file) === false){
    return '';
  }
  $mimetype = exif_imagetype($file['tmp_name']);
  $ext = PERMITTED_IMAGE_TYPES[$mimetype];
  return get_random_string() . '.' . $ext;
}

function get_random_string($length = 20){
  return substr(base_convert(hash('sha256', uniqid()), 16, 36), 0, $length);
}

function save_image($image, $filename){
  return move_uploaded_file($image['tmp_name'], IMAGE_DIR . $filename);
}

//
function delete_image($filename){
  //ファイルまたはディレクトリが存在するか調べる関数、
  if(file_exists(IMAGE_DIR . $filename) === true){
    unlink(IMAGE_DIR . $filename);
    return true;
  }
  return false;

}



function is_valid_length($string, $minimum_length, $maximum_length = PHP_INT_MAX){
  $length = mb_strlen($string);
  return ($minimum_length <= $length) && ($length <= $maximum_length);
}

function is_alphanumeric($string){
  return is_valid_format($string, REGEXP_ALPHANUMERIC);
}

function is_positive_integer($string){
  return is_valid_format($string, REGEXP_POSITIVE_INTEGER);
}

function is_valid_format($string, $format){
  return preg_match($format, $string) === 1;
}


function is_valid_upload_image($image){
  //POSTでアップロードされたファイルか調べる関数、一時フォルダに保存されたファイルがなにか間違っていた場合
  if(is_uploaded_file($image['tmp_name']) === false){
    //$_SESSION['__errors'][]にファイル形式が不正です。というエラーメッセージを格納する
    set_error('ファイル形式が不正です。');
    //falseを返す
    return false;
  }

  //画像ファイルかどうか拡張子を調べる関数、変数で出力する
  $mimetype = exif_imagetype($image['tmp_name']);
  //
  if( isset(PERMITTED_IMAGE_TYPES[$mimetype]) === false ){
    set_error('ファイル形式は' . implode('、', PERMITTED_IMAGE_TYPES) . 'のみ利用可能です。');
    //falseを返す
    return false;
  }
  //問題ない場合trueを返す
  return true;
}

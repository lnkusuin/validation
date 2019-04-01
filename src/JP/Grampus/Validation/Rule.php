<?php
namespace JP\Grampus\Validation;

/**
 * 検証ルールを定義する。
 * @author Vogaro Inc.
 *
 */
class Rule{

    /**
     * @var mixed 検証対象の値
     */
    protected $value = '';
    
    /**
     * @var array エラーメッセージ
     */
    protected $err = '';

    /**
     * @var Validator
     */
    protected $validator = '';
    
    /**
     * @var string タイトル
     */
    protected $title = '';

    /**
     * 
     * @param $value
     * @param $title
     * @param $validator
     */
    public function __construct($value, $title, $validator){
        $this->value = $value;
        $this->title = $title;
        $this->validator = $validator;
    }

    /**
     * 値か設定されていることを検証する。
     * nullか文字列なら空文字でないか配列なら要素があるかで判定する。
     * @param string $message
     * @return $this
     */
    public function required($message = 'が入力されていません。'){
                
        if (is_null($this->value)) {
            $this->throws($this->title . $message);
        } elseif (is_string($this->value) && trim($this->value) === '') {
            $this->throws($this->title . $message);
        } elseif (is_array($this->value) && count($this->value) < 1) {
            $this->throws($this->title . $message);
        }
        
        return $this;
    }
    

    /**
     * 値が数値のみであることを検証する(16進数, 10進数, 8進数, 2進数, 浮動少数, 整数)
     *　十進数表現かどうかの判断はRule#degitを使用すること。
     * @param string $message
     * @return Rule
     */
    public function numeric($message = 'の値が数値ではありません。'){
        if(!is_numeric($this->value)){
            $this->throws($this->title . $message);
        }

        return $this;
    }
    
    /**
     * 値が数値のみであることを検証する(10進数)
     * @param string $message
     * @return Rule
     */
    public function degit($message = 'の値が数値ではありません。'){
        if ( !preg_match("/^[0-9]+$/",$this->value) ) {
            $this->throws($this->title . $message);
        }
        
        return $this;
    }
    
    /**
    * 値がアルファベットのみであることを検証する
    * @param string $message
    * @return Rule 
    */
    public function alpha($message = 'の値が半角英字のみではありません。')
    {
        if ( !preg_match( "/^([a-z])+$/i", $this->value ) ) {
            $this->throws($this->title . $message);
        }

        return $this;
    }

    /**
    * 値がアルファベットと数値のみであることを検証する
    * @param string $message
    * @return Rule
    */
    public function alphaNumeric($message = 'の値が半角英数字のみではありません。')
    {
        if ( !preg_match( "/^([a-zA-Z0-9])+$/i", $this->value ) ) {
            $this->throws($this->title . $message);
        }

        return $this;
    }

    /**
     * 電話番号であることを検証する。
     * @param string $message
     * @return $this
     */
    public function tel($message = 'の書式が正しくありません。')
    {
        $pat = '/^[0-9]{2,4}-*[0-9]{2,4}-*[0-9]{3,4}$/i';
        if ( !preg_match( $pat, $this->value ) ) {
            $this->throws($this->title . $message);
        }

        return $this;
    }

    /**
    * メールアドレスであることを確認する
    *
    * @param string $message
    * @return Rule
    */
    public function email($message = 'の書式が正しくありません。')
    {
        $pat = "/^[0-9a-zA-Z_\.\-]+@[0-9a-zA-Z][0-9a-zA-Z\.\-]+$/";
        if ( !preg_match( $pat, $this->value ) ) {
            $this->throws($this->title . $message);
        }

        return $this;
    }

    /**
    * IPアドレスであることを検証する
    *
    * @param string $message
    * @return Rule
    */
    public function ip($message = 'の値は正しいIPではありません。')
    {
        if (!filter_var($this->value, FILTER_VALIDATE_IP)) {
            $this->throws($this->title . $message);
        }

        return $this;
    }

    /**
    * urlであることを検証する
    *
    * @param string $message
    * @return Rule
    */
    public function url($message = 'の値は正しいURLではありません。')
    {
        if (!filter_var($this->value, FILTER_VALIDATE_URL)) {
            $this->throws($this->title . $message);
        }

        return $this;
    }

    /**
    * 値が日付であることを検証する
    * @param string $message
    * @return Rule
    */
    public function dateFormat($message = '日付の書式が正しくありません。')
    {
        try {
            new \DateTime($this->value);
        } catch (Exception $e) {
            $this->throws($this->title . $message);
        }

        return $this;
    }

    /**
     *
     * @param $init
     * @param string $message
     * @return $this
     */
    public function placeHolder($init, $message = 'に使用できない文字が含まれています。')
    {
        if ($this->value == $init) {
            $this->throws($this->title . $message);
        }

        return $this;
    }

    /**
    * 値が全角文字であることを検証する
    *
    * @param string $message
    * @return Rule
    */
    public function zenkaku($message = 'に全角文字以外が含まれています。')
    {
        mb_regex_encoding( "UTF-8" );
        if ( !preg_match( "/^[^a-zA-Z0-9 ]+$/", $this->value ) ) {
            $this->throws($this->title . $message);
        }

        return $this;
    }

    /**
    * 値が全角カタカナであることを確認する
    *
    * @param string $message
    * @return Rule
    */
    public function katakana($message = 'に全角カタカナ以外が含まれています。')
    {
        mb_regex_encoding( "UTF-8" );
        if ( $this->value != '' && !preg_match( "/^[ァ-ヾ]+$/u", $this->value ) ) {
            $this->throws($this->title . $message);
        }

        return $this;
    }

    /**
    * 値が全角ひらがなであることを確認する
    *
    * @param string $message
    * @return Rule
    */
    public function hiragana($message = 'にひらがな以外が含まれています。')
    {
        mb_regex_encoding( "UTF-8" );
        if ( !preg_match( "/^[ぁ-ゞ]+$/u", $this->value ) ) {
            $this->throws($this->title . $message);
        }

        return $this;
    }

    /**
     * 長さ
     */
    public function length($operator, $len, $message = '', $encoding = 'UTF-8'){

        $operator_list = function($value, $len){
            return array(
                '>' => function() use ($value, $len){
                    return $value > $len;
                },
                '>=' => function() use ($value, $len){
                    var_dump($value);
                    return $value >= $len;
                },
                '==' => function() use ($value, $len){
                    return $value == $len;
                },
                '<' => function() use ($value, $len){
                    return $value < $len;
                },
                '<=' => function() use ($value, $len){
                    return $value <= $len;
                },
            );
        };

        $fn = $operator_list(mb_strlen($this->value, $encoding), $len);
        if(array_key_exists($operator, $fn)){
            if($fn[$operator]() == true){
                $this->throws($this->title . $message);
            }
        }
        
        return $this;
    }

    /**
     * デバイストークンチェック(IOS)
     * @param string $message
     * @return boolean true 正常なトークン値 | false 不正なトークン値
     */
    public function tokenIOS($message = 'IOSのデバイストークンが不正です。')
    {
        if (!preg_match('~^[a-f0-9]{64}$~i', $this->value)) {
            $this->throws($message);
        }

        return $this;
    }

    /**
     * デバイストークンチェック (Android)
     * @param string $message
     * @return boolean true 正常なトークン値 | false 不正なトークン値
     */
    public function tokenAndroid($message = 'Androidのデバイストークンが不正です。')
    {
        if(!preg_match('~^[0-9a-zA-Z_-]{1,205}$~i', $this->value)) {
            $this->throws($message);
        }

        return $this;
    }

    /**
     * カスタム検証
     * @param $callback
     * @param string $message
     * @return $this
     */
    public function custom($callback, $message){
        if($callback($this->value) == true){
            $this->throws($message);
        }

        return $this;
    }

    /**
     * ルール定義の終了
     */
    public function end(){
        return $this->validator;
    }
    
    /**
     * エラーメッセージの設定
     * @param string $message 失敗した理由をシステムに伝えるメッセージ
     * @throws RuleInValidException
     */
    protected function throws($message){
        $this->err = $message;
    }
    
    /**
     * エラーメッセージの取得
     * @return array:
     */
    public function getErr(){
        return $this->err;
    }

}

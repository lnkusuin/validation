<?php
namespace JP\Grampus\Validation;

class Validator{
    
	/**
	 * @var array 検証エラーメッセージ
	 */
    protected $err = array();
    
    /**
     * @var array<Rule> 検証ルール定義インスタンス
     */
    protected $rules = array();
    
    /**
     * Validatorクラスはmakeメソッドによる起動にする。
     * @return JP\Grampus\Validation\Validator
     */
    public static function make()
    {
		return new self;
    }
    
    /**
     * インスタンスの生成はmakeメソッドを使用する。
     */
    protected function __construct(){}
    
    /**
     * 検証ルールの定義を開始する。
     * @param unknown $value
     * @param unknown $message
     * @return JP\Grampus\Validation\Rule
     */
    public function set($value, $message)
    {
        $this->rules[] = $rule = new Rule($value, $message, $this);
        
        return $rule;
    }
    
    /**
     * 検証ルールからエラーメッセージを集める。
     * @return JP\Grampus\Validation\Validator
     */
    protected function _fails()
    {
    	foreach ($this->rules as $rule){
    		$err = $rule->getErr();
    		if(!empty($err)){
    			$this->err[] = $err;
    		}
    	}
    	
    	return $this;
    }
    
    /**
     * 検証ルールが失敗したかの確認
     * @return boolean
     */
    public function fails($success = '', $fail = '')
    {
        if(is_callable($success) && is_callable(($fail))){
            return !empty($this->err)?$fail($this->getErr()): $success();
        }

        return !empty($this->err)?true: false;
    }


    /**
     * 設定した内容を元に検証を行う
     * @return $this
     */
    public function valid()
    {

        foreach ($this->rules as $rule){
            $err = $rule->getErr();
            if(!empty($err)){
                $this->err[] = $err;
            }
        }

        return $this;
    }
    
    /**
     * 検証エラーメッセージの取得
     * @return Ambigous <multitype:, unknown>
     */
    public function getErr()
    {
    	return $this->err;
    }
    
}
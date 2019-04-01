<?php
namespace JP\Grampus\Validation;

use PHPUnit\Framework\TestCase;

/**
 * Ruleクラスのテスト
 */
class ValidatorTest extends TestCase
{
    /**
     * 検証が成功したことを確認する
     * @param $data
     * @param $fn
     */
    protected function ok($data, $fn)
    {
        foreach($data as $item){
            $ret = $fn($item);
            $this->assertFalse($ret);
        }
    }

    /**
     * 検証が失敗したことを確認する
     * @param $data
     * @param $fn
     */
    protected function ng($data, $fn)
    {
        foreach($data as $item){
            $ret = $fn($item);
            $this->assertTrue($ret);
        }
    }

    /**
     * @test
     */
    public function requireOk()
    {
        $this->ok(
            array(
                "1",
                "a",
                "あ",
                "亜",
                array('1')
            ),
            function($item){
            return Validator::make()->set($item, '必須項目テスト')->required()->end()->valid()->fails();
        });
    }

    /**
     * @test
     */
    public function requireNg()
    {
        $this->ng(
            array(
                "",
                array(),
                null
            ),
            function($item){
            return Validator::make()->set($item, '必須項目テスト')->required()->end()->valid()->fails();
        });
    }

    /**
     * @test
     */
    public function numericOK()
    {
        $this->ok(
            array(
                1,
                "1",
                0,
                "0",
                0.001,
                0.0000,
                01234,
                0x1234
            ),
            function($item){
                return Validator::make()->set($item, '数値テスト')->numeric()->end()->valid()->fails();
            });
    }

    /**
     * @test
     */
    public function numericNG()
    {
        $this->ng(
            array(
                "a",
                "あ",
                "亜",
                array('1'),
            ),
            function($item){
                return Validator::make()->set($item, '数値テスト')->numeric()->end()->valid()->fails();
            });
    }

    /**
     * @test
     */
    public function degitOK()
    {
        $this->ok(
            array(
                '0',
                0,
                '1',
                1,
                '123'
            ),
            function($item){
                return Validator::make()->set($item, '数値テスト')->degit()->end()->valid()->fails();
            });
    }

    /**
     * @test
     */
    public function degitNG()
    {
        $this->ng(
            array(
                0.11,
            ),
            function($item){
                return Validator::make()->set($item, '数値テスト')->degit()->end()->valid()->fails();
            });
    }

    /**
     * @test
     */
    public function alphaOK()
    {
        $this->ok(
            array(
                'a',
                'abcdefg',
            ),
            function($item){
                return Validator::make()->set($item, '数値テスト')->alpha()->end()->valid()->fails();
            });
    }

    /**
     * @test
     */
    public function alphaNG()
    {
        $this->ng(
            array(
                '1',
                '0',
                '--',
                'abcd-',
                'abcd1',
                'abcd1-!!'
            ),
            function($item){
                return Validator::make()->set($item, '数値テスト')->alpha()->end()->valid()->fails();
            });
    }

    public function alphaNumericOK()
    {
        $this->ok(
            array(
                'a',
                '1',
                'A',
                'abcdefg123',
                'AbcdE12',
            ),
            function($item){
                return Validator::make()->set($item, '数値テスト')->alphaNumeric()->end()->valid()->fails();
            });
    }

    /**
     * @test
     */
    public function alphaNumericNG()
    {
        $this->ng(
            array(
                'aaaあ',
                'aaa-',
                'aa12?',
                '12!'
            ),
            function($item){
                return Validator::make()->set($item, '数値テスト')->alphaNumeric()->end()->valid()->fails();
            });
    }

    /**
     * @test
     */
    public function emailOK()
    {
        $this->ok(
            array(
                'wakai@Pnkusu.co.jp',
                'Pnkusu@gmail.com',
                'Pnkusu.test@Pnkusu.jp',
            ),
            function($item){
                return Validator::make()->set($item, '数値テスト')->email()->end()->valid()->fails();
            });
    }

    /**
     * @test
     */
    public function emailNG()
    {
        $this->ng(
            array(
                'aaaあ',
                'aaa-',
                'aa12?',
                '12!'
            ),
            function($item){
                return Validator::make()->set($item, '数値テスト')->email()->end()->valid()->fails();
            });
    }

    /**
     * @test
     */
    public function ipOK()
    {
        $this->ok(
            array(
                '127.0.0.1'
            ),
            function($item){
                return Validator::make()->set($item, '数値テスト')->ip()->end()->valid()->fails();
            });
    }

    /**
     * @test
     */
    public function  ipNG()
    {
        $this->ng(
            array(
                'aslsdlks'
            ),
            function($item){
                return Validator::make()->set($item, '数値テスト')->ip()->end()->valid()->fails();
            });
    }

    /**
     * @test
     */
    public function urlOK()
    {
        $this->ok(
            array(
                'http://www.google.com'
            ),
            function($item){
                return Validator::make()->set($item, '数値テスト')->url()->end()->valid()->fails();
            });
    }

    /**
     * @test
     */
    public function urlNG()
    {
        $this->ng(
            array(
                'localhost'
            ),
            function($item){
                return Validator::make()->set($item, '数値テスト')->url()->end()->valid()->fails();
            });
    }


    /**
     * 値が日付であることを検証する(正常値)
     *
     * @test
     */
    public function dateFormatOK()
    {
        date_default_timezone_set('Asia/Tokyo');
        $this->ok(
            array(
                '2000-01-01',
                '01/01/2001',
                '2000-01-01 12:30'
            ),
            function($item){
                return Validator::make()->set($item, '数値テスト')->dateFormat()->end()->valid()->fails();
            });
    }
    /**
     * 値が日付であることを検証する(異常値)
     */
    public function dateFormatNG()
    {
        $this->ng(
            array(
                'Not a Date'
            ),
            function($item){
                return Validator::make()->set($item, '数値テスト')->dataFormat()->end()->valid()->fails();
            });
    }
    /**
     * 値が全角文字であることを検証する(正常値)
     *
     * @test
     */
    public function zenkakuOK()
    {
        $this->ok(
            array(
                "あ",
                "あいうえお"
            ),
            function($item){
                return Validator::make()->set($item, '数値テスト')->zenkaku()->end()->valid()->fails();
            });
    }
    /**
     * 値が全角文字であることを検証する(異常値)
     */
    public function zenkakuNG()
    {
        $this->ng(
            array(
                "a",
                "1"
            ),
            function($item){
                return Validator::make()->set($item, '数値テスト')->zenkaku()->end()->valid()->fails();
            });
    }
    /**
     * 値が全角カタカナであることを検証する(正常値)
     *
     * @test
     */
    public function katakanaOK()
    {
        $this->ok(
            array(
                "ア",
                "アイウエオ"
            ),
            function($item){
                return Validator::make()->set($item, '数値テスト')->katakana()->end()->valid()->fails();
            });
    }
    /**
     * 値が全角カタカナであることを検証する(異常値)
     *
     * @test
     */
    public function katakanaNG()
    {
        $this->ng(
            array(
                "あ",
                "あいうえお",
                "1",
                "asd"
            ),
            function($item){
                return Validator::make()->set($item, '数値テスト')->katakana()->end()->valid()->fails();
            });
    }
    /**
     * 値が全角ひらがなであることを検証する(正常値)
     *
     * @test
     */
    public function hiraganaOK()
    {
        $this->ok(
            array(
                "あ",
                "あいうえお"
            ),
            function($item){
                return Validator::make()->set($item, '数値テスト')->hiragana()->end()->valid()->fails();
            });
    }
    /**
     * 値が全角ひらがなであることを検証する(異常値)
     *
     * @test
     */
    public function hiraganaNG()
    {
        $this->ng(
            array(
                "ア",
                "アイウエオ"
            ),
            function($item){
                return Validator::make()->set($item, '数値テスト')->hiragana()->end()->valid()->fails();
            });
    }

}

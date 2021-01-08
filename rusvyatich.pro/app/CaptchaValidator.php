<?php

namespace App;
/*Валидация капчи гугла*/
class CaptchaValidator
{
    public function post_captcha($user_response) {
        $fields_string = '';
        $fields = array(
            'secret' => '6Lc0l2QUAAAAANuTURXfnjQU4yhxOso653a4NIk6',
            'response' => $user_response
        );
        foreach($fields as $key=>$value)
        $fields_string .= $key . '=' . $value . '&';
        $fields_string = rtrim($fields_string, '&');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, True);

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }
}

<?php


namespace LanDao\LaravelCore\Validation;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;

/**
 * Request 请求校验
 * Class BaseRequest
 * @package LanDao\LaravelCore\Validation
 */
abstract class BaseRequest extends FormRequest
{
    /**
     * 定义验证规则
     * @return array
     */
    public function rules()
    {
        $rule_action = 'getRulesBy' . ucfirst($this->route()->getActionMethod());

        if (method_exists($this, $rule_action))
            return $this->$rule_action();

        return $this->getDefaultRules();
    }

    /**
     * 默认验证规则
     * @return array
     */
    protected function getDefaultRules()
    {
        return [];
    }

    /**
     * 验证消息通过，json抛出，api开发
     * @param Validator $validator
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(Response::json([
            'status' => "error",
            'code' => FoundationResponse::HTTP_BAD_REQUEST,
            'message' => $validator->errors()->first()
        ]));
    }
}

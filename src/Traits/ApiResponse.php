<?php

namespace LanDao\LaravelCore\Traits;

use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;

/**
 * api 请求响应
 * Trait ApiResponse
 * @package JoyceZ\LaravelLib\Traits
 */
trait ApiResponse
{
    /**
     * 状态
     * @var int
     */
    protected $statusCode = FoundationResponse::HTTP_OK;

    /**
     * 获取状态
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * 设置状态
     * @param int $statusCode
     * @return $this
     */
    public function setStatusCode(int $statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function respond($data, $header = [])
    {
        return Response::json($data, $this->getStatusCode(), $header);
    }

    public function status($status, array $data, $code = null)
    {
        if ($code) {
            $this->setStatusCode($code);
        }
        $status = [
            'status' => $status,
            'code' => $this->statusCode
        ];
        if (isset($data['message']) && trim($data['message']) == '') {
            unset($data['message']);
        }
        $data = array_merge($status, $data);
        return $this->respond($data);
    }

    /**
     * 提示
     * @param $message
     * @param string $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function message($message, $status = 'error')
    {
        return $this->status($status, ['message' => $message]);
    }

    /**
     * 500 错误
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function internalError($message = 'Internal Server Error!')
    {
        return $this->failed($message, FoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * 请求成功，服务器创建了新资源
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function created($message = 'created')
    {
        return $this->setStatusCode(FoundationResponse::HTTP_CREATED)->message($message);
    }

    /**
     * 请求方法不存在
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function methodNotAllow($message = 'Method Not Allowed!')
    {
        return $this->setStatusCode(FoundationResponse::HTTP_METHOD_NOT_ALLOWED)->message($message);
    }

    /**
     * 请求错误响应
     * @param $message
     * @param int $code
     * @param string $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function failed($message, $code = FoundationResponse::HTTP_BAD_REQUEST, $status = 'error')
    {
        return $this->setStatusCode($code)->message($message, $status);
    }

    /**
     * 身份验证失败响应
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function unAuthorized($message = 'Unauthorized.')
    {
        return $this->setStatusCode(FoundationResponse::HTTP_UNAUTHORIZED)->message($message);
    }

    /**
     * 服务器未知错误响应
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function serviceUnavailable($message = 'Service Unavailable!')
    {
        return $this->setStatusCode(FoundationResponse::HTTP_SERVICE_UNAVAILABLE)->message($message);
    }

    /**
     * 权限不足响应
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function forbidden($message = 'Forbidden.')
    {
        return $this->setStatusCode(FoundationResponse::HTTP_FORBIDDEN)->message($message);
    }

    /**
     * 表单验证错误响应
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function badRequest($message = 'Bad Request!')
    {
        return $this->setStatusCode(FoundationResponse::HTTP_BAD_REQUEST)->message($message);
    }

    /**
     * 成功响应
     * @param array $data
     * @param string $message
     * @param string $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($data = [], $message = '', $status = 'success')
    {
        return $this->status($status, compact('data', 'message'));
    }

    /**
     * 成功响应带消息
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function successRequest($message = '')
    {
        return $this->status('success', compact('message'));
    }

    /**
     * 请求成功，其他业务状态码响应
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function badSuccessRequest($message = 'Bad Request!', $code = FoundationResponse::HTTP_BAD_REQUEST, $data = [])
    {
        $status = [
            'status' => 'error',
            'code' => $code,
        ];
        $res = ['message' => $message];
        if ($data) {
            $res['data'] = $data;
        }
        $data = array_merge($status, $res);
        return $this->respond($data);
    }

    /**
     * 资源不存在
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function notFond($message = 'Not Fond!')
    {
        return $this->failed($message, FoundationResponse::HTTP_NOT_FOUND);
    }
}
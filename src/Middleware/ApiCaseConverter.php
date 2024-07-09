<?php

namespace LanDao\LaravelCore\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use LanDao\LaravelCore\Helpers\CamelHelper;
use Symfony\Component\HttpFoundation\Response;

/**
 * 1、将前端发送过来的请求参数驼峰转下划线
 * 2、后端响应参数下划线转驼峰
 * Class ApiCaseConverter
 * @package LanDao\LaravelCore\Middleware
 */
class ApiCaseConverter
{

    /**
     * andle an incoming request.
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->convertRequestNameCase($request);
        $response = $next($request);
        $this->convertResponseNameCase($response);
        return $response;
    }

    /**
     * 请求数据驼峰转下划线
     * @param $request
     */
    private function convertRequestNameCase($request): void
    {
        $this->convertParameterNameCase($request->request);;
        $this->convertParameterNameCase($request->query);;
        $this->convertParameterNameCase($request->files);;
        $this->convertParameterNameCase($request->cookies);;
    }

    /**
     * 将驼峰命名转下划线命名
     * @param ParameterBag $parameterBag
     */
    private function convertParameterNameCase($parameterBag): void
    {
        $parameters = $parameterBag->all();
        $parameterBag->replace(CamelHelper::recursiveConvertParameterNameCase($parameters));
    }


    /**
     * 响应数据下划线转驼峰
     * @param $response
     */
    private function convertResponseNameCase($response): void
    {
        $content = $response->getContent();
        $json = json_decode($content, true);
        if (is_array($json)) {
            $json = CamelHelper::recursiveConvertNameCaseToCamel($json);
            $response->setContent(json_encode($json));
        }
    }
}
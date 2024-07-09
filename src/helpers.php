<?php
if (!function_exists('module_path')) {
    /**
     * 指定某个模块的路径
     * @param string $name
     * @param string $path
     * @return string
     */
    function module_path(string $name, string $path = '')
    {
        $module = app('modules')->find($name);
        dd($module);

        return $module->getPath().($path ? DIRECTORY_SEPARATOR.$path : $path);
//        $module = app(Modules::class)->find($name);
//        if (!$module) return '';
//        $modulePath = base_path('module') . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR;
//
//        return $modulePath . $path;
    }
}

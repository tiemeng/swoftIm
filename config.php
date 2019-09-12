<?php
/**
 * Created by PhpStorm.
 * User: tiemeng
 * Date: 2019/5/30
 * Time: 11:10
 */
use Sami\Sami;
use Sami\RemoteRepository\GitHubRemoteRepository;
use Symfony\Component\Finder\Finder;
$iterator = Finder::create()
    ->files()
    ->name("*.php")
    ->exclude("bin")
    ->exclude("public")
    ->exclude("runtime")
    ->exclude("config")
    ->exclude("test")
    ->exclude("vendor")
    ->exclude("resource")
    ->in("./");
return new Sami($iterator,[
    "theme" => "default",
    "title" => "接口文档",
    "build_dir" => __DIR__ . "/public/docs/build",
    "cache_dir" => __DIR__ . "/public/docs/cache",
]);

<?php

declare(strict_types=1);

namespace RssApp\Components\Twig\Filters;

class StaticFiles
{
    public static function jsTag(string $filename): string
    {
        $query = "";
        if (!(strpos($filename, "?") === false)) {
            $query = substr($filename, strpos($filename, "?") + 1);
            $filename = substr($filename, 0, strpos($filename, "?"));
        }
        $timestamp = filemtime(BASEPATH.DS.'public'.DS.'js'.DS.$filename);
        if ($query) {
            $timestamp .= "&$query";
        }

        return "<script type=\"text/javascript\" charset=\"utf-8\" src=\"/js/$filename?$timestamp\"></script>\n";
    }

    public static function cssTag(string $filename): string
    {
        $timestamp = filemtime(BASEPATH.DS.'public'.DS.'css'.DS.$filename);
        return "<link rel=\"stylesheet\" type=\"text/css\" data-orig-href=\"/css/$filename\" href=\"/css/$filename?$timestamp\"/>\n";
    }

    public static function errorMessage(string $message)
    {
        return '<div class="alert alert-danger">'.$message.'</div>';
    }

    public static function warningMessage(string $message)
    {
        return '<div class="alert">'.$message.'</div>';
    }

    public static function noticeMessage(string $message)
    {
        return '<div class="alert-info">'.$message.'</div>';
    }
}

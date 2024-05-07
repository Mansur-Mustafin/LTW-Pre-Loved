<?php

function getTimePassed(int $created_at) : string 
{
    $now = new DateTime();
    $eventTime = new DateTime();
    $eventTime->setTimestamp($created_at);
    $interval = $now->diff($eventTime);

    if ($interval->y > 0) {
        return $interval->y . ' year' . ($interval->y == 1 ? '' : 's') . ' ago';
    } elseif ($interval->m > 0) {
        return $interval->m . ' month' . ($interval->m == 1 ? '' : 's') . ' ago';
    } elseif ($interval->d > 0) {
        return $interval->d . ' day' . ($interval->d == 1 ? '' : 's') . ' ago';
    } elseif ($interval->h > 0) {
        return $interval->h . ' hour' . ($interval->h == 1 ? '' : 's') . ' ago';
    } elseif ($interval->i > 0) {
        return $interval->i . ' minute' . ($interval->i == 1 ? '' : 's') . ' ago';
    } else {
        return 'Just now';
    }
}

function ensureFolderExists($path) 
{
    if (!file_exists($path)) {
        if (!mkdir($path)) {
            throw new Exception("Failed to create directory: " . $path);
        } 
    } 
}

function urlTo(string $uri, array $params): string
{
    $httpQuery = http_build_query($params);
    return "{$uri}?{$httpQuery}";
}

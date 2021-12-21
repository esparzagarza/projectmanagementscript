<?php

namespace App;

class Helpers
{
    public static function permittedTypes(): array
    {
        return array('activity', 'appointment', 'call', 'demo', 'email', 'event', 'meet', 'raise', 'task', 'visit');
    }

    public static function permittedProperties(): array
    {
        return array('type', 'name', 'description', 'notes', 'tags', 'prio', 'status', 'duedate');
    }

    public static function isPermittedKey(string $key, array $permitted): bool
    {
        return in_array($key, $permitted) ? true : false;
    }

    public static function isPermittedType(string $value): bool
    {
        return in_array($value, self::permittedTypes()) ? true : false;
    }

    public static function stringValid($value): bool
    {
        return is_string($value) ? true :  false;
    }

    public static function numericValid($value): bool
    {
        return is_numeric($value) ? true :  false;
    }

    public static function greaterThanCero($value): bool
    {
        return is_numeric($value) && $value > 0 ? true :  false;
    }

    public static function cleanVar(array $array): array
    {
        foreach ($array as $key => $value) $array[$key] = htmlspecialchars(strip_tags(trim($value)));

        return $array;
    }

    public static function createTask(string $type): object
    {
        return new Task($type);
    }

    public static function formatResponse(int $httpStatusCode, string $message, array $data = []): array
    {
        return [
            'httpStatusCode' => $httpStatusCode,
            'message' => $message,
            'data' => $data
        ];
    }

    public static function returnToAction(array $response): void
    {
        http_response_code($response['httpStatusCode']);

        echo json_encode($response);
    }
}

<?php

namespace Wiki\controllers\requestHandlers;

use Wiki\tools\interfaces\iRequestHandler;

abstract class BaseRequestHandler implements iRequestHandler
{
    protected array $response;

    abstract public function handleRequest(array $request): array;

            /**
     * changes the array with given keys to values for the checkboxgroup to mark
     * so example : [tag5 => 5] becomes [5 => 1] 
     * @param array $result_array array containing the arrays that need to be changes.
     * @param array $keys is an array with key names used to find the array that have to be changed.
     * @return array the marked array 
     */
    protected function arrayToMarkedArray(array $result_array, array $keys)
    {
        $marked_array = [];

        foreach ($keys as $key) {
            $marked_array[$key] = [];
            foreach ($result_array[$key] ?? [] as $value) {
                $marked_array[$key][$value] = '1';
            }
        }

        return $marked_array;

    }
}

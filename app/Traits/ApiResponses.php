<?php

namespace App\Traits;

trait ApiResponses
{
    public function jsonResponse($data = [], int $status = 200)
    {
        return response()->json([
            $data
        ], $status);
    }

  
}

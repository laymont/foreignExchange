<?php

namespace App\Concerns;

use Illuminate\Http\Request;

trait HandlePerPageTrait
{
    /**
     * Get the per page value from the request.
     */
    protected function getPerPage(Request $request): int
    {
        return $request->has('per_page') ? $request->get('per_page') : 10;
    }
}

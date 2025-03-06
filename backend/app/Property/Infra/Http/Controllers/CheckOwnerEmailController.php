<?php

declare(strict_types=1);

namespace App\Property\Infra\Http\Controllers;

use App\Property\Domain\Contracts\PropertyRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Shared\Infra\Traits\JsonResponsable;

class CheckOwnerEmailController extends Controller
{
    use JsonResponsable;

    public function __construct(
        private PropertyRepository $repo
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $email = $request->query('email', '');
        return $this->success(data: $this->repo->findOwnerByField('email', $email)?->toArray() ?? []);
    }
}

<?php

namespace App\Http\Middleware;

use App\Http\Requests\Authentication\Auth\CheckRoleRequest;
use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(CheckRoleRequest $request, Closure $next)
    {
        $role = $request->user()->roles()
            ->where(function ($query) use ($request) {
                $query->where('institution_id', $request->institution)
                    ->orWhere('system_id', $request->system);
            })
            ->where('role_id', $request->role)
            ->first();

        if (!$role) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'No tiene un rol asignado para el sistema o institución (check-role)',
                    'detail' => 'Comuníquese con el administrador',
                    'code' => '403'
                ]
            ], 403);
        }
        return $next($request);
    }
}

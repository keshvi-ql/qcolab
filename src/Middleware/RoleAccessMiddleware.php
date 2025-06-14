<?php

namespace App\Middleware;

use Cake\ORM\TableRegistry;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Cake\Http\Exception\ForbiddenException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RoleAccessMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Skip permission check for DebugKit
        if ($request->getParam('plugin') === 'DebugKit') {
            return $handler->handle($request);
        }

        // Get the requested controller and action from the request
        $requestedController = $request->getParam('controller');
        $requestedAction = $request->getParam('action');

        // Define a list of actions that are accessible by all users without authentication
        $publicAccessActions = ['login', 'logout', 'forgotPassword', 'resetPassword', 'clockIn', 'pause', 'resume', 'clockOut', 'countNotifications', 'fetchNotifications'];

        // Allow access to the public actions without further checks
        if (in_array($requestedAction, $publicAccessActions)) {
            return $handler->handle($request);
        }

        // Get the current authenticated user from the request
        $currentUser = $request->getAttribute('identity');

        // If a user is authenticated, check their permissions
        if ($currentUser) {
            
            // 👇 Handle self-edit access
            if ($requestedController === 'Users' && $requestedAction === 'edit') {
                $routeUserId = (int)$request->getParam('pass')[0] ?? null;
                if ($routeUserId === $currentUser->id) {
                    return $handler->handle($request); // allow if editing own profile
                }
            }

            // Check permission if not self-edit and Get the RolePermissions table
            $rolePermissionsTable = TableRegistry::getTableLocator()->get('RolePermissions');

            // Find role permissions for the current user's role
            $userRolePermission = $rolePermissionsTable->find()
                ->where([
                    'role_id' => $currentUser->role_id,
                    'controller' => $requestedController,
                    'action' => $requestedAction
                ])
                ->first();

            // Check if a matching permission is found
            if (!$userRolePermission) {
                throw new ForbiddenException('You do not have permission to access this page.');
            }
            // If we reach this point, access is allowed (implicit)
        }
        // If no user is authenticated, the middleware will continue to the next handler
        // which should handle the case (e.g., redirect to login)

        return $handler->handle($request);
    }
}

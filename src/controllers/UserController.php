<?php namespace Qlcorp\VextSecurity;

use Qlcorp\VextFramework\TreeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\Collection;

class UserController extends TreeController {
    protected $Model = '\User';
    protected $root = 'children';
    protected $user_tree = null;

    /**
     * Get the view for the current authenticated user
     *
     * Note: uses 'getViewportAttribute' accessor in the User model
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getViewport() {
        return Response::json(array(
           'viewport' => Auth::user()->viewport
        ));
    }

    /**
     * User can only access Departments/Users belonging to it's top-level ancestor
     */
    public function __construct() {
        $user = Auth::user();
        $this->user_tree = $user->getTopLevel()->load('children');
        parent::__construct();
    }

    /**
     * Signs user out of application
     *
     * @return \Illuminate\Support\Facades\Redirect;
     */
    public function anyLogout() {
        Auth::logout();
        return Redirect::to('/');
    }

    /**
     * Retrieves Users belonging to parent organization
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRead() {
        if ( is_null($this->user_tree) ) {
            return $this->failure('No parent organization found');
        } else {
            return parent::getRead();
        }
    }

    public function getNode($id, $parentKey = null, $parentValue = null) {
        return $this->user_tree;
    }

    public function getRecords($parentKey = null, $parentValue = null) {
        $this->root = 'user';
        return new Collection($this->flatten($this->user_tree));
    }

    public function getDepartments() {
        $this->root = 'user';
        $users = $this->flatten($this->user_tree);
        $departments = array_values(array_filter($users, function($user) {
            return $user->type === 'department';
        }));
        $departments = new Collection($departments);

        return $this->success($departments, array('total' => $departments->count()));
    }

    /**
     * Updates User
     *
     * pre: Authenticated User must have access to this User
     * @return \Illuminate\Http\Response|string
     */
    public function postUpdate() {
        if ( $this->isNodeAccessible(Input::get('id')) ) {
            return parent::postUpdate();
        } else {
            return Response::make('Unauthorized', 403);
        }
    }

    /**
     * Moves User
     *
     * pre: Authenticated User must have access to this User
     * @return \Illuminate\Http\Response|string
     */
    public function postMove() {
       if ( $this->isNodeAccessible(Input::get('id'))
         && $this->isNodeAccessible(Input::get('newParentId')) ) {
           return parent::postMove();
       }  else {
           return Response::make('Unauthorized', 403);
       }
    }

    /**
     * Checks if Node is accessible by authenticated User
     *
     * User node must be a descendent of the authenticated User's parent-Organization
     *
     * @param $id id of User node/record
     * @return bool
     */
    protected function isNodeAccessible($id) {
        $User = $this->Model;
        $user = $User::findOrFail($id);

        return $this->isNodeInTree($user, $this->user_tree);
    }

    /**
     * Checks if a node exists in a tree
     *
     * Uses id of node for checking
     *
     * @param $node node to search for
     * @param $tree tree to search in
     * @return bool
     */
    protected function isNodeInTree($node, $tree) {
        if ( $node->id === $tree->id ) {
            return true;
        } else if ( $tree->children->isEmpty() ) {
            return false;
        } else {
            foreach ($tree->children as $child) {
                if ( $this->isNodeInTree($node, $child) ) {
                    return true;
                }
            }
        }
    }
}
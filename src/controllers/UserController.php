<?php namespace Qlcorp\VextSecurity;

use Qlcorp\VextFramework\TreeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;

class UserController extends TreeController {
    protected $Model = '\User';
    protected $root = 'children';
    protected $user_tree = null;

    /**
     * User can only access Departments/Users belonging to parent Organization
     */
    public function __construct() {
        $user = Auth::user();
        $this->user_tree = $this->parentOrganization($user)->load('children');
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
            return $this->success($this->user_tree);
        }
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

    /**
     * Get the parent-Organization of this User
     *
     * @param $user
     * @return mixed
     */
    protected function parentOrganization($user) {
        while( !is_null($user) && $user->type !== 'organization' ) {
            $user = $user->parent;
        }

        return $user;
    }
}
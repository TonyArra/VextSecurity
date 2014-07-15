<?php namespace Qlcorp\VextSecurity;

use Qlcorp\VextFramework\TreeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class UserController extends TreeController {
    protected $Model = '\User';
    protected $root = 'children';

    public function anyLogout() {
        Auth::logout();
        return Redirect::to('/');
    }

    public function getRead() {
        $user = Auth::user();
        $user_tree = $this->parentOrganization($user)->load('children');

        if ( is_null($user_tree) ) {
            return $this->failure('No parent organization found');
        } else {
            return $this->success($user_tree);
        }

    }

    private function parentOrganization($user) {
        while( !is_null($user) && $user->type !== 'organization' ) {
            $user = $user->parent;
        }

        return $user;
    }
}
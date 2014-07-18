<?php namespace Qlcorp\VextSecurity;

use Qlcorp\VextFramework\TreeController;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Collection;

class RoleController extends TreeController {
    protected $Model = '\Role';
    protected $root = 'children';

    /*public function getRead() {
        if ( Input::has('user_id') ) {
            $department_id = Input::get('user_id') * 1;
            $department = \User::find($department_id);

            if ( $department->type !== 'department' ) {
                return $this->failure(null, "Must add role to Department");
            } else {
                $role_tree = $department->roles->load('children');
                //$this->root = 'children';
                return $this->success($role_tree);
            }

        } else {
            return parent::getRead();
        }

    }*/

    protected function getRecords($parentKey = null, $parentValue = null) {
        $this->root = 'role';

        $records = \Role::with('roleChildren')->whereNull('parentId')->first();

        return new Collection($this->flatten($records));
    }

    protected function flatten($tree) {
        $flat_tree = array($tree);

        if ( !$tree->roleChildren->isEmpty() ) {
            foreach ($tree->roleChildren as $child) {
                $flat_tree = array_merge($flat_tree, $this->flatten($child));
            }
        }

        return $flat_tree;
    }
}
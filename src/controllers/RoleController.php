<?php namespace Qlcorp\VextSecurity;

use Qlcorp\VextFramework\TreeController;

class RoleController extends TreeController {
    protected $Model = '\Role';
    protected $root = 'role';
}
<?php namespace Qlcorp\VextSecurity;
/**
 * Created by PhpStorm.
 * User: tony
 * Date: 5/6/14
 * Time: 3:21 PM
 */

use Qlcorp\VextFramework\TreeController;

class RoleController extends TreeController {
    protected $Model = '\Role';
    protected $root = 'role';
}
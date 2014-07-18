<?php namespace Qlcorp\VextSecurity;
/**
 * Created by PhpStorm.
 * User: tony
 * Date: 5/6/14
 * Time: 3:21 PM
 */

use Qlcorp\VextFramework\CrudController;

class DepartmentRoleController extends CrudController {
    protected $Model = '\DepartmentRole';
    protected $root = 'departmentRole';
}
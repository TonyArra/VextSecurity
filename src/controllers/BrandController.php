<?php namespace Qlcorp\VextSecurity;
/**
 * Created by PhpStorm.
 * User: tony
 * Date: 5/6/14
 * Time: 3:21 PM
 */

use Qlcorp\VextFramework\CrudController;

class BrandController extends CrudController {
    protected $Model = '\Brand';
    protected $root = 'brand';
}
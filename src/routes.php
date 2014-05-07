<?php

Route::get('security',function(){
   return "Security";
});

Route::controller('group', 'Qlcorp\VextSecurity\GroupController');
Route::controller('brand', 'Qlcorp\VextSecurity\BrandController');
Route::controller('role', 'Qlcorp\VextSecurity\RoleController');
Route::controller('permission', 'Qlcorp\VextSecurity\PermissionController');
Route::controller('groupRole', 'Qlcorp\VextSecurity\GroupRoleController');
Route::controller('permissionRole', 'Qlcorp\VextSecurity\PermissionRoleController');
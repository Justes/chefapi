<?php
/**
 * UserController.php
 *
 * @copyright  Aoya Inc.
 * @author     Lin <lin@aoya.it>
 * @created    2024/5/17 18:21
 */

namespace App\Http\Controllers\Api;

use App\Models\User;

class UserController extends BaseController
{
    public function index()
    {
        $users = User::all();

        return json_suc($users);
    }
}

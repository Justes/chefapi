<?php
/**
 * HomeController.php
 *
 * @copyright  Aoya Inc.
 * @author     Lin <lin@aoya.it>
 * @created    2024/5/20 10:34
 */

namespace App\Http\Controllers\Api;

use App\Models\Category;

class HomeController extends BaseController
{
    public function index()
    {
        $data['categories'] = Category::active()->orderBy('sort', 'asc')->get(['id', 'name', 'sort']);
        return json_suc($data);
    }
}

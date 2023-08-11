<?php

namespace App\Http\V1\Controllers\Ref;

use App\Http\V1\Controllers\Controller;
use App\Http\V1\Resources\Ref\ItemType\Collection as ItemTypeCollection;
use Illuminate\Http\Request;
use Tagd\Core\Models\Item\Type as ItemType;

class ItemTypes extends Controller
{
    public function index(
        Request $request
    ) {
        return response()->withData(
            new ItemTypeCollection(ItemType::root()->with('children')->get())
        );
    }
}

<?php

namespace App\Observers;

use App\Models\Store;
use App\Models\StoreBook;
use Illuminate\Support\Facades\Log;

class StoreBookObserver
{

    public function updated(StoreBook $storeBook): void
    {

        // Update Book balance here
        $storeBookBalance = StoreBook::where('book_id', $storeBook->book->id)->sum('balance');
        $storeBook->book->balance = $storeBookBalance;
        $storeBook->book->save();
    }
}

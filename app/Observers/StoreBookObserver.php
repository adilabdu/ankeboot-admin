<?php

namespace App\Observers;

use App\Models\StoreBook;

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

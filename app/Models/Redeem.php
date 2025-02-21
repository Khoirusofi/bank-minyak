<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Redeem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'booking_trx_id',
        'total_redeem_price',
        'status',
        'method',
        'bank_number',
        'tax',
        'grand_redeem_total',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function generateUniqueTrxId()
    {
        $prefix = 'BMJPKH';
        do {
            $randomString = $prefix . mt_rand(100000, 999999);
        } while (self::where('booking_trx_id', $randomString)->exists());

        return $randomString;
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($redeem) {
            if ($redeem->status === 'approved') {
                $redeem->adjustOilData('reduce');
            }
            if ($redeem->method === 'cash') {
                $redeem->bank_number = null;
            }
        });

        static::updating(function ($redeem) {
            $originalStatus = $redeem->getOriginal('status');

            if ($originalStatus === 'approved' && $redeem->status !== 'approved') {
                $redeem->adjustOilData('rollback');
            } elseif ($originalStatus !== 'approved' && $redeem->status === 'approved') {
                $redeem->adjustOilData('reduce');
            }

            if ($redeem->method === 'cash') {
                $redeem->bank_number = null;
            }
        });

        static::deleting(function ($redeem) {
            if ($redeem->status === 'approved') {
                $redeem->adjustOilData('rollback');
            }
        });
    }

    /**
     * Menyesuaikan saldo sesuai status redeem.
     * @param string $action 'reduce' untuk mengurangi saldo, 'rollback' untuk mengembalikan saldo.
     */
    public function adjustOilData($action)
    {
        DB::transaction(function () use ($action) {
            $oilData = OilData::where('user_id', $this->user_id)->lockForUpdate()->first();

            if (!$oilData) return;

            if ($action === 'reduce') {
                // Pastikan saldo cukup sebelum mengurangi
                if ($oilData->total_saldo_price >= $this->total_redeem_price) {
                    $oilData->decrement('total_saldo_price', $this->total_redeem_price);
                }
            } elseif ($action === 'rollback') {
                $oilData->increment('total_saldo_price', $this->total_redeem_price);
            }
        });
    }
}

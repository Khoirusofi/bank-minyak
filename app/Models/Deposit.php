<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\OilData;

class Deposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_liter',
        'total_price',
        'oil_price',
        'created_by',
        'updated_by',
    ];

    /**
     * Relasi ke model User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Boot method untuk menangani event created, updated, deleted
     */
    protected static function boot()
    {
        parent::boot();

        // Saat data baru dibuat
        static::creating(function ($deposit) {
            $deposit->created_by = Auth::id(); // Set created_by dengan ID pengguna yang sedang login
        });

        // Saat data diperbarui
        static::updating(function ($deposit) {
            $deposit->updated_by = Auth::id(); // Set updated_by dengan ID pengguna yang sedang login
        });

        // Saat data baru dibuat
        static::created(function ($deposit) {
            $deposit->addOilData(); // Update OilData setelah deposit dibuat
        });

        // Saat data diperbarui
        static::updating(function ($deposit) {
            $deposit->updateOilData(); // Update OilData setelah deposit diperbarui
        });

        // Saat data dihapus
        static::deleting(function ($deposit) {
            $deposit->removeOilData(); // Update OilData setelah deposit dihapus
        });
    }

    /**
     * Menambahkan jumlah deposit ke OilData
     */
    public function addOilData()
    {
        // Ambil atau buat OilData untuk user yang sesuai
        $oilData = OilData::firstOrCreate(['user_id' => $this->user_id], [
            'total_saldo_price' => 0,
        ]);

        // Tambahkan total_price ke oil data
        $oilData->increment('total_saldo_price', $this->total_price);
    }

    /**
     * Memperbarui saldo pada OilData saat deposit diperbarui
     */
    public function updateOilData()
    {
        // Ambil OilData untuk user yang sesuai
        $oilData = OilData::where('user_id', $this->user_id)->first();

        if (!$oilData) return;

        // Kurangi saldo sebelumnya
        $oilData->decrement('total_saldo_price', $this->getOriginal('total_price'));

        // Tambahkan saldo yang baru
        $oilData->increment('total_saldo_price', $this->total_price);
    }

    /**
     * Mengurangi saldo jika deposit dihapus
     */
    public function removeOilData()
    {
        // Ambil OilData untuk user yang sesuai
        $oilData = OilData::where('user_id', $this->user_id)->first();

        if (!$oilData) return;

        // Kurangi total_price yang dihapus
        $oilData->decrement('total_saldo_price', $this->total_price);
    }
}

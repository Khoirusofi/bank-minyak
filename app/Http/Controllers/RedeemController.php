<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use App\Models\Redeem;
use App\Models\OilData;
use App\Models\OilPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RedeemController extends Controller
{
    public function redeem()
    {
        $user = Auth::user();
        $saldo = $user->oilData ? $user->oilData->total_saldo_price : 0;
        $oilPrice = OilPrice::latest()->first();
        $taxPrice = Tax::latest()->first();
        $latestOilData = OilData::where('user_id', Auth::id())->latest()->first();

        return view('front.redeem', compact(
            'latestOilData',
            'oilPrice',
            'user',
            'saldo',
            'taxPrice'
        ));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'method' => 'required|in:cash,transfer',
            'redeemAmount' => 'required|numeric|min:30000',
        ], [
            'method.required' => 'Pilih metode pencairan antara Tunai atau Transfer.',
            'redeemAmount.required' => 'Minimal pencairan sebesar Rp 30.000.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Ambil user yang sedang login
        $user = Auth::user();
        $taxPrice = Tax::latest()->first();

        // Ambil saldo minyak terbaru berdasarkan user_id
        $latestOilData = OilData::where('user_id', $user->id)->latest('updated_at')->first();

        // Validasi jika tidak ada data OilData atau saldo minyak <= 0
        if (!$latestOilData || $latestOilData->total_saldo_price <= 0) {
            return back()->withErrors([
                'error' => 'Anda tidak memiliki saldo untuk melakukan pencairan.'
            ]);
        }

        // Hitung total saldo minyak yang tersedia
        $totalSaldo = $latestOilData->total_saldo_price;

        // Hitung total pencairan yang masih pending
        $totalPendingRedeem = Redeem::where('user_id', $user->id)
            ->where('status', 'pending')
            ->sum('total_redeem_price');

        // Hitung saldo yang benar-benar bisa digunakan
        $availableSaldo = $totalSaldo - $totalPendingRedeem;

        // Validasi saldo mencukupi untuk pencairan baru
        if ($availableSaldo < $request->redeemAmount) {
            return back()->withErrors([
                'error' => "Saldo Anda saat ini adalah Rp " . number_format($totalSaldo, 0, ',', '.') .
                    ", namun Anda sudah memiliki pengajuan pencairan sebesar Rp " . number_format($totalPendingRedeem, 0, ',', '.') .
                    ". Sehingga saldo yang tersedia untuk pencairan baru adalah Rp " . number_format($availableSaldo, 0, ',', '.') .
                    ". Harap tunggu persetujuan admin sebelum melakukan pencairan baru."
            ]);
        }

        if ($request->method === 'transfer' && empty($user->bank_number)) {
            return redirect()->route('profile.edit')->withErrors([
                'error' => 'Anda belum memiliki nomor rekening bank. Silakan lengkapi informasi bank Anda terlebih dahulu.'
            ])->withInput()->header('Location', route('profile.edit') . '#bankInfo');
        }

        // Ambil nilai pajak berdasarkan metode
        $tax = ($request->method === 'cash') ? $taxPrice->taxCash : $taxPrice->taxTransfer;
        $grand_total = $request->redeemAmount - $tax;

        // Gunakan transaksi database untuk menghindari inkonsistensi data
        DB::beginTransaction();
        try {
            // Simpan transaksi pencairan
            $redeem = Redeem::create([
                'user_id' => $user->id,
                'booking_trx_id' => Redeem::generateUniqueTrxId(),
                'total_redeem_price' => $request->redeemAmount,
                'status' => 'pending',
                'method' => $request->input('method'),
                'bank_number' => $request->method === 'transfer' ? $user->bank_number : null,
                'tax' => $tax,
                'grand_redeem_total' => $grand_total,
            ]);

            DB::commit();

            return redirect()->route('deposit')->with('success', 'Pencairan berhasil diajukan dan sedang menunggu persetujuan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat memproses pencairan. Silakan coba lagi nanti.'
            ]);
        }
    }


}

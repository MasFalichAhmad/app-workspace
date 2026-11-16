<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return 'PageController@index - Daftar Halaman Sidebar';
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return 'PageController@create - Form Tambah Halaman';
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        'PageController@store - Proses Simpan Halaman';
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        "PageController@show - Menampilkan Isi Halaman ID: {$id}";
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        "PageController@edit - Form Edit Halaman ID: {$id}";
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        "PageController@update - Proses Update Halaman ID: {$id}";
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return "PageController@destroy - Proses Hapus Halaman ID: {$id}";
    }
    
    public function toggleSelesai(string $id)
    {
        return "ItemController@toggleSelesai - Ubah status selesai item ID: {$id}";
    }
}

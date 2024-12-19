<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Articles;


class ArticlesController extends Controller
{
    public function show(){
        $getArticles = Articles::with('user')->latest()->get();

        return view('pages.dashboard.users.index', compact('getArticles'));
    }

    public function create(){
        return view('pages.articles.create');
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'   => 'required|string|max:255',
            'banner'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'content' => 'required|string|min:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $bannerPath = null;
        if ($request->hasFile('banner')) {
            $image = $request->file('banner');
            $fileName = time() . '-' . str($request->title)->slug() . '.' . $image->extension();
            $bannerPath = $image->storeAs('banners', $fileName, 'public');
        }

        Articles::create([
            'id_users' => Auth::id(),
            'title'    => strip_tags($request->input('title')),
            'banner'   => $bannerPath ? Storage::url($bannerPath) : null,
            'content'  => $request->input('content'),
            'status'   => 'pending',
        ]);

        return redirect()->route('show.articles')
            ->with('success', 'Artikel berhasil dibuat dan menunggu persetujuan.');
    }

    public function edit($id)
    {
        // Cari artikel berdasarkan ID
        $article = Articles::findOrFail($id);

        // Pastikan hanya pemilik artikel yang bisa mengedit
        if ($article->id_users !== Auth::id()) {
            return redirect()->route('show.articles')->with('error', 'Anda tidak memiliki izin untuk mengedit artikel ini.');
        }

        // Tampilkan form edit dengan data artikel
        return view('articles.edit', compact('article'));
    }

    public function update(Request $request, $id)
    {
        // Cari artikel berdasarkan ID
        $article = Articles::findOrFail($id);

        // Pastikan hanya pemilik artikel yang bisa mengupdate
        if ($article->id_users !== Auth::id()) {
            return redirect()->route('show.articles')->with('error', 'Anda tidak memiliki izin untuk mengupdate artikel ini.');
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'title'   => 'required|string|max:255',
            'banner'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'content' => 'required|string|min:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update banner jika ada file baru
        if ($request->hasFile('banner')) {
            // Hapus banner lama jika ada
            if ($article->banner) {
                $oldBannerPath = str_replace('/storage/', '', $article->banner);
                Storage::disk('public')->delete($oldBannerPath);
            }

            // Simpan banner baru
            $image = $request->file('banner');
            $fileName = time() . '-' . str($request->title)->slug() . '.' . $image->extension();
            $bannerPath = $image->storeAs('banners', $fileName, 'public');
            $article->banner = Storage::url($bannerPath);
        }

        // Update data artikel
        $article->title   = strip_tags($request->input('title'));
        $article->content = $request->input('content');
        $article->status  = 'pending'; // Update status kembali ke pending
        $article->save();

        return redirect()->route('show.articles')
            ->with('success', 'Artikel berhasil diperbarui dan menunggu persetujuan.');
    }
    



}

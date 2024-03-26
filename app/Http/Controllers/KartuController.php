<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Kartu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class KartuController extends Controller
{
    function index()
    {
        try {
            $kartu = Kartu::paginate(5);
            if (!$kartu) {
                return Endpoint::warning(200, 'Data kartu masih kosong');
            }
            return Endpoint::success(200, 'Berhasil mendapatkan data kartu', $kartu);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal mendapatkan kartu', $th->getMessage());
        }
    }

    function store(Request $req)
    {
        try {
            $this->validate($req, [
                'title' => 'required'
            ]);
            $requestData = [
                'id'        => mt_rand(),
                'title'     => $req->title,
                'content'   => '',
            ];
            $jsonFile = File::get('default.json');
            $requestData['content'] = $jsonFile;

            Kartu::create($requestData);
            return Endpoint::success(200, 'Berhasil menambahkan kartu');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal menambahkan kartu', $th->getMessage());
        }
    }

    function update(Request $req, $id)
    {
        try {
            $this->validate($req, [
                'title' => 'required'
            ]);
            $requestData = [
                'title'     => $req->title
            ];
            Kartu::where('id', $id)->update($requestData);
            return Endpoint::success(200, 'Berhasil mengubah kartu');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal mengubah kartu', $th->getMessage());
        }
    }

    public function find($id)
    {
        $kartu = Kartu::findOrFail($id);
        return Endpoint::Success(200, 'Berhasil', $kartu);
    }

    public function destroy($id)
    {
        try {
            $kartu = Kartu::findOrFail($id);
            $kartu->delete();
            return Endpoint::Success(200, 'Berhasil');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal', $th->getMessage());
        }
    }

    public function loadKartu($id)
    {
        $page = Kartu::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $page->content,
            'message' => 'Success load page content'
        ]);
    }

    public function storeKartu(Request $request, $id)
    {
        $page = Kartu::findOrFail($id);
        $page->update(['content' => json_encode($request->data)]);
        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Project stored successfully'
        ]);
    }
}

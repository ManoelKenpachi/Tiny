<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Services\TinyAPI;

class ProdutoController extends Controller
{
    protected $tinyAPI;

    public function __construct(TinyAPI $tinyAPI)
    {
        $this->tinyAPI = $tinyAPI;
    }

    public function index()
    {
        return Produto::all();
    }

    public function getlistProducts()
    {
        $response = $this->tinyAPI->TinyRequest("/produtos.pesquisa.php");

        return response()->json([
            'status' => $response['status'],
            'data' => $response['body'] ?? null,
            'errors' => $response['erros'] ?? null,
        ]);
    }

    public function getStockId(int $id)
    {
        $query = http_build_query(['id' => $id]);
        return $this->tinyAPI->TinyRequest("/produto.obter.estoque.php", "&$query");
    }

    public function postAddProduct(Request $request)
    {
        $data =json_encode($request->all());
        return $this->tinyAPI->TinyRequest("/produto.incluir.php", "&produto=" . $data);
    }

    public function putProductId(Request $request)
    {
        $data =json_encode($request->all());
        return $this->tinyAPI->TinyRequest("/produto.alterar.php", "&produto=" . json_encode($data));
    }

    public function putStockId(Request $request)
    {
        $data = $request->validate([
            'estoque.idProduto' => 'required|integer', // ID do produto é obrigatório e deve ser um número inteiro
            'estoque.tipo' => 'required|string|in:E,S', // Tipo é obrigatório e deve ser 'E' (entrada) ou 'S' (saída)
            'estoque.quantidade' => 'required|integer|min:1', // Quantidade é obrigatória, deve ser um número inteiro e no mínimo 1
        ]);

        return $this->tinyAPI->TinyRequest("/produto.atualizar.estoque.php", "&estoque=" . json_encode($data));
    }
}
